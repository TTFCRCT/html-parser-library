<?php

use HtmlParserLibrary\ParserClient;
use HtmlParserLibrary\Counters\SpecificTagCounter;
use HtmlParserLibrary\Counters\TagCounter;
use HtmlParserLibrary\Services\ExternalServiceSender;
use HtmlParserLibrary\Factories\ParserAbstractFactory;
use HtmlParserLibrary\Factories\HtmlParserFactory;
use HtmlParserLibrary\Factories\XmlParserFactory;
use HtmlParserLibrary\Extractors\SelectiveDomExtractor;

require_once __DIR__ . '/vendor/autoload.php';

// Пример HTML для парсинга
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Тестовая страница</title>
    <meta charset="UTF-8">
</head>
<body>
    <div class="container">
        <h1>Welcome!</h1>
        <p>Раз раз раз.. два три</p>
        <div class="content">
            <p>Параграф</p>
            <p>Параграф</p>
            <a href="/">Ссылка</a>
        </div>
    </div>  
</body>
</html>
HTML;

$xml = '<?xml version="1.0"?><root><item>Test</item></root>';

try {
    // Инициализация фабрик
    $abstractFactory = new ParserAbstractFactory();

    // Регистрируем доступные парсеры
    $abstractFactory->registerFactory('html', new HtmlParserFactory());
    $abstractFactory->registerFactory('xml', new XmlParserFactory());

    // Создание парсера
    $parser = $abstractFactory->create('html');
    $client = new ParserClient($parser);

    // Счетчик для всех тегов
    $tagCounter = new TagCounter();
    // Счетчик для конкретных тегов
    $tagCounter = new SpecificTagCounter(['div', 'p']);

    $client->setTagCounter($tagCounter);

    $extractor = new SelectiveDomExtractor(['p']);
    $client->setContentExtractor($extractor);

    // Для XML
    $extractorXML = new SelectiveDomExtractor(['root'], true);

    // Внешний сервис для отправки результатов
    $serviceSender = new ExternalServiceSender('http://fake-url.test');
    $client->addObserver($serviceSender);

    // Результат
    $result = $client->parse($html);

    echo "<h2>Результаты подсчета тегов: <span style='color: yellow; background-color: green; padding: 3px;'>\$result['tags']</span></h2>";
    echo "<pre style='padding: 20px; color: white; background-color: black;'>" . print_r($result['tags'], true) . "</pre>";


    echo "<h2>Контент этих тегов: <span style='color: yellow; background-color: green; padding: 3px;'>\$result['content']</span></h2>";
    echo "<pre style='padding: 20px; color: white; background-color: black;'>" . print_r($result['content'], true) . "</pre>";

} catch (Exception $e) {
    echo "<div style='color: yellow; background-color: red; padding: 3px;'>Ошибка: " . $e->getMessage() . "</div>";
}