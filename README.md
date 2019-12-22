# BrowserLocale

This small package helps you detect the languages accepted by the browser and guess the most appropriate depending on the available locales on your site.

## Installation

`composer require arcesilas/browser-locale`

## Usage

```php
$bestChoice = (new BrowserLocale($accept))
    ->among(...$locales)
    ->choose();
```

It's also a helper:

```php
$browserLocale = new BrowserLocale();

// Check if the browser accepts a locale
$browserLocale->accepts('de'); // false

// Get accepted locales, ordered by weight
$browserLocale->getAccepted(); // ['fr' => 1.0, 'fr-FR' => 0.8, 'en-US' => 0.5, 'en' => 0.3]

// Get the weight of a locale the browser accepts
$browserLocale->getWeight('en'); // 0.3
```
