<?php

declare(strict_types=1);

namespace Arcesilas\BrowserLocale;

class BrowserLocale
{
    /**
     * Locales accepted by the browser
     *
     * @var array
     */
    protected array $accept = [];

    /**
     * Locales available in the application/project
     *
     * @var string[]
     */
    protected array $available = [];

    /**
     * Builds a BrowserLocale instance
     *
     * @param string|null $accept The string of accepted weighted locales
     */
    public function __construct(?string $accept = null)
    {
        $accept ??= $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $this->accept = $this->parseAccept($accept);
    }

    /**
     * Parses the `accept` string
     *
     * @param  string $accept The string of accepted weighted locales
     * @return array          The locales with their respective weights
     */
    protected function parseAccept(string $accept): array
    {
        $weighted = [];

        foreach (explode(',', $accept) as $locale) {
            list($locale, $weight) = $this->parseWeightedLocale($locale);
            $weighted[$locale] = $weight;
        }

        arsort($weighted);

        return $weighted;
    }

    /**
     * Parses a weighted locale, typically something lik `fr;q=0.8`
     *
     * @param  string $locale The locale with its weight
     * @return array          An array [locale, weight]
     */
    protected function parseWeightedLocale(string $locale): ?array
    {
        $pieces = explode(';', $locale);
        $weight = ltrim($pieces[1] ?? '', ';q=');

        return [$pieces[0], (float) $weight ?: 1.0];
    }

    /**
     * Returns the locales accepted by the browser or defined in the input string
     *
     * @return array array
     */
    public function getAccepted(): array
    {
        return $this->accept;
    }

    /**
     * Specifies the locales available in the application/project
     *
     * @param  string $available A list of locales available in the application/project
     * @return self
     */
    public function among(string ...$available): self
    {
        $this->available = $available;
        return $this;
    }

    /**
     * Returns a boolean based on whether the browser accepts the given locale or not
     *
     * @param  string $locale The locale to verify
     * @return bool
     */
    public function accepts(string $locale): bool
    {
        return array_key_exists($locale, $this->accept);
    }

    /**
     * Returns the weight for a locale in the browser's preference
     *
     * @param  string $locale The locale of which to get the weight
     * @return float|null     The weight of the locale
     */
    public function getWeight(string $locale): ?float
    {
        return $this->accept[$locale] ?? null;
    }

    /**
     * Let `BrowserLocale` choose the most appropriate locale based on the browser's `accept ` locales
     *
     * @param  string $default Default locale if none matches the browser's `accept` locales
     * @return string|null     The locale chosen
     */
    public function choose(?string $default = null): ?string
    {
        $intersect = array_intersect(array_keys($this->accept), $this->available);
        return $intersect[0] ?? $default;
    }
}
