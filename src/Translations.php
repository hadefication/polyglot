<?php

namespace Hadefication\Polyglot;

use Illuminate\Support\Collection;
use Illuminate\Translation\Translator;

class Translations
{
    
    protected $config;

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
        $this->config = config('polyglot');
    }

    /**
     * Compile translations
     *
     * @return self
     */
    public function compile()
    {
        $this->init()->laravel()->vendor();
        return $this;
    }

    /**
     * Initialize
     *
     * @return self
     */
    public function init()
    {
        $this->translations = ['laravel' => [], 'vendor' => []];
        return $this;
    }

    /**
     * Compile laravel language files
     *
     * @return self
     */
    public function laravel()
    {
        $path = $this->getProtectedProps($this->translator->getLoader(), 'path');
        $this->translations['laravel'] = $this->compiler->usePath($path)->compile();
        return $this;
    }

    /**
     * Compile vendor files
     *
     * @return self
     */
    public function vendor()
    {
        $paths = $this->getProtectedProps($this->translator->getLoader(), 'hints');
        $this->translations['vendor'] = (new Collection($paths))
                                            ->filter(function($path, $vendor) {
                                                $packages = $this->config['packages'] ?? [];
                                                if (empty($packages)) {
                                                    return true;
                                                } else {
                                                    return in_array($vendor, $packages);
                                                }
                                            })
                                            ->mapWithKeys(function($path, $vendor) {
                                                return [$vendor => $this->compiler->usePath($path)->compile()];
                                            })
                                            ->all();
        return $this;
    }

    /**
     * Read protected props
     *
     * @param mixed $obj
     * @param string $prop
     * @return mixed
     */
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
        return $this->config;
    }

}
