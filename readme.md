# Учебный Telegram бот «Спросите меня о чем угодно»

Возвращает ответ. Не собирает и не хранит персональные данные

- [@ask_me_about_anything_bot](https://t.me/ask_me_about_anything_bot)

## Требования

РНР версии 7.2.x или выше

## Запуск

Добавить переменные окружения `BOT_TOKEN` и `SECRET_TOKEN`. Зарегистрировать вебхук

```text
https://api.telegram.org/bot<BOT_TOKEN>/setWebhook?url=<WEBHOOK_URL>&secret_token=<SECRET_TOKEN>
https://api.telegram.org/bot<BOT_TOKEN>/getWebhookInfo
```
