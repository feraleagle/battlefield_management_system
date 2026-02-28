<?php
// Ensure UTF-8 encoding
mb_internal_encoding("UTF-8");

// Given data
$alph = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-+=[];',./?><}:\"\\|{`~0123456789 ";
$encrypt = "€₹₩₭₮₯₰₱₲₳₴₵₶₷Åℬℭ℮ℯℰℱℳℴℵℶℷℸℹ℺℻ℼℽℾℿ⅁⅂⅃⅄ⅅⅆⅇⅈⅉ⅊⅋⅍ⅎ⅏⅐⅑⅒⅓⅔⅕⅖⅗⅘⅙⅚⅛⅜⅝⅞⅟ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩⅪⅫⅬⅭⅮⅯⅰⅱⅲⅳⅴⅵⅶⅷⅸⅹⅺⅻⅼⅽⅾⅿ";

// Encryption
function encryptMessage($message) {
    global $alph;
    global $encrypt;
    $encryptedMessage = "";
    for ($i = 0; $i < mb_strlen($message, 'UTF-8'); $i++) {
        $char = mb_substr($message, $i, 1, 'UTF-8');
        $index = mb_strpos($alph, $char, 0, 'UTF-8');
        if ($index !== false) {
            $encryptedMessage .= mb_substr($encrypt, $index, 1, 'UTF-8');
        } else {
            // Handle characters not in $alph
            $encryptedMessage .= $char;
        }
    }
    return $encryptedMessage;
}

// Decryption
function decryptMessage($encryptedMessage) {
    global $alph;
    global $encrypt;
    $decryptedMessage = "";
    for ($i = 0; $i < mb_strlen($encryptedMessage, 'UTF-8'); $i++) {
        $char = mb_substr($encryptedMessage, $i, 1, 'UTF-8');
        $index = mb_strpos($encrypt, $char, 0, 'UTF-8');
        if ($index !== false) {
            $decryptedMessage .= mb_substr($alph, $index, 1, 'UTF-8');
        } else {
            // Handle characters not in $encrypt
            $decryptedMessage .= $char;
        }
    }
    return $decryptedMessage;
}