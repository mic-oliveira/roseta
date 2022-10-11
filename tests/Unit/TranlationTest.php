<?php

use App\Actions\TranslateJson;
use Spatie\Browsershot\Browsershot;

test('translation from pt to de', function () {
    $translate = new TranslateJson(new Browsershot(), new Symfony\Component\DomCrawler\Crawler());
    $result = $translate->handle(['from' => 'pt-BR',"to" => 'de', 'translate'=> ['test' => "teste"]]);
    expect($result['test'])->toBe('Prüfung');
});
