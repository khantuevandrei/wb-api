# WB API Data Fetcher

Импорт данных из API Wildberries в MySQL.

## Доступ к БД

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

php artisan wb:fetch {dateFrom} {dateTo}

Пример:
php artisan wb:fetch 2026-01-01 2026-06-27
