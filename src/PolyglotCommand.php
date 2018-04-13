<?php

namespace Hadefication\Polyglot;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PolyglotCommand extends Command
{
    /**
     * Command signature
     *
     * @var string
     */
    protected $signature = 'polyglot:dump {path=./resources/assets/js/polyglot.js}';
    
    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Dump\'s a JavaScript file the houses all translations that can be included to Laravel Mix or your custom build pipeline.';

    /**
     * Filesystem container
     *
     * @var Filesystem
     */
    protected $fs;

    /**
     * Translations container
     *
     * @var PolyglotTranslations
     */
    protected $translations;
    
    /**
     * Constructor
     *
     * @param Filesystem $fs
     * @param PolyglotTranslations $translations
     */
    public function __construct(Filesystem $fs, PolyglotTranslations $translations)
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
        $path = $this->argument('path');
        $this->makePath($path);
        $this->fs->put($path, $this->generateJavaScriptContents());
    }
    /**
     * Generate the js contents that will be dump
     *
     * @return string
     */
    public function generateJavaScriptContents()
    {
        $json = $this->translations->compile()->toJson();
        $transFunction = file_get_contents(__DIR__ . '/resources/assets/js/trans.js');
        return <<<EOT
var Polyglot = $json;
export { Polyglot };
$transFunction
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
