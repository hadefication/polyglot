<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

class Compiler
{
    protected $path;
    protected $filesystem;
    protected $translator;
    protected $translations;

    /**
     * Constructor
     *
     * @param Translator $translator
     */
    public function __construct(Filesystem $filesystem, Translator $translator) {
        $this->filesystem = $filesystem;
        $this->translator = $translator;
    }

    public function usePath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function compile()
    {
        $this->make()->files()->each(function($file) {
            $name = $this->filename($file);
            $locale = $this->locale($file);
            if (strlen($locale) == 2) {
                if ($file->getExtension() == 'php') {
                    $this->translations[$locale]['keys'] = array_merge(
                        $this->translations[$locale]['keys'], 
                        [$name => $this->translator->trans($name, [], $locale)]
                    );
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

    public function files()
    {
        return new Collection($this->filesystem->allFiles($this->path));
    }

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

    public function locales()
    {
        return $this->files()
                    ->mapWithKeys(function($file) {
                        return [$this->locale($file) => []];
                    })
                    ->filter(function($item, $key) {
                        return strlen($key) > 0;
                    });
    }

    public function locale($file)
    {
        $key = $file->getRelativePath();
        if (strlen($key) == 0) {
            $key = str_replace(".{$file->getExtension()}", "", $file->getFilename());
        }
        return $key;
    }

    public function filename($file)
    {
        return str_replace(".{$file->getExtension()}", "", $file->getFilename());
    }
}