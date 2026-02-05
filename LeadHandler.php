<?php

class LeadHandler
{
    private Bitrix24 $bitrix;

    public function __construct(Bitrix24 $bitrix)
    {
        $this->bitrix = $bitrix;
    }

    /**
     * Основной обработчик входящего запроса от Tilda.
     * Извлекает данные, отправляет в Telegram и создаёт лид в Bitrix24
     *
     * @return void
     */
    public function handleRequest(): void
    {
        $request = Utils::extractRequestData();

        if (empty($request) || empty($request["phone"])) {
            Utils::error([
                "cause" => "Невалидный запрос",
                "phone" => $request["phone"],
                "requestEmpty" => empty($request),
            ]);
            return;
        }

        Utils::info($_POST, $_GET);
        Utils::note([
            "name" => $request["name"] ?? "Не указано",
            "phone" => $request["phone"],
            "request" => $request["utm_source"] ?? "Не указано"
        ]);

        [$chatId, $botToken] = Config::getTgChatIdAndToken(
            $request["url"] ?? "",
            $request["utm_source"] ?? "",
            (int)($request["city"] ?? 0));

        if (!$chatId) {
            Utils::error([
                "cause" => "ID чата не найден",
                "url" => $request["url"] ?? "",
                "city" => $request["city"] ?? "",]);
            return;
        }


        $TgSender = new TelegramSender($botToken);
        $TgSender = $TgSender->sendMessage($request,$chatId);

        if (!$TgSender) {
            Utils::error([
                "cause" => "Ошибка отправки в телеграмм: ",
                "error" => ($result['description'] ?? 'Неизвестная ошибка')
            ]);
        }

        $fields = $this->prepareLeadFields($request);
        $res = $this->bitrix->addLead($fields);

        if ($res->error) {
            Utils::error([
                "cause" => "Не удалось отправить лид в Битрикс24",
                "phone" => $request["phone"],
                "error" => $res->error,
                "description" => $res->error_description ?? ""
            ]);
        }

        // TEST
        if (Config::IS_DEV) {
            Utils::test("Проверка получение данных реквеста", $request);
            Utils::test("Проверка получения чата и токена", $chatId, $botToken);
        }
        //
    }

    /**
     * Подготавливает поля для создания лида в Bitrix24
     *
     * @param array $request
     * @return array
     */
    private function prepareLeadFields(array $request): array
    {
        $phone = Utils::normalizePhone($request["phone"] ?? "");
        $email = trim($request["email"] ?? "");
        $cityId = Utils::getCityId($request);

        $fields = [
            "TITLE" => "Заявка с сайта " . Utils::normalizeUrl($request["url"]),
            "NAME" => trim($request["name"] ?? "Без имени"),
            "STATUS_ID" => "NEW",
            "OPENED" => "Y",
            "ASSIGNED_BY_ID" => Config::B24_RESPONIBLE_ID,
            "PHONE" => [["VALUE" => $phone, "VALUE_TYPE" => "WORK"]],
            "EMAIL" => filter_var($email, FILTER_VALIDATE_EMAIL)
                ? [["VALUE" => $email, "VALUE_TYPE" => "WORK"]]
                : [],
            'POST' => $request['job'] ?? null,
        ];

        $customFields = [
            "UF_CRM_1698302617" => Utils::normalizeUrl($request["url"] ?? ""),
        ];

        if (!empty($request["city"])) {
            $customFields["UF_CRM_1635751283979"] = $cityId;
        }

        $utmFields = Utils::extractRequestUtm($request) ?? [];
        $customFields = array_merge($customFields, $utmFields);

        return array_merge($fields, $customFields);
    }

}