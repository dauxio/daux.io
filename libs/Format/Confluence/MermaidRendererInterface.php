<?php

declare(strict_types=1);

namespace Todaymade\Daux\Format\Confluence;

use Todaymade\Daux\Config;
use Todaymade\Daux\Exception;
use Todaymade\Daux\Tree\ComputedRaw;
use Todaymade\Daux\Tree\Directory;

interface MermaidRendererInterface
{
    /**
     * Render a Mermaid diagram to an image.
     *
     * @param string    $mermaidCode The Mermaid diagram code
     * @param string    $diagramId   Unique identifier for the diagram
     * @param Config    $config      Daux configuration
     * @param Directory $parent      Parent directory for the ComputedRaw
     *
     * @return ComputedRaw The rendered image as a ComputedRaw attachment
     *
     * @throws Exception If rendering fails
     */
    public function render(string $mermaidCode, string $diagramId, Config $config, Directory $parent): ComputedRaw;

    /**
     * Check if this renderer is available and can be used.
     *
     * @param Config $config Daux configuration
     *
     * @return bool True if renderer is available
     */
    public function isAvailable(Config $config): bool;

    /**
     * Get the name of this renderer (for error messages and logging).
     *
     * @return string Renderer name
     */
    public function getName(): string;
}
