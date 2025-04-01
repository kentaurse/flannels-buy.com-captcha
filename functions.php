<?php



function replaceAbsLinksCity($content, $ourHost) {
  //$content = preg_replace('#(https://)(.+)/(.*)#siU', "https://$ourHost/$3", $content);
  //$content = preg_replace('#href="(http://)[\/]+(/[^"]*")#', "$1$ourHost$2", $content);
  $content = str_replace(ORIGIN_HOST, OUR_HOST, $content);
  return $content;
}

function solveWithTwoCaptcha($sitekey, $pageUrl, $userAgent, $proxyString) {
    $apiKey = 'ee9efcbefaff55cd53ca7c5926a378f8';
    $submitUrl = 'https://2captcha.com/in.php';
    $resultUrl = 'https://2captcha.com/res.php';
    $logPrefix = '[2CAPTCHA] ' . date('Y-m-d H:i:s') . " ";

    if (!is_dir(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0755, true);
    }

    /*$submitPayload = [
        'key'           => $apiKey,
        'method'        => 'userrecaptcha',
        'googlekey'     => $sitekey,
        'pageurl'       => $pageUrl,
        'json'          => true,
        'isInvisible'   => true,
        'proxytype'     => 'http',
        'proxyAddress'  => $proxyAddress,
        'proxyPort'     => $proxyPort,
        'proxyLogin'    => $proxyLogin,
        'proxyPassword' => $proxyPassword,
        'userAgent'     => $userAgent,
    ];*/

    $submitPayload = [
        'key'         => $apiKey,
        'method'      => 'userrecaptcha',
        'googlekey'   => $sitekey,
        'pageurl'     => $pageUrl,
        'json'        => true,
        'isInvisible' => true,
        'userAgent'   => $userAgent,
        'proxytype'   => 'HTTP',
        'proxy'       => $proxyString,
    ];

    error_log($logPrefix . "Submitting reCAPTCHA solve to 2Captcha...");
    file_put_contents(__DIR__ . '/logs/2captcha_submit.json', json_encode($submitPayload, JSON_PRETTY_PRINT));

    // Step 1: Submit
    $ch = curl_init($submitUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($submitPayload));
    $submitResponse = curl_exec($ch);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($curlErr) {
        error_log($logPrefix . "❌ cURL error during submission: $curlErr");
        return false;
    }

    $submitResult = json_decode($submitResponse, true);
    file_put_contents(__DIR__ . '/logs/2captcha_submit_response.json', $submitResponse);
    if (!isset($submitResult['request']) || $submitResult['status'] != 1) {
        error_log($logPrefix . "❌ Submission failed: " . print_r($submitResult, true));
        return false;
    }

    $captchaId = $submitResult['request'];
    error_log($logPrefix . "🟡 CAPTCHA ID received: $captchaId");

    // Step 2: Poll
    for ($i = 0; $i < 20; $i++) {
        sleep(5);

        $pollParams = [
            'key'    => $apiKey,
            'action' => 'get',
            'id'     => $captchaId,
            'json'   => 1
        ];

        $ch = curl_init($resultUrl . '?' . http_build_query($pollParams));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $pollResponse = curl_exec($ch);
        $curlPollErr = curl_error($ch);
        curl_close($ch);

        if ($curlPollErr) {
            error_log($logPrefix . "cURL polling error: $curlPollErr");
            continue;
        }

        $pollResult = json_decode($pollResponse, true);
        file_put_contents(__DIR__ . '/logs/2captcha_poll_' . $i . '.json', $pollResponse);

        if ($pollResult['status'] == 1 && isset($pollResult['request'])) {
            error_log($logPrefix . "✅ CAPTCHA solved.");
            return $pollResult['request'];
        }

        if (isset($pollResult['request']) && $pollResult['request'] != 'CAPCHA_NOT_READY') {
            error_log($logPrefix . "❌ CAPTCHA error: " . $pollResult['request']);
            return false;
        }

        error_log($logPrefix . "⏳ Poll #$i — CAPTCHA still solving...");
    }

    error_log($logPrefix . "⏰ CAPTCHA solve timed out after polling.");
    return false;
}
