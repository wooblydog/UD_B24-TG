<?php // src/TelegramSender.php

declare(strict_types=1);

class TelegramSender
{
    private readonly string $botToken;

    public function __construct(string $botToken)
    {
        $this->botToken = $botToken;
    }

    public function sendMessage(string $chatId, array $data): bool
    {
        // Формируем текст сообщения из $data (имя, телефон, etc.)
        $message = $this->formatMessage($data);

        // Формируем URL для TG API
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage?chat_id={$chatId}&text=" . urlencode($message) . "&parse_mode=HTML";

        // Отправка (используем curl или file_get_contents)
        $response = file_get_contents($url); // Или curl для продакшена
        $result = json_decode($response, true);

        if ($result['ok'] ?? false) {
            return true;
        }

        error_log("TG send error: " . ($result['description'] ?? 'Unknown'));
        return false;
    }

    private function formatMessage(array $data): string
    {
        // Пример форматирования: <b>Заявка</b>\nИмя: {$data['name']}\n...
        return 'Formatted message here'; // Реализуй по своим полям
    }
}