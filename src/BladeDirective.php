<?php

namespace Hadefication\Polyglot;

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
        $translations = $this->translations->compile()->toJson();
        $polyglot = file_get_contents(__DIR__ . '/dist/js/polyglot.js');
        return <<<EOT
<script type="text/javascript">
    var Polyglot = $translations;
    $polyglot
</script>
EOT;
    }
}
