<?php
function retrievePublicKey() {
    $fp = fopen("/home/webmanager/temp/push-pro-api-public-key.pem", "r");
    $publicKey = fread($fp, 4096);
    fclose($fp);

    return $publicKey;
}