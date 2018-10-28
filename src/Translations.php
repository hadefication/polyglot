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
        $this->initSchema()
            ->getFiles()->each(function($file) {
                $locale = $this->resolveLocale($file);
                $name = $this->resolveFilename($file);

                if (strlen($locale) == 2) {
                    if ($file->getExtension() == 'php') {
                        $this->translations[$locale]['keys'] = array_merge($this->translations[$locale]['keys'], [
                            $name => $this->translator->trans($name, [], $locale)
                        ]);
                    } else {
                        $this->translations[$locale]['strings'] = array_merge($this->translations[$locale]['strings'], json_decode($file->getContents(), true));
                    }
                }
            });

        // dd($this->resolveFiles(), $this->getThirdPartyLocales(), $this->translations);
        // $this->files()->each(function($file) {
        //     $this->translations[$file] = $this->translator->trans($file);
        // });
        return $this;
    }

    public function initSchema()
    {
        $this->translations = $this->getNativeLocales()
                                ->map(function($item, $key) {
                                    return [
                                        'keys' => [],
                                        'strings' => []
                                    ];
                                })
                                ->all();
        return $this;
    }

    public function resolveFiles()
    {
        return $this->fs->allFiles($this->settings['path']);
    }

    public function getFiles()
    {
        return new Collection($this->resolveFiles());
    }

    public function getAllLocales()
    {
        return (new Collection($this->resolveFiles()))
                ->mapWithKeys(function($file) {
                    return [$this->resolveLocale($file) => []];
                })
                ->filter(function($item, $key) {
                    return strlen($key) > 0;
                });
    }

    public function getNativeLocales()
    {
        return $this->getAllLocales()->filter(function($item, $key) {
            return strlen($key) == 2;
        });
    }

    public function getThirdPartyLocales()
    {
        return $this->getAllLocales()->filter(function($item, $key) {
                                        return starts_with($key, 'vendor/');
                                    })
                                    ->mapWithKeys(function($item, $key) {
                                        $segment = explode('/', $key);
                                        return [$segment[1] => []];
                                    });
    }

    public function resolveLocale($file)
    {
        $key = $file->getRelativePath();
        if (strlen($key) == 0) {
            $key = str_replace(".{$file->getExtension()}", "", $file->getFilename());
        }
        return $key;
    }

    public function resolveFilename($file)
    {
        return str_replace(".{$file->getExtension()}", "", $file->getFilename());
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

    /**
     * Return compiled translations
     *
     * @return array
     */
    public function toArray()
    {
        return $this->translations;
    }


    /**
     * Get translation config
     *
     * @return array
     */
    public function config()
    {
        return $this->settings;
    }

}
