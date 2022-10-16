<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class TranslateParser
{
    use AsAction;

    private array $parsedArray;

    public function handle(array $translation): array
    {
        return $this->parseTranslation($translation);
    }

    private function parseTranslation(array $translation): array
    {
        foreach ($translation as $value) {
            if (is_array($value)) {
                $this->parseTranslation($value);
                continue;
            }
            $this->parsedArray[] = $value ?? '';
        }
        return $this->parsedArray;
    }

}
