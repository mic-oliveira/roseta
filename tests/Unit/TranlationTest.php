<?php

use App\Actions\TranslateJson;

test('tranlation', function () {
    $translate = new TranslateJson(new \Spatie\Browsershot\Browsershot(), new Symfony\Component\DomCrawler\Crawler());
    $result = $translate->handle(['from' => 'pt-BR',"to" => 'de', 'translate'=> ['test' => "teste"]]);
    dump($result);
    expect($result)->toBe('Prüfung');
});
