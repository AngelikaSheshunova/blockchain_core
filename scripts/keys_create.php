<?php

$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA
);

$res = openssl_pkey_new($config);

openssl_pkey_export($res, $privKey);

$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];


file_put_contents("../../www/demo-verifier.vchain/vchain-public.key", $pubKey);

file_put_contents("../../www/demo-verifier.vchain/vchain-private.key", $privKey);

?>
