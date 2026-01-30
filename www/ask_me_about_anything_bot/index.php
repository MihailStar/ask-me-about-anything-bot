<?php

declare(strict_types=1);

/** @var string|false */
$botToken = getenv("BOT_TOKEN");
if ($botToken === false) {
    http_response_code(500);
    exit(1);
}

/** @var string */
define("API_URL", "api.telegram.org/bot" . $botToken);

/** @var string|false */
$secretToken = getenv("SECRET_TOKEN");
if ($secretToken === false) {
    http_response_code(500);
    exit(1);
}

/** @var string|null */
$incomingSecretToken = $_SERVER["HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN"] ?? null;
if ($incomingSecretToken === null || !hash_equals($secretToken, $incomingSecretToken)) {
    http_response_code(403);
    exit();
}

$rawBody = file_get_contents("php://input");
if ($rawBody === false) {
    http_response_code(400);
    exit();
}

/**
 * @var array{message?:array{chat:array{id:int},text?:string}}|null
 * @tutorial https://core.telegram.org/bots/api#update
 */
$update = json_decode($rawBody, true);
if ($update === null) {
    http_response_code(400);
    exit();
}

$chatId = $update["message"]["chat"]["id"];

$chatText = $update["message"]["text"] ?? null;
if ($chatText === null) {
    sendMessage($chatId, "Напишите вопрос - получите ответ");
    exit();
}

if ($chatText === "/start") {
    sendMessage(
        $chatId,
        "Спросите меня о чем угодно" . PHP_EOL .
            "Напишите вопрос - получите ответ"
    );
} else {
    sendMessage($chatId, "Вас это еб*ть не должно");
}

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
