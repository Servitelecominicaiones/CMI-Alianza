<?php

namespace App\Helpers;

class CryptoHelper
{
    public static function Enc($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'F05osefbkF';
        $secret_iv  = 'afHffGh76o05Rg1';

        $key = hash('sha256', $secret_key);
        $iv  = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action === 'enc') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } elseif ($action === 'dec') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
