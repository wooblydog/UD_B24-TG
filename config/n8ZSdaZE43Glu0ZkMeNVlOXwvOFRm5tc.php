<?php

// Запрос для робота в Битрикс
// http://api.tech-ud72.ru/bitrix-api/callcentre/index.php?phone={{Рабочий телефон}}&city={{ГОРОД1 (текст)}}&name={{Имя}} {{Фамилия}} {{Отчество}}

$cities = [
    'Тюмень' => 51,
    'Екатеринбург' => 53
];

$clinics = [
    'Челюскинцев' => 101,
    'Республика' => 103,
    'Серова' => 105,
    'Добролюбова' => 107,
];

// URL-адресы
$urlBitrix = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
$urlSenderBitrix = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
$urlTg = "https://api.telegram.org/bot5678615483:AAF2V925GQpoHhLfTVzApqgcYb4PDlppsk4/sendmessage?chat_id=-889273408&text=";

// Запросы в битрикс
$checkedContactRequest = "crm.duplicate.findbycomm.json";
$addContactRequest = "crm.contact.add.json";
$addDealRequest = "crm.deal.add.json";
$getVoxList = "voximplant.statistic.get.json";
$getFile = "disk.file.get.json";
$postFile = "crm.timeline.comment.add.json";

// Функции
function createPhonePatterns($phone) {
    $phonePattern1 = $phone;
    $phonePattern2 = "";
    $phonePattern3 = "";
    if (strpos($phonePattern1, '+7')) {
        $phonePattern2 = substr_replace($phonePattern1, '7', 0, 2);
        $phonePattern3 = substr_replace($phonePattern1, '8', 0, 2);
    } elseif (strpos($phonePattern1, '7') === 0) {
        $phonePattern2 = substr_replace($phonePattern1, '+7', 0, 1);
        $phonePattern3 = substr_replace($phonePattern1, '8', 0, 1);
    } elseif (strpos($phonePattern1, '8') === 0) {
        $phonePattern2 = substr_replace($phonePattern1, '+7', 0, 1);
        $phonePattern3 = substr_replace($phonePattern1, '7', 0, 1);
    }
    return [$phonePattern1, $phonePattern2, $phonePattern3];
}

function checkingContact($phone, $url) {
    global $urlTg;
    $phonePatternsArr = createPhonePatterns($phone);
    $paramLid = http_build_query(array(
        'entity_type' => "CONTACT",
        'type' => "PHONE",
        'values' => [$phonePatternsArr[0], $phonePatternsArr[1], $phonePatternsArr[2]],
    ));

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => $paramLid,
    ));
    $checkResult = curl_exec($ch);
    curl_close($ch);
    $checkResult = json_decode($checkResult, true);

    $contactId = (isset($checkResult['result']['CONTACT']) && isset($checkResult['result']['CONTACT'][0])) ? $contactId = $checkResult['result']['CONTACT'][0] : null;
    if (isset($checkResult['error'])) {
        file_put_contents('errors.log', "ERROR || " . date("d-m-Y H:i:s") . " || Ошибка отправки в битрикс || " . implode(' ', $checkResult) . " || " . $url . "\n", FILE_APPEND);
    }
    return $contactId;
}

function sendBitrix($url, $dataArr) {
    global $urlTg;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => $dataArr,
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    if (isset(json_decode($result, true)['error'])) {
        file_put_contents('errors.log', "ERROR || " . date("d-m-Y H:i:s") . " || Ошибка отправки в битрикс || " . implode(' ', json_decode($result, true)) . " || " . $url . "\n", FILE_APPEND);
    }
    return $result;
}

function sendTelegram($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    if (isset(json_decode($result, true)['error'])) {
        file_put_contents('errors.log', "ERROR || " . date("d-m-Y H:i:s") . " || Ошибка дублирования сделки в телеграм || " . json_decode($result, true) . " || " . $url . "\n", FILE_APPEND);
    }

    return $result;
}