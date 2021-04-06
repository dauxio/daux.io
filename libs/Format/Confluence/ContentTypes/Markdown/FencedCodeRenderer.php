<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Util\Xml;
use Todaymade\Daux\Config;

class FencedCodeRenderer extends CodeRenderer
{
    protected $supported_languages = [
        'actionscript3',
        'bash',
        'csharp',
        'coldfusion',
        'cpp',
        'css',
        'delphi',
        'diff',
        'erlang',
        'groovy',
        'html/xml',
        'java',
        'javafx',
        'javascript',
        'none',
        'perl',
        'php',
        'powershell',
        'python',
        'ruby',
        'scala',
        'sql',
        'vb',

        // Special treatment
        'tex',
        'mermaid',
    ];
    protected $known_conversions = ['html' => 'html/xml', 'xml' => 'html/xml', 'js' => 'javascript'];

    /**
     * @var Config
     */
    protected $config;

    function __construct(Config $config) {
        $this->config = $config;
    }

    /**
     * @param bool $inTightList
     *
     * @return HtmlElement|string
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        if (!($block instanceof FencedCode)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }

        $language = $this->getLanguage($block->getInfoWords());

        if ($language === 'tex') {
            $this->config['__confluence__tex'] = true;
            return new HtmlElement(
                'pre',
                [],
                new HtmlElement('code', ['class' => 'katex'], Xml::escape($block->getStringContent()))
            );
        }

        if ($language === 'mermaid') {
            $this->config['__confluence__mermaid'] = true;
            // We render this as <pre> so confluence will leave the content as-is, otherwise it will remove
            // newlines and other formatting.
            // There is a script to transform it back to a <div>
            // Also, if the diagram can't be rendered at least it is displayed in a formatted way
            return new HtmlElement('pre', ['class' => 'mermaid'], Xml::escape($block->getStringContent()));
        }

        return $this->getHTMLElement($block->getStringContent(), $language);
    }

    public function getLanguage($infoWords)
    {
        if (count($infoWords) === 0 || strlen($infoWords[0]) === 0) {
            return false;
        }

        $language = Xml::escape($infoWords[0]);

        if (array_key_exists($language, $this->known_conversions)) {
            $language = $this->known_conversions[$language];
        }

        if (in_array($language, $this->supported_languages)) {
            return $language;
        }

        return false;
    }
}
