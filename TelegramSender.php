<?php // src/TelegramSender.php

declare(strict_types=1);

readonly class TelegramSender
{
    private string $botToken;

    public function __construct(string $botToken)
    {
        $this->botToken = $botToken;
    }

    public function sendMessage(array $data, string $chatId): bool
    {
        $message = $this->formatMessage($data);

        $params = [
            "chat_id" => $chatId,
            "text" => $message,
            "parse_mode" => "HTML",
        ];
        $ch = curl_init("https://api.telegram.org/bot{$this->botToken}/sendMessage");
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        // TEST
        if (Config::IS_DEV) {
            Utils::test("Проверка отправки в телеграмм", $message, $result['ok']);
        }
        //

        if ($result['ok'] ?? false) {
            return true;
        }

        return false;
    }

    //TODO: проверка на то что юзер уже обращался и отображение?
    private function formatMessage(array $data): string
    {
        $quiz = $this->checkQuiz($data);

        $title = $quiz ? "🎯 Заявка на квиз" : "Новая заявка! 🔥";
        $name = $data['name'] ?? "Не указано";

        $message = "<b>{$title}</b>\n\n" .
            "🌐  <b>Сайт</b>: {$data['url']}\n" .
            "📞  <b>Контакты</b>: {$data['phone']} / {$name}\n\n";

        if ($quiz) {
            $message .= "<b>❓ Квиз:</b>\n";
            foreach ($quiz as $question => $answer) {
                $message .= str_replace("_", " ", $question) . "?\n └ <b>{$answer}</b>\n\n";
            }
        }
        $message .= "<b>🔖 UTM-метки:</b>\n" .
            "├ Source: <b>{$data['utm_source']}</b>\n" .
            "├ Medium: <b>{$data['utm_medium']}</b>\n" .
            "├ Campaign: <b>{$data['utm_campaign']}</b>\n" .
            "└ Content: <b>{$data['utm_content']}</b>\n\n" .
            "📝 <b>Форма</b>: {$data['formid']}\n" .
            "📎 <b>Транзакция</b>: {$data['tranid']}";

        return $message;
    }

    /**
     * Проверяет, содержит ли заявка вопросы квиза (по ключам на кириллице)
     *
     * @param array $data Данные заявки
     * @return array|null Ассоциативный массив вопросов-ответов или null
     */
    private function checkQuiz(array $data): ?array
    {
        return array_filter($data, function ($key) {
            return preg_match('/^[А-Яа-яЁё_]+$/u', $key);
        }, ARRAY_FILTER_USE_KEY) ?? null;
    }


}