<?php namespace Todaymade\Daux;

use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Todaymade\Daux\ContentTypes\ContentTypeHandler;
use Todaymade\Daux\Tree\Builder;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Directory;
use Todaymade\Daux\Tree\Root;

class Daux
{
    const STATIC_MODE = 'DAUX_STATIC';
    const LIVE_MODE = 'DAUX_LIVE';

    public static $output;

    /** @var string */
    public $local_base;

    /** @var \Todaymade\Daux\Format\Base\Generator */
    protected $generator;

    /** @var ContentTypeHandler */
    protected $typeHandler;

    /**
     * @var string[]
     */
    protected $validExtensions;

    /** @var Processor */
    protected $processor;

    /** @var Tree\Root */
    public $tree;

    /** @var Config */
    public $options;

    /** @var string */
    private $mode;

    /** @var bool */
    private $merged_tree = false;

    /**
     * @param string $mode
     */
    public function __construct($mode, OutputInterface $output)
    {
        Daux::$output = $output;
        $this->mode = $mode;

        $this->local_base = dirname(__DIR__);

        // global.json
        $this->loadBaseConfiguration();
    }

    /**
     * @param string $override_file
     * @throws Exception
     */
    public function initializeConfiguration($override_file = null)
    {
        $params = $this->getParams();

        // Validate and set theme path
        $params->setDocumentationDirectory(
            $docs_path = $this->normalizeDocumentationPath($this->getParams()->getDocumentationDirectory())
        );

        // Read documentation overrides
        $this->loadConfiguration($docs_path . DIRECTORY_SEPARATOR . 'config.json');

        // Read command line overrides
        $override_file = $this->getConfigurationOverride($override_file);
        if ($override_file !== null) {
            $params->setConfigurationOverrideFile($override_file);
            $this->loadConfiguration($override_file);
        }

        // Validate and set theme path
        $params->setThemesPath($this->normalizeThemePath($params->getThemesDirectory()));

        // Set a valid default timezone
        if ($params->hasTimezone()) {
            date_default_timezone_set($params->getTimezone());
        } elseif (!ini_get('date.timezone')) {
            date_default_timezone_set('GMT');
        }
    }

    /**
     * Get the file requested for configuration overrides
     *
     * @param string|null $path
     * @return string|null the path to a file to load for configuration overrides
     * @throws Exception
     */
    public function getConfigurationOverride($path)
    {
        $validPath = DauxHelper::findLocation($path, $this->local_base, 'DAUX_CONFIGURATION', 'file');

        if ($validPath === null) {
            return null;
        }

        if (!$validPath) {
            throw new Exception('The configuration override file does not exist. Check the path again : ' . $path);
        }

        return $validPath;
    }

    public function normalizeThemePath($path)
    {
        $validPath = DauxHelper::findLocation($path, $this->local_base, 'DAUX_THEME', 'dir');

        if (!$validPath) {
            throw new Exception('The Themes directory does not exist. Check the path again : ' . $path);
        }

        return $validPath;

    }

    public function normalizeDocumentationPath($path)
    {
        $validPath = DauxHelper::findLocation($path, $this->local_base, 'DAUX_SOURCE', 'dir');

        if (!$validPath) {
            throw new Exception('The Docs directory does not exist. Check the path again : ' . $path);
        }

        return $validPath;
    }

    /**
     * Load and validate the global configuration
     *
     * @throws Exception
     */
    protected function loadBaseConfiguration()
    {
        $this->options = new Config();

        // Set the default configuration
        $this->options->merge([
            'docs_directory' => 'docs',
            'valid_content_extensions' => ['md', 'markdown'],

            //Paths and tree
            'mode' => $this->mode,
            'local_base' => $this->local_base,
            'templates' => 'templates',

            'index_key' => 'index.html',
            'base_page' => '',
            'base_url' => '',
        ]);

        // Load the global configuration
        $this->loadConfiguration($this->local_base . DIRECTORY_SEPARATOR . 'global.json', false);
    }

    /**
     * @param string $config_file
     * @param bool $optional
     * @throws Exception
     */
    protected function loadConfiguration($config_file, $optional = true)
    {
        if (!file_exists($config_file)) {
            if ($optional) {
                return;
            }

            throw new Exception('The configuration file is missing. Check path : ' . $config_file);
        }

        $config = json_decode(file_get_contents($config_file), true);
        if (!isset($config)) {
            throw new Exception('The configuration file "' . $config_file . '" is corrupt. Is your JSON well-formed ?');
        }
        $this->options->merge($config);
    }

    /**
     * Generate the tree that will be used
     */
    public function generateTree()
    {
        $this->options['valid_content_extensions'] = $this->getContentExtensions();

        $this->tree = new Root($this->getParams());
        Builder::build($this->tree, $this->options['ignore']);

        // Apply the language name as Section title
        if ($this->options->isMultilanguage()) {
            foreach ($this->options['languages'] as $key => $node) {
                $this->tree->getEntries()[$key]->setTitle($node);
            }
        }

        // Enhance the tree with processors
        $this->getProcessor()->manipulateTree($this->tree);

        // Sort the tree one last time before it is finalized
        Builder::sortTree($this->tree);

        Builder::finalizeTree($this->tree);
    }

    /**
     * @return Config
     */
    public function getParams()
    {
        if ($this->tree && !$this->merged_tree) {
            $this->options['tree'] = $this->tree;
            $this->options['index'] = $this->tree->getIndexPage() ?: $this->tree->getFirstPage();
            if ($this->options->isMultilanguage()) {
                foreach ($this->options['languages'] as $key => $name) {
                    $this->options['entry_page'][$key] = $this->tree->getEntries()[$key]->getFirstPage();
                }
            } else {
                $this->options['entry_page'] = $this->tree->getFirstPage();
            }
            $this->merged_tree = true;
        }

        return $this->options;
    }

    /**
     * @return Processor
     */
    public function getProcessor()
    {
        if (!$this->processor) {
            $this->processor = new Processor($this, Daux::getOutput(), 0);
        }

        return $this->processor;
    }

    /**
     * @param Processor $processor
     */
    public function setProcessor(Processor $processor)
    {
        $this->processor = $processor;

        // This is not the cleanest but it's
        // the best i've found to use the
        // processor in very remote places
        $this->options['processor_instance'] = $processor;
    }

    public function getGenerators()
    {
        $default = [
            'confluence' => '\Todaymade\Daux\Format\Confluence\Generator',
            'html-file' => '\Todaymade\Daux\Format\HTMLFile\Generator',
            'html' => '\Todaymade\Daux\Format\HTML\Generator',
        ];

        $extended = $this->getProcessor()->addGenerators();

        return array_replace($default, $extended);
    }


    public function getProcessorClass()
    {
        $processor = $this->getParams()['processor'];

        if (empty($processor)) {
            return null;
        }

        $class = '\\Todaymade\\Daux\\Extension\\' . $processor;
        if (!class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found. We cannot use it as a Processor");
        }

        if (!array_key_exists('Todaymade\\Daux\\Processor', class_parents($class))) {
            throw new \RuntimeException("Class '$class' invalid, should extend '\\Todaymade\\Daux\\Processor'");
        }

        return $class;
    }

    protected function findAlternatives($input, $words) {
        $alternatives = [];

        foreach ($words as $word) {
            $lev = levenshtein($input, $word);

            if ($lev <= \strlen($word) / 3) {
                $alternatives[] = $word;
            }
        }

        return $alternatives;
    }

    /**
     * @return \Todaymade\Daux\Format\Base\Generator
     */
    public function getGenerator()
    {
        if ($this->generator) {
            return $this->generator;
        }

        $generators = $this->getGenerators();

        $format = $this->getParams()->getFormat();

        if (!array_key_exists($format, $generators)) {
            $message = "The format '$format' doesn't exist, did you forget to set your processor ?";

            $alternatives = $this->findAlternatives($format, array_keys($generators));

            if (0 == \count($alternatives)) {
                $message .= "\n\nAvailable formats are \n    " . implode("\n    ", array_keys($generators));
            } elseif (1 == \count($alternatives)) {
                $message .= "\n\nDid you mean this?\n    " . implode("\n    ", $alternatives);
            } else {
                $message .= "\n\nDid you mean one of these?\n    " . implode("\n    ", $alternatives);
            }

            throw new \RuntimeException($message);
        }

        $class = $generators[$format];
        if (!class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found. We cannot use it as a Generator");
        }

        $interface = 'Todaymade\Daux\Format\Base\Generator';
        if (!in_array('Todaymade\Daux\Format\Base\Generator', class_implements($class))) {
            throw new \RuntimeException("The class '$class' does not implement the '$interface' interface");
        }

        return $this->generator = new $class($this);
    }

    public function getContentTypeHandler()
    {
        if ($this->typeHandler) {
            return $this->typeHandler;
        }

        $base_types = $this->getGenerator()->getContentTypes();

        $extended = $this->getProcessor()->addContentType();

        $types = array_merge($base_types, $extended);

        return $this->typeHandler = new ContentTypeHandler($types);
    }

    /**
     * Get all content file extensions
     *
     * @return string[]
     */
    public function getContentExtensions()
    {
        if (!empty($this->validExtensions)) {
            return $this->validExtensions;
        }

        return $this->validExtensions = $this->getContentTypeHandler()->getContentExtensions();
    }

    public static function getOutput() {
        if (!Daux::$output) {
            Daux::$output = new NullOutput();
        }

        return Daux::$output;
    }

    /**
     * Writes a message to the output.
     *
     * @param string|array $messages The message as an array of lines or a single string
     * @param bool         $newline  Whether to add a newline
     * @param int          $options  A bitmask of options (one of the OUTPUT or VERBOSITY constants), 0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
     */
    public static function write($messages, $newline = false, $options = 0) {
        Daux::getOutput()->write($messages, $newline, $options);
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string|array $messages The message as an array of lines of a single string
     * @param int          $options  A bitmask of options (one of the OUTPUT or VERBOSITY constants), 0 is considered the same as self::OUTPUT_NORMAL | self::VERBOSITY_NORMAL
     */
    public static function writeln($messages, $options = 0) {
        Daux::getOutput()->write($messages, true, $options);
    }

    public static function getVerbosity() {
        return Daux::getOutput()->getVerbosity();
    }
}
