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
     * Generate tranlation keys and export the trans function
     *
     * @return String
     */
    public function generate()
    {
        $translations = [];

        $files = config('polyglot.files');

        foreach ($files as $key => $file) {
            $translations[$file] = $this->translator->trans($file);
        }

        $json = json_encode($translations);
        $transFunction = file_get_contents(__DIR__ . '/dist/js/trans.min.js');

        return <<<EOT
<script type="text/javascript">
    var __TRANSLATIONS__ = $json;
    $transFunction
</script>
EOT;
    }
}
