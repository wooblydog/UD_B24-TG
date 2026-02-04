<?php

class LeadHandler
{
    private Bitrix24 $bitrix;

    public function __construct(Bitrix24 $bitrix)
    {
        $this->bitrix = $bitrix;
    }

    public function handleRequest(): void
    {
        $request = $this->extractRequestData();

        if (empty($request) || empty($request["phone"])) {
            Utils::error("Невалидный запрос");
            return;
        }

        Utils::info($_POST, $_GET);
        Utils::note(
            $request['name'] ?? 'Не указано',
            $request['phone'] ?? 'Не указано',
            $request['utm_source'] ?? 'Не указано'
        );

        [$chatId, $token] = $this->determineTgChatId(
            $request['url'] ?? '',
            $request['utm_source'] ?? '',
            (int)($request['city'] ?? 0));

        if (!$chatId) {
            Utils::error('ID чата не найден');
            return;
        }
        //TODO: Сверка контактов и отправка в битрикс
//        $contact = $this->bitrix->checkContact($request['phone']);
//
//        if ($contact) {
//            $request['contact_id'] = $contact['ID'];
//        }
//        ((new TelegramSender($token))->sendMessage($request,$chatId ));
        ((new TelegramSender("7055483414:AAE6Ck2F8fRBZ0baNEXuhg677fjwlY0S7ME"))->sendMessage($request,"-1002115190876" ));
//        $this->bitrix->createLead($request);
    }

    private function extractRequestData(): array
    {
        return array_merge(array_change_key_case($_POST ?? null), array_change_key_case($_GET ?? null));
    }

    private function determineTgChatId(string $url, string $utmSource, int $city): array
    {
        return Config::getTgChatIdAndToken($url, $utmSource, $city);
    }
}