<?php
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/TelegramSender.php';
require_once __DIR__ . '/Bitrix24.php';
require_once __DIR__ . '/LeadHandler.php';
require_once __DIR__ . '/Utils.php';

$bitrix = new Bitrix24(Config::B24_DOMAIN, Config::B24_ID, Config::B24_HASH);
$handler = new LeadHandler($bitrix);
$handler->handleRequest();