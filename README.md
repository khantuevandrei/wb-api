# WB API Data Fetcher

Импорт данных из API Wildberries в MySQL.

## Доступ к БД

- url: http://www.phpmyadmin.co/
- Host: sql7.freesqldatabase.com
- Port: 3306
- Database: sql7831696
- Username: sql7831696
- Password: pMkRZnCcgc

## Таблицы

- sales — продажи
- orders — заказы
- stocks — склады
- incomes — доходы

## Запуск

php artisan wb:fetch {type} {dateFrom} {dateTo}

Пример:
php artisan wb:fetch sales 2026-06-26
php artisan wb:fetch orders 2026-06-26
php artisan wb:fetch stocks 2026-06-27
php artisan wb:fetch incomes 2026-03-20

## Почему не все таблицы за одну дату

- **stocks** — API отдаёт данные только за текущий день
- **incomes** — данные доступны не за все даты (последние найденные — 20 марта 2026)
- **sales и orders** — содержат данные за 26 июня 2026

Поэтому загрузка выполнена раздельно:

- sales, orders — 26 июня 2026
- stocks — 27 июня 2026
- incomes — 20 марта 2026

Каждая таблица содержит по 100 записей (для соблюдения лимитов бесплатного хостинга).
