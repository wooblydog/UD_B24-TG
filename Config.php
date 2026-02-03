<?php

class Config
{
    public const TG_BOT_TOKEN = [

    ];

    public const B24_DOMAIN = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
    public const B24_ID = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
    public const B24_HASH = "https://b24-wwasx2.bitrix24.ru/rest/1/qse4csz4cy2awwdz/";
    public const TELEGRAN_URL = "https://api.telegram.org/bot5678615483:AAF2V925GQpoHhLfTVzApqgcYb4PDlppsk4/sendmessage?chat_id=-889273408&text=";

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
        return null;
    }
}