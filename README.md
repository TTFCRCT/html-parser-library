# HTML Parser Library

![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)

Библиотека для анализа HTML/XML документов с расширенными возможностями подсчёта тегов и отправки статистики.

🚀 Быстрый старт

Базовый парсинг HTML

```php
use HtmlParserLibrary\Factories\ParserAbstractFactory;
use HtmlParserLibrary\Factories\HtmlParserFactory;
use HtmlParserLibrary\ParserClient;

// Инициализация фабрики
$abstractFactory = new ParserAbstractFactory();

// Регистрируем доступные парсеры
$abstractFactory->registerFactory('html', new HtmlParserFactory());

// Создание парсера
$parser = $abstractFactory->create('html');

// Запуск клиента
$client = new ParserClient($parser);

// Получение результат
$result = $client->parse('<div><p>Hello World</p></div>');
```

🔧 Основные возможности

📊 Подсчет тегов

```php
use HtmlParserLibrary\Counters\SpecificTagCounter;

$client->setTagCounter(
    new SpecificTagCounter(['div', 'p']) // Только эти теги
);
```

📝 Извлечение контента

```php
use HtmlParserLibrary\Extractors\DomContentExtractor;

$client->setContentExtractor(
    new SelectiveDomExtractor(['div', 'p'])
);
```

📡 Интеграции

```php
use HtmlParserLibrary\Services\ExternalServiceSender;

$client->addObserver(
    new ExternalServiceSender('https://api.example.com/logs')
);
```

🛠 Тестирование

```bash
# Запуск всех тестов
phpunit tests
```

🏗 Архитектура

<h2>Компоненты</h2>
<table>
<tr><td>Parser</td><td>Реализации HTML/XML парсеров</td></tr>
<tr><td>Counters</td><td>Фильтрация и подсчет тегов</td></tr>
<tr><td>Extractors</td><td>Извлечение текстового содержимого</td></tr>
<tr><td>Services</td><td>Интеграция с внешними сервисами</td></tr>
</table>
<h2>Шаблоны проектирования</h2>
<table>
<tr><td>Стратегия</td><td>Переключаемые парсеры/экстракторы</td></tr>
<tr><td>Наблюдатель</td><td>Отправка результатов</td></tr>
<tr><td>Декоратор</td><td>Фильтрация тегов</td></tr>
<tr><td>Фабрика</td><td>Создание парсеров</td></tr>
</table>