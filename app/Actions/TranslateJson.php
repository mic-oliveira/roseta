<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class TranslateJson
{
    use AsAction;

    private string $to;

    public function __construct(private Browsershot $browsershot)
    {
    }

    public function handle($translate)
    {
        $translated = [];
        $this->to = $translate['to'];
        foreach ($translate['translate'] as $key => $value) {
            $this->requestTranslation($translate['from'], $translate['to'], $value);
            $translated[$key] = $this->filterHtmlBody($this->browsershot->bodyHtml())[0] ?? $value;
        }
        return $translated;
    }

    private function requestTranslation($from, $to, $word): void
    {
        $this->browsershot
            ->setUrl("https://translate.google.ca/?sl=${from}&tl=${to}&text=${word}&op=translate");
        $this->browsershot->noSandbox();
        $this->browsershot->setExtraNavigationHttpHeaders(["user-agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36" ,
            'referer'=> 'https://www.google.com/']);
        $this->browsershot->waitUntilNetworkIdle();
        $this->browsershot->addChromiumArguments(['headless' => false]);
        $this->browsershot->delay(1000);
    }

    private function filterHtmlBody(string $html) {
        $translateTo = $this->to;
        $crawler = new DomCrawler($html);
        return $crawler->filter('div span[lang="'.$translateTo.'"] span')->each(function(DomCrawler $node) {
            return $node->first()->innerText();
        });
    }
}
