<?php

namespace App\Helper;

class DateHelper
{
    /**
     * Usuwa polskie znaki diakrytyczne, eliminuje spacje i konwertuje tekst na małe litery.
     * Używane głównie do przygotowania ciągów znaków do zastosowań, gdzie wymagana jest jednolita reprezentacja.
     * @param string $string Wejściowy ciąg znaków do przetworzenia.
     * @return string Przetworzony ciąg znaków.
     */
    public static function normalizeString(string $string): string
    {
        $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $normalized     = strtolower(str_replace(' ', '', $transliterated));

        return $normalized;
    }
}
