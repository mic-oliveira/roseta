<?php

use App\Actions\ResponseParser;
use App\Actions\TranslateJson;
use App\Actions\TranslateParser;
use Spatie\Browsershot\Browsershot;

test('translation from pt to de', function () {
    $pageContent = file_get_contents(__DIR__ . '/GoogleTradutor.html');
    $mockBrowsershot = Mockery::mock(Browsershot::class)->makePartial();
    $mockBrowsershot->expects('bodyHtml')->andReturn($pageContent);
    $translate = new TranslateJson($mockBrowsershot, new TranslateParser(), new ResponseParser());
    $result = $translate->handle(['from' => 'pt-BR', "to" => 'de', 'translate' => ["help" => "ajuda"]]);
    expect($result['help'])->toBe('Prüfung');
});

test('translation with nested keys', function () {
    $pageContent = file_get_contents(__DIR__ . '/GoogleTradutor.html');
    $mockBrowsershot = Mockery::mock(Browsershot::class)->makePartial();
    $mockBrowsershot->expects('bodyHtml')->andReturn($pageContent);
    $translate = new TranslateJson($mockBrowsershot, new TranslateParser(), new ResponseParser());
    $result = $translate
        ->handle(['from' => 'pt-BR', "to" => 'en', 'translate' => ['test' => ["teste1" => ["test4" => "teste"], "help" => "ajuda"]]]);
    expect($result)->toMatchArray(['test' => ["teste1" => ["test4" => "test"], "help" => "help"]]);
});

test('translation with interpolate text', function () {

});
