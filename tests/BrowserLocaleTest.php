<?php

declare(strict_types=1);

namespace Arcesilas\BrowserLocale\Tests;

use Arcesilas\BrowserLocale\BrowserLocale;
use PHPUnit\Framework\TestCase;

class BrowserLocaleTest extends TestCase
{
    protected $accept = 'fr,fr-FR;q=0.8,en;q=0.3,en-US;q=0.5';

    protected $sorted = [
        'fr'    => 1.0,
        'fr-FR' => 0.8,
        'en-US' => 0.5,
        'en'    => 0.3
    ];

    protected $bl;

    public function setUp(): void
    {
        $this->bl = new BrowserLocale($this->accept);
    }

    public function testItAcceptsBrowserLanguage()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = $this->accept;
        $bl = new BrowserLocale();
        $this->assertSame($this->sorted, $bl->getAccepted());
    }

    public function testItAcceptsArbitraryLanguages()
    {
        $this->assertSame($this->sorted, $this->bl->getAccepted());
    }

    public function testItAcceptsEn()
    {
        $this->assertTrue($this->bl->accepts('en'));
    }

    public function testItDoesNotAcceptDe()
    {
        $this->assertFalse($this->bl->accepts('de'));
    }

    public function testItReturnsAcceptedLanguageWeight()
    {
        $this->assertSame(0.3, $this->bl->getWeight('en'));
    }

    public function testItReturnsNullWeightWhenLanguageIsNotAccepted()
    {
        $this->assertNull($this->bl->getWeight('de'));
    }

    public function testItDetectsBestLocale()
    {
        $this->bl->among('en', 'fr');
        $this->assertSame('fr', $this->bl->choose());
    }

    public function testItFailsToDetectBestLocale()
    {
        $this->bl->among('de', 'es');
        $this->assertNull($this->bl->choose());
    }

    public function testItDefaultsToGivenLocale()
    {
        $this->bl->among('de', 'es');
        $this->assertSame('es', $this->bl->choose('es'));
    }
}
