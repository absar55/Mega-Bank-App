<?php
    function aes($text, $mode){
        $result = '';
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'b1fffbb060bc2e119e8b2d8e3abaf5e6424491t543d36a7bcacab267bo2a54e4';
        $secret_iv = 'Our security is flawless';
        $key = hash('sha256', $secret_key);
        $option = 0;
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($mode == 'encrypt0172') {   // ENCRYPTION
            $result = openssl_encrypt($text, $encrypt_method, $key, $option, $iv);
            $result = base64_encode($result);
        }
        else if($mode == 'decrypt0172') {   // DECRYPTION
            $result = openssl_decrypt(base64_decode($text), $encrypt_method, $key, $option, $iv);
        }
        return $result;
    }
?>