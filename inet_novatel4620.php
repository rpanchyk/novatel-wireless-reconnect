#!/usr/bin/php
<?php

define('CRLF', "\r\n");
date_default_timezone_set('UTC');

echo 'Novatel 4620LE MIFI Modem - Reconnecting';

if (count($argv) != 3) {
  echo CRLF . 'Invalid input params. Must be: "Admin Panel Url" "Admin Password"';
  echo CRLF . 'Given: ';
  print_r($argv);
  exit(1);
}

// settings
define('URL', $argv[1]);
define('PASSWD', $argv[2]);

// initialize and setup curl
$s = curl_init();

// http://php.net/manual/en/function.curl-setopt.php
curl_setopt($s, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0');
curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
curl_setopt($s, CURLOPT_TIMEOUT_MS, 5000);
curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($s, CURLOPT_MAXREDIRS, 10);


// get
echo CRLF . CRLF . '---' . CRLF . 'Get' . CRLF . '---';

curl_setopt($s, CURLOPT_HTTPGET, true);
curl_setopt($s, CURLOPT_URL, URL . '/');
curl_setopt($s, CURLOPT_REFERER, URL . '/');

$httpResponse = curl_exec($s);
$httpStatus = curl_getinfo($s, CURLINFO_HTTP_CODE);
echo CRLF . 'Status: ' . $httpStatus;
// echo CRLF . 'Response: ' . CRLF . $httpResponse;

if ($httpStatus != 200) {
  echo CRLF . 'Get request failed. Exit.' . CRLF;
  exit(1);
}

preg_match('/stoken="(.*?)"/', $httpResponse, $matches); // print_r($matches);
if (count($matches) < 2 || empty($matches[1])) {
  echo 'Cannot get STOKEN. Response: ' . $httpResponse . CRLF;
  exit(1);
}
$stoken = $matches[1];
echo CRLF . 'stoken: ' . $stoken;

preg_match('/pwtoken="(.*?)"/', $httpResponse, $matches); // print_r($matches);
if (count($matches) < 2 || empty($matches[1])) {
  echo 'Cannot get PWTOKEN. Response: ' . $httpResponse . CRLF;
  exit(1);
}
$pwtoken = $matches[1];
echo CRLF . 'pwtoken: ' . $pwtoken;

$pwCrypted = sha1(PASSWD . $pwtoken);
echo CRLF . 'pwCrypted: ' . $pwCrypted;


// login
echo CRLF . CRLF . '-----' . CRLF . 'Login' . CRLF . '-----';
$payload = 'AdPassword=' . $pwCrypted
  . '&stoken=' . $stoken
  . '&currtime=' . time();

curl_setopt($s, CURLOPT_POST, true);
curl_setopt($s, CURLOPT_URL, URL . '/login.cgi');
curl_setopt($s, CURLOPT_REFERER, URL . '/mifi.cgi');
curl_setopt($s, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($s, CURLOPT_POSTFIELDS, $payload);

$httpResponse = curl_exec($s);
$httpStatus = curl_getinfo($s, CURLINFO_HTTP_CODE);
echo CRLF . 'Status: ' . $httpStatus;
// echo CRLF . 'Response: ' . CRLF . $httpResponse;

if ($httpStatus != 204) {
  echo CRLF . 'Login request failed. Exit.' . CRLF;
  exit(1);
}


// disconnect
echo CRLF . CRLF . '----------' . CRLF . 'Disconnect' . CRLF . '----------';
$payload = 'nextfile=200'
  . '&stoken=' . $stoken
  . '&todo=disconnect';

curl_setopt($s, CURLOPT_POST, true);
curl_setopt($s, CURLOPT_URL, URL . '/wwan.cgi');
curl_setopt($s, CURLOPT_REFERER, URL . '/mifi.cgi');
curl_setopt($s, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($s, CURLOPT_POSTFIELDS, $payload);

$httpResponse = curl_exec($s);
$httpStatus = curl_getinfo($s, CURLINFO_HTTP_CODE);
echo CRLF . 'Status: ' . $httpStatus;
// echo CRLF . 'Response: ' . CRLF . $httpResponse;

if ($httpStatus != 204) {
  echo CRLF . 'Disconnect request failed. Exit.' . CRLF;
  exit(1);
}

// wait
// todo: replace this with curl GET and parsing "Disconnected" status on admin panel page
$secs = 10;
echo CRLF . CRLF . 'Waiting ' . $secs . ' seconds to connect...';
sleep($secs);


// connect
echo CRLF . CRLF . '-------' . CRLF . 'Connect' . CRLF . '-------';
$payload = 'nextfile=200'
  . '&stoken=' . $stoken
  . '&todo=connect';

curl_setopt($s, CURLOPT_POST, true);
curl_setopt($s, CURLOPT_URL, URL . '/wwan.cgi');
curl_setopt($s, CURLOPT_REFERER, URL . '/mifi.cgi');
curl_setopt($s, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($s, CURLOPT_POSTFIELDS, $payload);

$httpResponse = curl_exec($s);
$httpStatus = curl_getinfo($s, CURLINFO_HTTP_CODE);
echo CRLF . 'Status: ' . $httpStatus;
// echo CRLF . 'Response: ' . CRLF . $httpResponse;

if ($httpStatus != 204) {
  echo CRLF . 'Connect request failed. Exit.' . CRLF;
  exit(1);
}

curl_close($s);
echo CRLF;
?>
