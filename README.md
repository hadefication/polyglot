# Polyglot

Polyglot creates a blade directive `@polyglot` that you can add to your master blade file. This will then export a global variable `Polyglot` where all of your app's current locale translation keys are stored. The root object keys are base from the defined translation files located in the Polyglot's configuration file.

## Installation
1. `composer require hadefication/polyglot`
2. Add `Hadefication\Polyglot\PolyglotServiceProvider::class` to your `config/app.php` under the `providers` array. This step is not needed for version 5.5 and above where package auto-discovery is introduced.
3. Include `@polyglot` blade directive to your master blade file on top of your JavaScript files -- probably in the header or before the body tag ends.

## Usage
Once installed, this package will then expose a `Polyglot` variable where all of your current locale translation keys are stored.

A nifty JavaScript helper function will be exposed too that you can use to translate translation keys like what we're doing in Laravel. Accidentally named it `trans` too. See examples below for more details on `trans` helper function.

### Example
Without param
```
trans('auth.failed');

// Should return the equivalent translation of the supplied key
```

Translations with params
```
trans('validation.required', {attribute: 'email'});

// Should return the equivalent translation of the supplied key including the supplied params
```

## Config
A configuration file is included too to customize the translation files that will be loaded to `Polyglot`. To publish the included config file, run `php artisan vendor:publish`.

## Artisan Command
An artisan command is also provided where it will dump a JavaScript file that houses all collected translation keys including the importable route method helper. Upon using this approach, including the `@polyglot` blade directive won't be necessary.

```
php artisan polyglot:dump
```
The command above should dump a JavaScript file named polyglot.js in your  `/resources/assets/js` directory. You can also supply `--path=/path/to/where/the/dump/file/will/be/exported` to dumpt the file in other location. The command should look like `php artisan polyglot:dump --path=./resources/assets/js/vendor/polyglot.js`.

```
// ES6
import './path/to/polyglot';

// Old School
require('./path/to/polyglot.js');
```
The code above should be added to your bootstrap file or to the main JavaScript file if you have a custom entry point.

### Laravel Mix
You can also automate the dumping by installing a webpack plugin that runs a simple artisan command on every build so you are sure that you got the latest translation files included in your build. Follow steps below:

1. Install the [webpack shell plugin](https://github.com/1337programming/webpack-shell-plugin): `npm install --save-dev webpack-shell-plugin` or `yarn add --dev webpack-shell-plugin`
2. Include the plugin to your `webpack.mix.js` file:
```
const mix = require('laravel-mix');
const WebpackShellPlugin = require('webpack-shell-plugin');

mix.webpackConfig({
    plugins: [
        new WebpackShellPlugin({ onBuildStart: ['php artisan polyglot:dump'], onBuildEnd: [] }),
    ]
});

....
```
3. Done! This will run `php artisan polyglot:dump` on start of the build so you get the latest translation files.