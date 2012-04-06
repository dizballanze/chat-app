# UWC. Back-end developer 3+

<p>Для работы приложения необходимо установить:</p>

- Apache2+
- MySQL 5.1+
- PHP 5.3+
- Redis 2.2+
- node.js 0.6.14
- Расширения для php:
* phpredis (https://github.com/nicolasff/phpredis)
* PDO MySQL extension
* php-openssl

## Конфигурация:
- Корень apache нужно установить на директорию chat/ и установить AllowOverride All.
- Также необходимо включить модуль rewrite для apache.
- Дамп базы данных находится в chat.sql
- Настройка подключения к БД в /chat/protected/config/main.php
- Настройка подключения к redis для node.js приложения в chat-node/daemon.js
- Выставить права доступа для директорий chat/ и yii/, чтобы веб-сервер мог читать и записывать файлы.

## Запуск:
Для запуска приложения неоходимо запустить chat-node/daemon.js:

	node daemon.js

После этого можно заходить на хост, который указан в настройках apache.
