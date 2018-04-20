<?php

namespace Hadefication\Polyglot;

class PolyglotBladeDirective
{
    /**
     * Translations container
     *
     * @var PolyglotTranslations
     */
    protected $translations;

    /**
     * Constructor
     *
     * @param PolyglotTranslations $translations
     */
    public function __construct(PolyglotTranslations $translations)
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
        $setup = config()->get('polyglot.setup', 'default');
        $json = ($setup == 'default') ? $this->translations->compile()->toJson() : '{}';
        $transFunction = ($setup == 'default') ? file_get_contents(__DIR__ . '/../dist/js/trans.min.js') : '';
        $locale = $this->translations->locale();
        $fallbackLocale = $this->translations->fallbackLocale();
        return <<<EOT
<script type="text/javascript">
    var Polyglot = {
        translations: $json,
        activeLocale: '$locale',
        fallbackLocale: '$fallbackLocale',
    };$transFunction
</script>
EOT;
    }
}
