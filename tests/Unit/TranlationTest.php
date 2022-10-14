<?php

use App\Actions\TranslateJson;
use Spatie\Browsershot\Browsershot;

test('translation from pt to de', function () {
    $pageContent = file_get_contents(__DIR__ . '/GoogleTradutor.html');
    $mockBrowsershot = Mockery::mock(Browsershot::class)->makePartial();
    $mockBrowsershot->expects('bodyHtml')->andReturn($pageContent);
    $translate = new TranslateJson($mockBrowsershot);
    $result = $translate->handle(['from' => 'pt-BR', "to" => 'de', 'translate' => ['test' => "teste"]]);
    dump($result);
    expect($result['test'])->toBe('Prüfung');
});
