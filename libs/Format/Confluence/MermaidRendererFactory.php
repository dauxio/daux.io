<?php

declare(strict_types=1);

namespace Todaymade\Daux\Format\Confluence;

use Todaymade\Daux\Config;
use Todaymade\Daux\Exception;

class MermaidRendererFactory
{
    /**
     * Create the appropriate Mermaid renderer based on configuration.
     *
     * @param Config $config Daux configuration
     *
     * @return MermaidRendererInterface The renderer instance
     *
     * @throws Exception If no renderer is available
     */
    public static function create(Config $config): MermaidRendererInterface
    {
        $confluenceConfig = $config->getConfluenceConfiguration();

        // Priority order: CLI > Future: Node.js > Future: Kroki
        $renderers = [
            new MermaidCliRenderer(),
            // Future: new MermaidNodeRenderer(),
            // Future: new MermaidKrokiRenderer($confluenceConfig->getMermaidKrokiUrl()),
        ];

        foreach ($renderers as $renderer) {
            if ($renderer->isAvailable($config)) {
                return $renderer;
            }
        }

        throw new Exception(
            'No Mermaid renderer is available. '
            . 'Please install @mermaid-js/mermaid-cli: npm install -g @mermaid-js/mermaid-cli'
        );
    }
}
