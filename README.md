# Polyglot

Polyglot creates a blade directive `@polyglot` that you can add to your master blade file. This will then export a global variable `__TRANSLATIONS__` where all of your app's current locale translation keys are stored. The root object keys are base from the defined translation files located in the Polyglot's configuration file.

## Installation
1. `composer require hadefication/polyglot`
2. Add `Hadefication\Polyglot\PolyglotServiceProvider::class` to your `config/app.php` under the `providers` array.
3. Include `@polyglot` blade directive to your master blade file on top of your JavaScript files -- prolly in the header or before the body tag ends.

## Usage
Once installed, this package will then expose a `__TRANSLATIONS__` variable where all of your current locale translation keys are stored.

A nifty JavaScript helper function will be exposed too that you can use to translate translation keys like what we're doing in Laravel. Accidentally named it `trans` too. See examples below for more details on `trans` helper function.

### Example
Without param
```
trans('auth.failed');
// Should return the equivalent translation of the supplied key
```

Translations with params
```
trans('validation.required', {attribute: 'email'}); // Should return the equivalent translation of the supplied key including the supplied params
```

### Config
A configuration file is included too to customize the translation files that will be loaded to `__TRANSLATIONS__`. To publish the included config file, run `php artisan vendor:publish`.
