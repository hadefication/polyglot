<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class Translations
{
    
    protected $settings;

    protected $compiler;

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
    public function __construct(Compiler $compiler, Translator $translator) {
        $this->compiler = $compiler;
        $this->translator = $translator;
        $this->settings = config('polyglot');
    }

    /**
     * Compile translations
     *
     * @return self
     */
    public function compile()
    {
        $this->init()->laravel()->vendor();
        // dd($this->translations);
        return $this;
    }

    public function init()
    {
        $this->translations = ['laravel' => [], 'vendor' => []];
        return $this;
    }

    public function laravel()
    {
        $path = $this->getProtectedProps($this->translator->getLoader(), 'path');
        $this->translations['laravel'] = $this->compiler->usePath($path)->compile();
        return $this;
    }

    public function vendor()
    {
        $paths = $this->getProtectedProps($this->translator->getLoader(), 'hints');
        $this->translations['vendor'] = (new Collection($paths))
                                            ->mapWithKeys(function($path, $vendor) {
                                                return [$vendor => $this->compiler->usePath($path)->compile()];
                                            })
                                            ->all();
        return $this;
    }

    private function getProtectedProps($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
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
