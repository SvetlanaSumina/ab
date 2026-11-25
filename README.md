# Блог

Простой блог на чистом PHP без фреймворков. Шаблоны отдаёт Smarty, данные лежат в MySQL.

## Стек

- PHP 8.6 (FPM) и PDO
- Smarty 5.9
- MySQL 8.4
- Nginx 1.30
- SCSS, сборка через Sass в контейнере Node
- Docker Compose

## Страницы

- `/` — категории, в которых есть хотя бы одна статья. В каждой показаны три последних поста по дате публикации и кнопка «Все статьи».
- `/category/{id}` — название и описание категории, список статей, сортировка и пагинация. Сортировка: по дате публикации (`?sort=date`) и по количеству просмотров (`?sort=views`). На странице 6 статей.
- `/article/{id}` — изображение, название, описание, текст, категории, дата публикации и число просмотров. Ниже блок из трёх похожих статей.
- Неизвестный адрес и отсутствующая запись открывают страницу 404.

Похожие статьи — это другие посты с общими категориями. Сначала идут те, у которых совпадений больше, затем более новые. При каждом открытии статьи счётчик просмотров увеличивается на один.

У категории в базе есть название и описание. У статьи есть изображение, название, описание, текст, одна или несколько категорий и счётчик просмотров.

## Запуск

Нужен Docker.

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec -T mysql mysql -h127.0.0.1 -uroot -proot blog < migrations/001_init.sql
docker compose exec php composer install --no-interaction
docker compose exec php php seeds/seed.php
docker compose --profile styles up -d node
```

Сайт: http://localhost:8080

Примеры адресов после сидинга:

- http://localhost:8080/
- http://localhost:8080/category/1
- http://localhost:8080/article/1

MySQL с хоста доступен на порту 3307. Внутри сети контейнеров хост по-прежнему `mysql`, порт `3306`.

Сидер создаёт 5 категорий и 30 статей, затем копирует обложки из `seeds/images` в `public/uploads`. Повторный запуск очищает эти таблицы и заполняет их заново:

```bash
docker compose exec php php seeds/seed.php
```

`public/css/` и копии картинок в `public/uploads/` в git не хранятся: CSS собирается из `scss/`, картинки появляются после сидинга. Контейнер `node` следит за SCSS и пересобирает `public/css/main.css`.

## Структура

```text
├── config/config.php
├── docker/
│   ├── nginx/default.conf
│   ├── php/Dockerfile
│   └── node/Dockerfile
├── migrations/001_init.sql
├── public/
│   ├── index.php
│   ├── css/                  # собирается из scss
│   └── uploads/              # заполняет сидер
├── scss/
├── seeds/
│   ├── seed.php
│   ├── data/
│   └── images/
├── src/
│   ├── bootstrap.php
│   ├── Core/
│   ├── Repository/
│   └── Controller/
├── templates/
├── docker-compose.yml
├── .env.example
└── composer.json
```
