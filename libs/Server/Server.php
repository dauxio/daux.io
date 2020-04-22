<?php namespace Todaymade\Daux\Server;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;
use Todaymade\Daux\ConfigBuilder;
use Todaymade\Daux\Daux;
use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Exception;
use Todaymade\Daux\Format\Base\ComputedRawPage;
use Todaymade\Daux\Format\Base\LiveGenerator;
use Todaymade\Daux\Format\Base\Page;
use Todaymade\Daux\Format\HTML\RawPage;

class Server
{
    private $daux;
    private $config;
    private $base_url;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Daux $daux)
    {
        $this->daux = $daux;

        $this->request = Request::createFromGlobals();
        $this->base_url = $this->request->getHttpHost() . $this->request->getBaseUrl() . '/';
    }

    /**
     * Serve the documentation.
     *
     * @throws Exception
     */
    public static function serve()
    {
        $verbosity = getenv('DAUX_VERBOSITY');
        $output = new ConsoleOutput($verbosity);

        $configFile = getenv('DAUX_CONFIG');
        if ($configFile) {
            $config = ConfigBuilder::fromFile($configFile);
        } else {
            $config = ConfigBuilder::withMode(Daux::LIVE_MODE)->build();
        }

        $daux = new Daux($config, $output);

        $class = $daux->getProcessorClass();
        if (!empty($class)) {
            $daux->setProcessor(new $class($daux, $output, 0));
        }

        // Improve the tree with a processor
        $daux->generateTree();

        $server = new static($daux);

        try {
            $page = $server->handle();
        } catch (NotFoundException $e) {
            $page = new ErrorPage('An error occured', $e->getMessage(), $daux->getConfig());
        }

        $server->createResponse($page)->prepare($server->request)->send();
    }

    /**
     * Create a temporary file with the file suffix, for mime type detection.
     *
     * @param string $postfix
     *
     * @return string
     */
    private function getTemporaryFile($postfix)
    {
        $sysFileName = tempnam(sys_get_temp_dir(), 'daux');
        if ($sysFileName === false) {
            throw new \RuntimeException('Could not create temporary file');
        }

        $newFileName = $sysFileName . $postfix;
        if ($sysFileName == $newFileName) {
            return $sysFileName;
        }

        if (DIRECTORY_SEPARATOR == '\\' ? rename($sysFileName, $newFileName) : link($sysFileName, $newFileName)) {
            return $newFileName;
        }

        throw new \RuntimeException('Could not create temporary file');
    }

    /**
     * @return Response
     */
    public function createResponse(Page $page)
    {
        // Add a custom MimeType guesser in case the default ones are not available
        // This makes sure that at least CSS and JS work fine.
        $mimeTypes = MimeTypes::getDefault();
        $mimeTypes->registerGuesser(new ExtensionMimeTypeGuesser());

        if ($page instanceof RawPage) {
            return new BinaryFileResponse($page->getFile());
        }

        if ($page instanceof ComputedRawPage) {
            $file = $this->getTemporaryFile($page->getFilename());
            file_put_contents($file, $page->getContent());

            return new BinaryFileResponse($file);
        }

        return new Response($page->getContent(), $page instanceof ErrorPage ? 404 : 200);
    }

    /**
     * @return \Todaymade\Daux\Config
     */
    public function getConfig()
    {
        $config = $this->daux->getConfig();

        DauxHelper::rebaseConfiguration($config, '//' . $this->base_url);

        return $config;
    }

    /**
     * Handle an incoming request.
     *
     * @throws Exception
     * @throws NotFoundException
     *
     * @return \Todaymade\Daux\Format\Base\Page
     */
    public function handle()
    {
        $this->config = $this->getConfig();

        $request = substr($this->request->getRequestUri(), strlen($this->request->getBaseUrl()) + 1);

        if (substr($request, 0, 7) == 'themes/') {
            return $this->serveTheme(substr($request, 6));
        }

        if ($request == '') {
            $request = $this->daux->tree->getIndexPage()->getUri();
        }

        return $this->getPage($request);
    }

    /**
     * Handle a request on custom themes.
     *
     * @param string $request
     *
     * @throws NotFoundException
     *
     * @return \Todaymade\Daux\Format\Base\Page
     */
    public function serveTheme($request)
    {
        $file = $this->getConfig()->getThemesPath() . $request;

        if (file_exists($file)) {
            return new RawPage($file);
        }

        throw new NotFoundException();
    }

    /**
     * @param string $request
     *
     * @throws NotFoundException
     *
     * @return \Todaymade\Daux\Format\Base\Page
     */
    private function getPage($request)
    {
        $file = DauxHelper::getFile($this->daux->tree, $request);
        if ($file === false) {
            throw new NotFoundException('The Page you requested is yet to be made. Try again later.');
        }

        $this->daux->tree->setActiveNode($file);

        $generator = $this->daux->getGenerator();

        if (!$generator instanceof LiveGenerator) {
            throw new \RuntimeException(
                "The generator '" . get_class($generator) . "' does not implement the interface " .
                "'Todaymade\\Daux\\Format\\Base\\LiveGenerator' and thus doesn't support live rendering."
            );
        }

        return $this->daux->getGenerator()->generateOne($file, $this->config);
    }
}
