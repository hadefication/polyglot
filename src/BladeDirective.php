<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Facades\App;


class BladeDirective
{
    /**
     * Translations container
     *
     * @var Translations
     */
    protected $translations;

    /**
     * Constructor
     *
     * @param Translations $translations
     */
    public function __construct(Translations $translations)
    {
        $this->translations = $translations;
    }

    /**
     * Generate translation keys and export the trans function
     *
     * @return String
     */
    public function generate()
    {
        $polyglot = json_encode(array_merge([
            'settings' => [
                'locale' => App::getLocale(), 
                'fallback' => config('app.fallback_locale')
            ],
            'translations' => $this->translations->config()['mode'] == 'inline' 
                                ? $this->translations->compile()->toArray() 
                                : [],
        ]), JSON_PRETTY_PRINT);
        $helpers = file_get_contents(__DIR__ . '/dist/js/polyglot.js');
        return <<<EOT
<script type="text/javascript">
    var Polyglot = $polyglot;
    $helpers
</script>
EOT;
    }
}
