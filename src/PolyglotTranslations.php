<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class PolyglotTranslations
{

    /**
     * Translator container
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Translations container
     *
     * @var array
     */
    protected $translations = [];

    /**
     * Constructor
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator) {
        $this->translator = $translator;
    }

    /**
     * Get translations files
     *
     * @return Collection
     */
    public function translationFiles()
    {
        return new Collection(config('polyglot.files'));
    }

    /**
     * Compile translations
     *
     * @return self
     */
    public function compile()
    {
        $this->translationFiles()->each(function($file) {
            $this->translations[$file] = $this->translator->trans($file);
        });
        return $this;
    }

    /**
     * Encode to JSON
     *
     * @param integer $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->translations, $options);
    }

}
