<?php

namespace Hadefication\Polyglot;

use Illuminate\Translation\Translator;

class Polyglot
{
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

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
