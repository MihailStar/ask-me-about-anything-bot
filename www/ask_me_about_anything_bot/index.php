<?php

declare(strict_types=1);

/** @var string|false */
$secretToken = getenv("SECRET_TOKEN");
if ($secretToken === false) {
    http_response_code(500);
    exit();
}

/** @var string|false */
$botToken = getenv("BOT_TOKEN");
if ($botToken === false) {
    http_response_code(500);
    exit();
}

/** @var string */
define("API_URL", "https://api.telegram.org/bot" . $botToken);

/** @var string|null */
$incomingSecretToken = $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] ?? null;
if ($incomingSecretToken === null || !hash_equals($secretToken, $incomingSecretToken)) {
    http_response_code(403);
    exit();
}

/** @var string|false */
$rawBody = file_get_contents("php://input");
if ($rawBody === false) {
    http_response_code(400);
    exit();
}

/** @var array|null */
$update = json_decode($rawBody, true);
if ($update === null) {
    http_response_code(400);
    exit();
}

/** @var int */
$chatId = $update["message"]["chat"]["id"];

/** @var string|null */
$chatText = $update["message"]["text"] ?? null;
if ($chatText === null) {
    sendMessage($chatId, "Напишите вопрос - получите ответ");
    exit();
}

switch ($chatText) {
    case "/start":
        sendMessage(
            $chatId,
            "Спросите меня о чем угодно" . PHP_EOL .
                "Напишите вопрос - получите ответ"
        );
        break;

    default:
        sendMessage($chatId, "Вас это еб*ть не должно");
        break;
}

/** @return string|false */
function sendMessage(int $chatId, string $text)
{
    $queryParameters = http_build_query([
        "chat_id" => $chatId,
        "text" => $text
    ]);
    $messageUrl = API_URL . "/sendMessage?" . $queryParameters;

    return file_get_contents($messageUrl);
}

exit();
