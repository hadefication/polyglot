<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

class Translations
{
    
    protected $fs;
    protected $settings;

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
    public function __construct(Translator $translator, Filesystem $fs) {
        $this->fs = $fs;
        $this->translator = $translator;
        $this->settings = config('polyglot');
    }

    /**
     * Get translations files
     *
     * @return Collection
     */
    public function files()
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
        $translations = [];
        $files = $this->fs->allFiles($this->settings['path']);
        $locales = (new Collection($files))
                        ->mapWithKeys(function($file) {
                            $key = $file->getRelativePath();
                            if (strlen($key) == 0) {
                                $key = str_replace(".{$file->getExtension()}", "", $file->getFilename());
                            }
                            return [$key => []];
                        })
                        ->filter(function($item, $key) {
                            return strlen($key) > 0;
                        })
                        ->all();

        

        dd($files, $locales);
        $this->files()->each(function($file) {
            $this->translations[$file] = $this->translator->trans($file);
        });
        return $this;
    }

    /**
     * Encode to JSON
     *
     * @param integer $options
     * @param integer $depth
     * @return string
     */
    public function toJson($options = 0, $depth = 512)
    {
        return json_encode($this->translations, $options);
    }

}
