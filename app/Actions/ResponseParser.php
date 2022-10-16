<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class ResponseParser
{
    use AsAction;

    private array $parsedTranslation = [];

    public function handle($original, $response)
    {
        $this->parseResponse($original, $response, $this->parsedTranslation);
        return $this->parsedTranslation;
    }

    private function parseResponse(array $original, $response, &$parsedArray = []): void
    {
        $translations = explode(TranslateJson::SEPARATOR, $response);
        foreach (array_keys($original) as $index => $key) {
            if (is_array($original[$key])) {
                $this->parseResponse($original[$key], $response, $parsedArray[$key] );
                continue;
            }
            $parsedArray[$key]=$translations[$index];
        }
    }

}
