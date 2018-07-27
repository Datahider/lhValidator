<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//mb_internal_encoding("UTF-8");
echo "Тестирование lhValidator\n";

define('LH_LIB_ROOT', './');

echo "Проверка класса lhEmailValidator\n";
require_once LH_LIB_ROOT . 'lhValidator/classes/lhEmailValidator.php';

$email_validator = new lhEmailValidator();

$strings = [
    ["  alpha@yandex.ru", true, 'alpha', 'yandex.ru'],
    ["123@losthost.online   ", true, '123', 'losthost.online'],
    ["-minus@losthost.online", false, '', 'losthost.online'],
    ["wrong-@losthost.online", false, '', 'losthost.online'],
    ["good@wrong.domain", false, 'good', '']
];

foreach ($strings as $key => $value) {
    if ($email_validator->validate($value[0]) == $value[1]) {
        $more_info = $email_validator->moreInfo();
        if ( ($more_info['user'] == $value[2]) && ($more_info['domain'] == $value[3]) ) {
            echo $key.'........... ok - ' . $more_info['error_info'] . "\n"; 
        } else {
            echo $key.': MORE_INFO FAIL!!! - ' . $more_info['error_info'] . "\n";
        }
    } else {
        echo $key.':  VALIDATE FAIL!!! - ' . $more_info['error_info'] . "\n";
    }
}


echo "Проверка класса lhPhoneValidator\n";
require_once LH_LIB_ROOT . 'lhValidator/classes/lhPhoneValidator.php';

$phone_validator = new lhPhoneValidator();

$strings = [
    [" +79262261868", true],
    ["89262261868   ", true],
    ["9262261868", true],
    ["(926) 226-18-68", true],
    ["+7 () 92622-6-1868", false],
    ["+7 (92622) 6-1868", true],
    ["+7_92622-6-1868", false],
    ["+7 (s231)2-6-1868", false],
];

foreach ($strings as $key => $value) {
    if ($phone_validator->validate($value[0]) == $value[1]) {
        echo $key.'........... ok' . "\n"; 
    } else {
        echo $key.': ######### FAIL!!!' . "\n";
    }
}
