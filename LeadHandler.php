<?php
require_once "helpers.php";
class LeadHandler
{
    private TelegramSender $tgSender;
    private Bitrix24 $bitrix;

    public function __construct(TelegramSender $tgSender, Bitrix24 $bitrix)
    {
        $this->tgSender = $tgSender;
        $this->bitrix = $bitrix;
    }

    public function handleRequest(): void
    {
        $data = $this->extractPostData();

        if (empty($data)) {
            Utils::error("POST-запрос не обнаружен");
            return;
        }

        Utils::info($_POST, $_GET);


        if (empty($data['phone']) || empty($data['name'])) {
            error_log('Invalid data');
            return;
        }

        [$chatId, $token] = $this->determineTgChatId($data['url'] ?? '', $data['utm_source'] ?? '', (int)($data['city'] ?? 0));
        if (!$chatId) {
            error_log('No chat ID found');
            return;
        }

        $contact = $this->bitrix->checkContact($data['phone']);
        if ($contact) {
            $data['contact_id'] = $contact['ID'];
        }

        $this->tgSender->sendMessage($chatId, $data);

        $this->bitrix->createLead($data);
    }

    private function extractPostData(): array
    {
        // Парсинг $_POST, фильтрация, trim etc.
        return array_map('trim', $_POST ?? []);
        // Добавь: $data['url'] = ...; $data['utm_source'] = ...; $data['city'] = ...
    }

    private function determineTgChatId(string $url, string $utmSource, int $city): array
    {
        $domain = strtolower(trim($url));
        $source = strtolower(trim($utmSource));

        $chatConfig = Config::getTgChatConfig();
        $rules = $chatConfig[$domain] ?? null;

        $chatId = null;
        if ($rules) {
            $chatId = $rules[$source] ?? $rules['default'] ?? null;
        } else {
            $chatId = Config::getFallbackChatId($city, $source);
        }

        return [$chatId, Config::TG_BOT_TOKEN];
    }
}