<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'secrets.php';
//mb_internal_encoding("UTF-8");
echo "Тестирование lhValidator\n";

define('LH_LIB_ROOT', '/Users/user/MyData/phplib');
require_once LH_LIB_ROOT . '/lhRuNames/classes/lhRuNames.php';

echo "Проверка класса lhEmailValidator\n";
require_once __DIR__ . '/lhValidator/classes/lhEmailValidator.php';

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
            echo $key.'........... ok - ' . (isset($more_info['error_info']) ? $more_info['error_info'] : '') . "\n"; 
        } else {
            echo $key.': MORE_INFO FAIL!!! - ' . $more_info['error_info'] . "\n";
        }
    } else {
        echo $key.':  VALIDATE FAIL!!! - ' . $more_info['error_info'] . "\n";
    }
}

echo "Проверка класса lhNameValidator";
require_once __DIR__ . '/lhValidator/classes/lhNameValidator.php';

$name_validator = new lhNameValidator();

$names = [
    ["Петя", 'true', 'true', "Петр", lhAbstractRuNames::$gender_male, 1],
    ["Марсель", 'true', 'true', "Марсель", lhAbstractRuNames::$gender_male, 1],
    ["Бензин", 'false', 'false', "", null, 0],
    ['василий', 'true', 'true', "Василий", lhAbstractRuNames::$gender_male, 1],
    ["Саша", 'false', 'true', "Александр Александра", null, 2],
    ["Слава", 'false', 'true', "Вячеслав Ростислав Ярослав Слава", null, 4],
    ['Рита', 'false', 'true', "Маргарита Рита", lhAbstractRuNames::$gender_female, 2]
];
foreach ($names as $value) {
    $res = $name_validator->validate($value[0]) ? 'true' : 'false';
    if ( $res != $value[1]) {
        echo "$value[0] result - FAIL!!! Ожидалось \"$value[1]\", получено \"$res\"\n";
        die();
    }
    echo '.';
    $info = $name_validator->moreInfo();
    $is_known = $info['is_known'] ? 'true' : 'false';
    if ( $is_known != $value[2] ) {
        echo "$value[0] is_known - FAIL!!! Ожидалось \"$value[2]\", получено \"$is_known\"\n";
        die();
    }
    echo '.';
    $found = $info['found_names'];
    if ($found != $value[3]) {
        echo "$value[0] found_names - FAIL!!! Ожидалось \"$value[3]\", получено \"$found\"\n";
        die();
    }
    echo '.';
    $gender = $info['gender'];
    if ($gender != $value[4]) {
        echo "$value[0] gender - FAIL!!! Ожидалось \"$value[4]\", получено \"$gender\"\n";
        die();
    }
    echo '.';
    $found = $info['found'];
    if ($found != $value[5]) {
        echo "$value[0] found - FAIL!!! Ожидалось \"$value[5]\", получено \"$found\"\n";
        die();
    }
    echo '.';
}
echo "Ok\n";

echo "Проверка класса lhFullNameValidator";
require_once __DIR__ . '/lhValidator/classes/lhFullNameValidator.php';

$name_validator = new lhFullNameValidator();

$names = [
    ["Петя", 'false'],
    ["Марсель", 'true', "Марсель", lhAbstractRuNames::$gender_male],
    ["Бензин", 'false'],
    ['вася', 'false'],
    ["Саша", 'false'],
    ["Слава", 'true', 'Слава', lhAbstractRuNames::$gender_female],
    ['Рита', 'true', "Рита", lhAbstractRuNames::$gender_female]
];
foreach ($names as $value) {
    $res = $name_validator->validate($value[0]) ? 'true' : 'false';
    if ( $res != $value[1]) {
        echo "$value[0] - FAIL!!! Ожидалось \"$value[1]\", получено \"$res\"\n";
        die();
    }
    echo '.';
    if ($res == 'true') {
        $info = $name_validator->moreInfo();
        $found = $info['full'];
        if ($found != $value[2]) {
            echo "$value[0] - FAIL!!! Ожидалось \"$value[2]\", получено \"$found\"\n";
            die();
        }
        echo '.';
        $gender = $info['gender'];
        if ($gender != $value[3]) {
            echo "$value[0] - FAIL!!! Ожидалось \"$value[3]\", получено \"$gender\"\n";
            die();
        }
        echo '.';
    }
}
echo "Ok\n";


echo "Проверка lhTgBotTokenValidator";
require_once __DIR__ . '/lhValidator/classes/lhTgBotTokenValidator.php';

$tg = new lhTgBotTokenValidator("asdflj345h345hk345hkh345hkjh345kh345");
if ($tg->validate()) {
    echo 'FAIL!!! - Ожидалось "false", получено "true"'."\n";
    die();
}
echo '.';

if ($tg->validate($token)) {
    echo 'FAIL!!! - Ожидалось "true", получено "false"'."\n";
    die();
}
echo '.';
$more_info = $tg->moreInfo();
if ($more_info['first_name'] != 'TestBuddy') {
    echo 'FAIL!!! - Ожидалось "TestBuddy", получено "'.$more_info['first_name'].'"'."\n";
    die();
}
echo '.';
if ($more_info['username'] != 'TestBuddyBot') {
    echo 'FAIL!!! - Ожидалось "TestBuddyBot", получено "'.$more_info['username'].'"'."\n";
    die();
}
echo '.';
echo "Ok\n";


echo "Проверка класса lhPhoneValidator";
require_once __DIR__ . '/lhValidator/classes/lhPhoneValidator.php';

$phone_validator = new lhPhoneValidator();

$strings = [
    [" +79262261868", 'true'],
    ["89262261868   ", 'true'],
    ["9262261868", 'true'],
    ["(926) 226-18-68", 'true'],
    ["+7 () 92622-6-1868", 'false'],
    ["+7 (92622) 6-1868", 'true'],
    ["+7_92622-6-1868", 'false'],
    ["+7 (s231)2-6-1868", 'false'],
];

foreach ($strings as $key => $value) {
    $result = $phone_validator->validate($value[0]) ? 'true' : 'false';
    if ($result != $value[1]) {
        echo "$value[0] - FAIL!!! - Ожидалось \"$value[1]\", получено \"$result\"\n";
        die();
    }
    echo '.';
}

