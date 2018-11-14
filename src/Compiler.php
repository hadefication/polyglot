<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

class Compiler
{
    /**
     * Path
     *
     * @var string
     */
    protected $path;

    /**
     * Config
     *
     * @var array
     */
    protected $config;

    /**
     * Filesystem
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Translator
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Translations
     *
     * @var array
     */
    protected $translations;

    /**
     * Constructor
     *
     * @param Translator $translator
     */
    public function __construct(Filesystem $filesystem, Translator $translator) {
        $this->filesystem = $filesystem;
        $this->translator = $translator;
        $this->config = config('polyglot');
    }

    /**
     * Path setter
     *
     * @param string $path
     * @return self
     */
    public function usePath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Compile
     *
     * @return array
     */
    public function compile()
    {
        $this->make()->files()->each(function($file) {
            $name = $this->getFilename($file);
            $locale = $this->getLocale($file);
            if (strlen($locale) == 2) {
                if ($file->getExtension() == 'php') {
                    if (in_array($name, ($this->config['files'] ?? []))) {
                        $this->translations[$locale]['keys'] = array_merge(
                            $this->translations[$locale]['keys'], 
                            [$name => $this->translator->trans($name, [], $locale)]
                        );
                    }
                } else {
                    $this->translations[$locale]['strings'] = array_merge(
                        $this->translations[$locale]['strings'], 
                        json_decode($file->getContents(), true)
                    );
                }
            }
        });
        return $this->translations;
    }

    /**
     * Get all files
     *
     * @return Collection
     */
    public function files()
    {
        return new Collection($this->filesystem->allFiles($this->path));
    }

    /**
     * Make translation schema
     *
     * @return self
     */
    public function make()
    {
        $this->translations = $this->locales()->filter(function($item, $key) {
                                                return strlen($key) == 2;
                                            })
                                            ->map(function($item, $key) {
                                                return ['keys' => [], 'strings' => []];
                                            })
                                            ->all();
        return $this;
    }

    /**
     * Get all available locales
     *
     * @return Collection
     */
    public function locales()
    {
        return $this->files()
                    ->mapWithKeys(function($file) {
                        return [$this->getLocale($file) => []];
                    })
                    ->filter(function($item, $key) {
                        return strlen($key) > 0;
                    });
    }

    /**
     * Extract locale base from path or the name of the supplied file
     *
     * @param SplFileInfo $file
     * @return string
     */
    public function getLocale($file)
    {
        $key = $file->getRelativePath();
        if (strlen($key) == 0) {
            $key = str_replace(".{$file->getExtension()}", "", $file->getFilename());
        }
        return $key;
    }

    /**
     * Extract filename
     *
     * @param \SplFileInfo $file
     * @return string
     */
    public function getFilename($file)
    {
        return str_replace(".{$file->getExtension()}", "", $file->getFilename());
    }
}