<?php

namespace Hadefication\Polyglot;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Console extends Command
{
    /**
     * Command signature
     *
     * @var string
     */
    protected $signature = 'route:dump 
                            {--path= : Path where to dump the file, defaults to ./resources/js/polyglot.js}';
    
    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Dump a JavaScript file the houses all translations that can be included to Laravel Mix or your custom build pipeline.';

    /**
     * Filesystem container
     *
     * @var Filesystem
     */
    protected $fs;

    /**
     * Translations container
     *
     * @var Translations
     */
    protected $translations;
    
    /**
     * Constructor
     *
     * @param Filesystem $fs
     * @param Translations $translations
     */
    public function __construct(Filesystem $fs, Translations $translations)
    {
        parent::__construct();
        $this->fs = $fs;
        $this->translations = $translations;
    }

    /**
     * Handle
     *
     * @return void
     */
    public function handle()
    {
        $path = is_null($this->option('path')) ? './resources/js/polyglot.js' : $this->option('path');
        $this->makePath($path);
        $this->fs->put($path, $this->generate());
    }
    /**
     * Generate the js contents that will be dump
     *
     * @return string
     */
    public function generate()
    {
        $translations = $this->translations->compile()->toJson(JSON_PRETTY_PRINT);
        $polyglot = file_get_contents(__DIR__ . '/js/polyglot.js');
        return <<<EOT
/**
 * Translations
 * 
 * @type {Object}
 */
const Polyglot = $translations;

$polyglot
EOT;
    }

    /** 
     * Make path
     * 
     * @param string $path                      the path to make
     * @return string
     */
    protected function makePath($path)
    {
        if (!$this->fs->isDirectory(dirname($path))) {
            $this->fs->makeDirectory(dirname($path), 0777, true, true);
        }
        return $path;
    }
}
