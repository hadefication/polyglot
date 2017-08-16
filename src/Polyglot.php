<?php

namespace Hadefication\Polyglot;

use Illuminate\Translation\Translator;

class Polyglot
{
    /**
     * Translator class container
     *
     * @var Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * Constructor
     *
     * @param Illuminate\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Compile translation files to array
     *
     * @return Array
     */
    public function compileTranslationFiles()
    {
        // Get translation files
        $files = config('polyglot.files');

        $translations = [];
        
        foreach ($files as $key => $file) {
            // Load all translation keys filed under the translation file
            $translations[$file] = $this->translator->trans($file);
        }

        return $translations;
    }

    /**
     * Generate tranlation keys and export the trans function
     *
     * @return String
     */
    public function generate()
    {
        $json = json_encode($this->compileTranslationFiles());
        $transFunction = file_get_contents(__DIR__ . '/dist/js/trans.min.js');

        return <<<EOT
<script type="text/javascript">
    var __TRANSLATIONS__ = $json;
    $transFunction
</script>
EOT;
    }
}
