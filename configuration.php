<?php
function retrievePublicKey() {
    $fp = fopen("/home/jason/software/temp/public_key_rsa_4096_pkcs8-exported.pem", "r");
    $publicKey = fread($fp, 4096);
    fclose($fp);

    return $publicKey;
}