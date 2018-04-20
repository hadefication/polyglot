<?php

namespace Hadefication\Polyglot;

use SplFileInfo;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
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

    protected $fs;

    protected $languages = [];

    /**
     * Constructor
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator, Filesystem $fs) {
        $this->translator = $translator;
        $this->fs = $fs;
    }

    public function locale()
    {
        return $this->translator->locale();
    }

    public function fallbackLocale()
    {
        return $this->translator->getFallback();
    }

    public function getTheNameOnly(SplFileInfo $file)
    {
        return join('.', array_slice(explode('.', $file->getFilename()), 0, -1));
    }

    /**
     * Compile translations
     *
     * @return self
     */
    public function compile()
    {
        $files = new Collection($this->fs->allFiles(config()->get('polyglot.location')));

        $strings = $files->filter(function($file) {
            return $file->getExtension() == 'json';
        });

        $keys = $files->filter(function($file) {
            return $file->getExtension() == 'php';
        });

        $keys->each(function($file) {
            $this->languages[$file->getPathinfo()->getBasename()] = $this->stub();
        });

        $keys->each(function($file) {
            $locale = $file->getPathinfo()->getBasename();
            $languages = $this->languages[$locale]['keys'];
            $key = $this->getTheNameOnly($file);
            $keys = $this->translator->trans($key, [], $locale);
            $this->languages[$locale]['keys'] = array_merge($languages, [$key => $keys]);
        });

        $strings->each(function($file) {
            $locale = $this->getTheNameOnly($file);
            $contents = (array) json_decode(file_get_contents($file->getRealPath()));
            if (!isset($this->languages[$locale])) {
                $this->languages[$locale] = $this->stub();
            }
            
            $languages = $this->languages[$locale]['strings'];
            $this->languages[$locale]['strings'] = array_merge($languages, $contents);
        });
        return $this;
    }

    public function stub()
    {
        return [
            'keys' => [],
            'strings' => []
        ];
    }

    /**
     * Encode to JSON
     *
     * @param integer $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->languages, $options);
    }

}
