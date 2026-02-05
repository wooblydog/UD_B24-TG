<?php
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
    "formid": "form1814896041"
  }', true);

$_GET = json_decode('{
    "city": "72",
    "url": "Ultradent66.ru/allonfourpter"
  }', true);

$bitrix = new Bitrix24(Config::B24_DOMAIN, Config::B24_ID, Config::B24_HASH);
$handler = new LeadHandler($bitrix);
$handler->handleRequest();