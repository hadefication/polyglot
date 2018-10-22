# Laravel Polyglot

Extends Laravel's localization handling to JavaScript.

## Installation
1. Install it by running `composer require hadefication/polyglot`.
2. Publish the included config file by running `php artisan vendor:publish` and select the corresponding number of `Hadefication\Polyglot\PolyglotServiceProvider` entry and that should be it.

## Usage
Once the package is installed, you can either use 1 of the 2 approaches. The first approach is by adding the polyglot blade directive right before your main JavaScript file. See example below:

```
        ...
        @polyglot
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
```
The second approach is to run the included command that dumps JavaScript file that can then be included anywhere in your JavaScript handlings. Fire it up by running `php artisan route:dump`, this will then dump a JavaScript file in your `resources/js` directory. The command also includes an optional param `--path` to customize where the JavaScript file will be dumped.

Below is rhe standard command, will dump a JavaScript file to `resources/js` (`resources/js/polyglot.js`)
```
php artisan route:dump
````

Below will dump a JavaScript file to `/resources/js/assets`(`resources/assets/js/polyglot.js`)
```
php artisan route:dump --path=./resources/assets/js/polyglot.js
```

And finally below will dump a JavaScript file will a custom name to `resources/js/` (`resources/js/custom_name.js`)
```
php artisan route:dump --path=./resources/js/custom_name.js
```
## API
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