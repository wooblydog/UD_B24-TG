<?php

ini_set('display_errors', 1);

require_once(__DIR__ . '/config/n8ZSdaZE43Glu0ZkMeNVlOXwvOFRm5tc.php');
require_once(__DIR__ . '/config/ErrorHandler.php');
require_once(__DIR__ . '/helpers.php');

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/TelegramSender.php';
require_once __DIR__ . '/Bitrix24.php';
require_once __DIR__ . '/LeadHandler.php';
require_once __DIR__ . '/Utils.php';

$_POST = json_decode('{
    "Какую_челюсть_нужно_восстановить": "Верхнюю",
    "Нужно_удалять_зубы": "Зубов нет",
    "Как_давно_у_Вас_нет_зубов": "Зубы всё ещё есть",
    "Как_скоро_планируете_начать_лечение": "В ближайшее время",
    "Куда_Вам_отправить_расчёт_стоимости_по_имплантации_зубов": "Telegram",
    "Phone": "+7 (999) 999-99-99",
    "tranid": "3055481:8140818822",
    "COOKIES": "__ddg9_=195.133.94.65; _ym_uid=1770146617335078468; _ym_d=1770146617; _ym_isad=1; _ym_visorc=w; _ct_ids=jqw9m221%3A55370%3A713910897; _ct_session_id=713910897; _ct_site_id=55370; _ct=2200000000464647719; _ct_client_global_id=e0c2d5ea-5583-5bc1-afc8-8407c1b21a58; cted=modId%3Djqw9m221%3Bya_client_id%3D1770146617335078468; tildauid=1770146618839.510808; tildasid=1770146618839.927107; __ddg8_=7SWlYG6a6S58EyIZ; __ddg10_=1770146876; call_s=___jqw9m221.1770148835.713910897.292272:1279137|2___; previousUrl=stomatdent72.ru%2Ftilda%2Fform1823303801%2Fsubmitted%2F",
    "formid": "form1814896041",
    "utm_source": "yandex_ads_danilekb",
    "utm_medium": "rcy",
    "utm_campaign": "All_on_4",
    "utm_content": "89000allon4",
    "utm_term": "---autotargeting.com.snake.evolution.eating.game"
  }', true);

$_GET = json_decode('{
    "city": "72",
    "url": "stomatdent72.ru/allonfourpter"
  }', true);

$bitrix = new Bitrix24(Config::B24_DOMAIN, Config::B24_ID, Config::B24_HASH);
$handler = new LeadHandler($bitrix);
$handler->handleRequest();


//if (empty($_POST)) {
//    file_put_contents('errors.log', "ERROR" . date('d-m-Y H:i:s') . " || POST-запрос не обнаружен\n", FILE_APPEND);
//    die;
//}

//file_put_contents('log.log', date('d-m-Y H:i:s') . json_encode($_POST, JSON_UNESCAPED_UNICODE) . PHP_EOL . json_encode($_GET, JSON_UNESCAPED_UNICODE) . PHP_EOL . PHP_EOL, FILE_APPEND);
// index.php

echo 11;
die();

$request = array_change_key_case($_POST);

//file_put_contents('text.txt', $request['name'] . " " . $request['phone'] . " " . $request['utm_source'] . "\n", FILE_APPEND);
//file_put_contents('text.txt', "\n", FILE_APPEND);

$name = (!empty($request['name'])) ? $request['name'] : '';
$phone = (!empty($request['phone'])) ? $request['phone'] : '';
$email = (!empty($request['email'])) ? $request['email'] : '';
//$job = (!empty($request['job'])) ? $request['job'] : '';
$utm_source = (!empty($request['utm_source'])) ? $request['utm_source'] : '';
$utm_medium = (!empty($request['utm_medium'])) ? $request['utm_medium'] : '';
$utm_campaign = (!empty($request['utm_campaign'])) ? $request['utm_campaign'] : '';
$utm_content = (!empty($request['utm_content'])) ? $request['utm_content'] : '';
$utm_term = (!empty($request['utm_term'])) ? $request['utm_term'] : '';
$formid = (!empty($request['formid'])) ? $request['formid'] : '';
$tranid = (!empty($request['tranid'])) ? $request['tranid'] : '';
$url = (!empty($_GET['url'])) ? $_GET['url'] : '';
$city = (!empty($_GET['city'])) ? $_GET['city'] : '';
//$page = (!empty($request['page'])) ? $request['page'] : '';
$quiz1 = (!empty($request['1__Сколько_зубов_вам_нужно_восстановить'])) ? $request['1__Сколько_зубов_вам_нужно_восстановить'] : '';
$quiz2 = (!empty($request['2__Как_давно_у_вас_отсутствуют_зубы'])) ? $request['2__Как_давно_у_вас_отсутствуют_зубы'] : '';
$quiz3 = (!empty($request['3__Есть_ли_у_вас_хронические_заболевания'])) ? $request['3__Есть_ли_у_вас_хронические_заболевания'] : '';
$quiz4 = (!empty($request['4__Когда_планируете_начать_лечение'])) ? $request['4__Когда_планируете_начать_лечение'] : '';
if ($city == 72) $cityId = 51;
else if ($city == 66) $cityId = 53;

$token = '';
$chat_id = '';

if ($url == "Ultradent72.ru") {
    if ($utm_source == 'soffioo') {
        $chat_id = "-1001678881905";
        $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
    } else {
        $chat_id = "-1001577054388";
        $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
    }
}
elseif ($url == "Stomatdent72.ru") {
    $chat_id = "-1001912627775";
    $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
}
elseif ($url == "len.ultra-implant66.ru") {
    $chat_id = "-1002098556978";
    $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
}
elseif ($url == "Dental66ekb.ru" || ($url == "Ultradent66.ru" && $utm_source == 'yandex_k')) {
    $chat_id = "-925455936";
    $token = "5843215983:AAHCpzxVvcfvRMN0Ca7sgy1pQlYj6IMtI7g";
}
elseif ($url == "Ultradent66.ru") {
    if ($utm_source == 'gurmanova') {
        $chat_id = "-1001713413442";
        $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
    }
    else {
        $chat_id = "-1001977992092";
        $token = "5843215983:AAHCpzxVvcfvRMN0Ca7sgy1pQlYj6IMtI7g";
    }
}
elseif ($url == "ultra-lab72.ru") {
    $chat_id = "-4033847093";
    $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
}
elseif ($url == "Stomatdent66.ru") {
    $chat_id = "-1001856248091";
    $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
}
else {
    if ($city == 72) {
        $token = "5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI";
        $chat_id = "-1001912627775";
    } elseif ($city == 66) {
        if ($utm_source == "yandex_k") {
            $chat_id = "-925455936";
            $token = "5843215983:AAHCpzxVvcfvRMN0Ca7sgy1pQlYj6IMtI7g";
        } else {
            $token = "5843215983:AAHCpzxVvcfvRMN0Ca7sgy1pQlYj6IMtI7g";
            $chat_id = "-1001956618159";
        }
    }
}


//tg message
$nextLine = '%0A';
$title = "Заявка с сайта " . $url;
$message = $title . $nextLine;
if ($name != '') $message = $message . "Имя: " . $name . $nextLine;
if ($phone != '') $message = $message . "Телефон: " . $phone . $nextLine;
if ($email != '') $message = $message . "Email: " . $email . $nextLine;
//if ($job != '') $message = "Должность: " . $job . $nextLine;
if ($url != '') $message = $message . "url: " . $url . $nextLine;
if ($utm_source != '') $message = $message . "utm-source: " . $utm_source . $nextLine;
if ($utm_medium != '') $message = $message . "utm-medium: " . $utm_medium . $nextLine;
if ($utm_campaign != '') $message = $message . "utm-campaign: " . $utm_campaign . $nextLine;
if ($utm_content != '') $message = $message . "utm-content: " . $utm_content . $nextLine;
if ($utm_term != '') $message = $message . "utm-term: " . $utm_term . $nextLine;
if ($formid != '') $message = $message . "formid: " . $formid . $nextLine;
if ($tranid != '') $message = $message . "tranid: " . $tranid . $nextLine;
//if ($page != '') $message = $message . "page: " . $page . $nextLine;
if ($quiz1 != '') $message = $message . "Сколько зубов вам нужно восстановить: " . $quiz1 . $nextLine;
if ($quiz2 != '') $message = $message . "Как давно у вас отсутствуют зубы: " . $quiz2 . $nextLine;
if ($quiz3 != '') $message = $message . "Есть ли у вас хронические заболевания: " . $quiz3 . $nextLine;
if ($quiz4 != '') $message = $message . "Когда планируете начать лечение: " . $quiz4 . $nextLine;

$urlTg = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chat_id&text={$message}";
fopen($urlTg, 'r');

$data = http_build_query([
    "fields" => [
        "TITLE" => $title,
        "PHONE" => [
            [
                "TYPE_VALUE" => "WORK",
                "VALUE" => $phone
            ]
        ],
        "NAME" => $name,
        "UTM_SOURCE" => $utm_source,
        "UTM_MEDIUM" => $utm_medium,
        "UTM_CAMPAIGN" => $utm_campaign,
        "UTM_CONTENT" => $utm_content,
        "UTM_TERM" => $utm_term,
        "UF_CRM_1635751283979" => $cityId,
        "UF_CRM_1698302617" => $url,
        'POST' => $job
    ]
]);

sendBitrix($urlBitrix . "crm.lead.add.json", $data);