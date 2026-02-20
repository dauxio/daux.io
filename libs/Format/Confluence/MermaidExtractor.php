<?php

declare(strict_types=1);

namespace Todaymade\Daux\Format\Confluence;

class MermaidExtractor
{
    /**
     * Extract all Mermaid code blocks from HTML content.
     *
     * @param string $content HTML content containing Mermaid blocks
     *
     * @return array<int, array<string, mixed>> Array of diagram data with keys: id, code, placeholder, original
     */
    public function extractFromContent(string $content): array
    {
        $diagrams = [];
        $pattern = '/<pre[^>]*class="mermaid"[^>]*>(.*?)<\/pre>/s';

        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
            foreach ($matches as $index => $match) {
                $diagrams[] = [
                    'id' => $this->generateUniqueId($index),
                    'code' => html_entity_decode($match[1][0], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'placeholder' => $this->createPlaceholder($index),
                    'original' => $match[0][0],
                ];
            }
        }

        return $diagrams;
    }

    /**
     * Generate a unique identifier for a diagram.
     *
     * @param int $index Diagram index
     *
     * @return string Unique identifier
     */
    private function generateUniqueId(int $index): string
    {
        return 'mermaid-' . uniqid('', true) . '-' . $index;
    }

    /**
     * Create a placeholder string for replacement.
     *
     * @param int $index Diagram index
     *
     * @return string Placeholder string
     */
    private function createPlaceholder(int $index): string
    {
        return "<!-- MERMAID_PLACEHOLDER_{$index} -->";
    }
}
