<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class TranslateJson
{
    use AsAction;

    private string $to;
    const SEPARATOR = '|';

    private array $t = [];

    public function __construct(private Browsershot $browsershot, private TranslateParser $parser, private ResponseParser $json)
    {
    }

    public function handle($translate)
    {
        $this->to = $translate['to'];
        $toTranslate = $this->parser->handle($translate['translate']);

        $this->requestTranslation($translate['from'], $translate['to'], array_values($toTranslate));
        $translated = $this->filterHtmlBody($this->browsershot->bodyHtml());
        return $this->json->handle($translate['translate'], $translated);
    }

    private function requestTranslation($from, $to, $word)
    {

        $s = urlencode(implode(self::SEPARATOR, $word));
        // dd("https://translate.google.ca/?sl=${from}&tl=${to}&text=${s}&op=translate");
        $this->browsershot
            ->setUrl("https://translate.google.ca/?sl=${from}&tl=${to}&text=${s}&op=translate");

        $this->browsershot->noSandbox();
        $this->browsershot->setExtraNavigationHttpHeaders(["user-agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36" ,
            'referer'=> 'https://www.google.com/']);
        $this->browsershot->waitUntilNetworkIdle();
        $this->browsershot->addChromiumArguments(['headless' => false]);
        $this->browsershot->delay(1000);
    }

    private function filterHtmlBody(string $html): string
    {
        $translateTo = $this->to;
        $crawler = new DomCrawler($html);
        return $crawler->filter('div span[lang="'.$translateTo.'"] span')->innerText();
    }


}
