<?php
$stdout = fopen("php://stdout", "w");
function logTerminal($someString) {
    GLOBAL $stdout;
    fwrite($stdout,$someString . PHP_EOL);
}

require_once "configuration.php";

$reqMethod = $_SERVER['REQUEST_METHOD'];
$action = $_SERVER['REQUEST_URI'];
logTerminal($action . ' ' . $reqMethod);

if ($reqMethod === 'PUT' && $action === "/shipments") {
    $headers = getallheaders();
    $authorization = $headers['authorization'];
    $hostHdr = $headers['Host'];
    $dateHdr = $headers['Date'];
    $digestHdr = $headers['digest'];
    $inputStr = $hostHdr . $dateHdr . $digestHdr;
    logTerminal('inputStr : ' . $inputStr . ' authorization : ' . $authorization);

    $pattern = '/signature="(.*)"/';
    $signature = '';
   if (preg_match($pattern, $authorization, $matches)) {
       $signature = $matches[1];
   }
   logTerminal('signature : ' . $signature);

    $isLegit = isLegitDigitalSignature($signature, $inputStr);
    if ($isLegit == 1) {
        logTerminal("good");
    } elseif ($isLegit == 0) {
        logTerminal("bad");
    } else {
        logTerminal("ugly, error checking signature");
    }

    http_response_code(200);
}

function isLegitDigitalSignature($signature, $inputStr) {
    $publicKeyStr = retrievePublicKey();
    $decodedSignature = base64_decode($signature, false);
    return openssl_verify($inputStr, $decodedSignature, $publicKeyStr, OPENSSL_ALGO_SHA256);
}


fclose($stdout);