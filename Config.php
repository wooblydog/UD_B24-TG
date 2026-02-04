<?php

class Config
{
    public const TG_BOT_TOKEN = [

    ];

    public const B24_DOMAIN = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
    public const B24_ID = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
    public const B24_HASH = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
    public const TELEGRAM_URL = "https://api.telegram.org/bot5678615483:AAF2V925GQpoHhLfTVzApqgcYb4PDlppsk4/sendmessage?chat_id=-889273408&text=";

    public const TOKEN_TYUMEN = '5858304349:AAFfh6hWfDJJa6AtsZNnH5rk-n4MLJ-scfI';
    public const TOKEN_EKB = '5843215983:AAHCpzxVvcfvRMN0Ca7sgy1pQlYj6IMtI7g';

    public const CHAT_TOKEN_MAP = [
        '-1001678881905' => self::TOKEN_TYUMEN,  // Ultradent72 + soffioo
        '-1001577054388' => self::TOKEN_TYUMEN,  // Ultradent72 default
        '-1001912627775' => self::TOKEN_TYUMEN,  // Stomatdent72 + fallback 72
        '-1002098556978' => self::TOKEN_TYUMEN,  // len.ultra-implant66
        '-4033847093'    => self::TOKEN_TYUMEN,  // ultra-lab72
        '-1001856248091' => self::TOKEN_TYUMEN,  // Stomatdent66
        '-1001713413442' => self::TOKEN_TYUMEN,  // Ultradent66 gurmanova

        '-925455936'     => self::TOKEN_EKB,     // Dental + Ultradent66 yandex_k + fallback 66 yandex
        '-1001977992092' => self::TOKEN_EKB,     // Ultradent66 default
        '-1001956618159' => self::TOKEN_EKB,     // fallback 66 обычный
    ];

    public static function getTgChatConfig(): array
    {
        return [
            'ultradent72.ru' => [
                'default' => '-1001577054388',
                'soffioo' => '-1001678881905',
            ],
            'stomatdent72.ru' => [
                'default' => '-1001912627775',
            ],
            'len.ultra-implant66.ru' => [
                'default' => '-1002098556978',
            ],
            'dental66ekb.ru' => [
                'default' => '-925455936',
            ],
            'ultradent66.ru' => [
                'default' => '-1001977992092',
                'gurmanova' => '-1001713413442',
                'yandex_k' => '-925455936',
            ],
            'ultra-lab72.ru' => [
                'default' => '-4033847093',
            ],
            'stomatdent66.ru' => [
                'default' => '-1001856248091',
            ],
        ];
    }

    public static function getFallbackChatId(int $city, string $utmSource): ?string
    {
        $source = strtolower(trim($utmSource));
        if ($city === 72) {
            return '-1001912627775';
        } elseif ($city === 66) {
            return ($source === 'yandex_k') ? '-925455936' : '-1001956618159';
        }
        return null; //МБ добавить потом дефолтный чат для ошибок
    }

    public static function getTgChatIdAndToken(string $url, string $utmSource, int $city): array
    {
        $domain = strtolower(trim($url));
        $source = strtolower(trim($utmSource));

        $chatConfig = self::getTgChatConfig();
        $chatId = $chatConfig[$domain][$source] ?? $chatConfig[$domain]['default'] ?? null;

        if ($chatId === null) {
            $chatId = self::getFallbackChatId($city, $source);
        }

        $token = self::CHAT_TOKEN_MAP[$chatId] ?? null;

        return [$chatId, $token];
    }
}