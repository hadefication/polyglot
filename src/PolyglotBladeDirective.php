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
        $json = $this->translations->compile()->toJson();
        $transFunction = file_get_contents(__DIR__ . '/../dist/js/trans.min.js');
        return <<<EOT
<script type="text/javascript">
    var Polyglot = $json;
    $transFunction
</script>
EOT;
    }
}
