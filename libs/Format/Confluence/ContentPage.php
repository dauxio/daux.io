<?php namespace Todaymade\Daux\Format\Confluence;

use Todaymade\Daux\Exception;
use Todaymade\Daux\Format\Base\EmbedImages;
use Todaymade\Daux\Tree\ComputedRaw;
use Todaymade\Daux\Tree\Entry;
use Todaymade\Daux\Tree\Raw;

class ContentPage extends \Todaymade\Daux\Format\Base\ContentPage
{
    public $attachments = [];

    protected function generatePage()
    {
        // Reset Mermaid data for this page before rendering
        $this->config['__confluence__mermaid_prerender'] = [];

        $content = parent::generatePage();

        $confluenceConfig = $this->config->getConfluenceConfiguration();

        // Handle Mermaid pre-rendering if enabled
        if ($confluenceConfig->getPreRenderMermaid()) {
            $extractor = new MermaidExtractor();

            try {
                // Use factory to get appropriate renderer
                $renderer = MermaidRendererFactory::create($this->config);
            } catch (Exception $e) {
                // Fallback to original client-side rendering
                $this->config['__confluence__mermaid'] = true;
                $renderer = null;
            }

            if ($renderer !== null) {
                // Extract Mermaid blocks from HTML content
                $diagrams = $extractor->extractFromContent($content);

                if (!empty($diagrams)) {
                    foreach ($diagrams as $diagramIndex => $diagram) {
                        try {
                            // Render diagram to image
                            // Get parent directory from the current file
                            $parent = $this->file->getParent();
                            if ($parent === null) {
                                // Fallback: get root directory from config
                                $parent = $this->config->getTree();
                            }

                            $attachment = $renderer->render(
                                $diagram['code'],
                                $diagram['id'],
                                $this->config,
                                $parent
                            );

                            // Add as attachment
                            $this->attachments[$attachment->getUri()] = [
                                'filename' => $attachment->getUri(),
                                'content' => $attachment->getContent(),
                            ];

                            // Replace original <pre class="mermaid"> block with image tag
                            // Apply Mermaid-specific size settings
                            $imageAttributes = [];
                            $mermaidWidth = $confluenceConfig->getMermaidImageWidth();
                            $mermaidHeight = $confluenceConfig->getMermaidImageHeight();
                            if ($mermaidWidth !== null) {
                                $imageAttributes['width'] = (string) $mermaidWidth;
                            }
                            if ($mermaidHeight !== null) {
                                $imageAttributes['height'] = (string) $mermaidHeight;
                            }
                            $imageTag = $this->createImageTag($attachment->getUri(), $imageAttributes);
                            $content = str_replace($diagram['original'], $imageTag, $content);
                        } catch (Exception $e) {
                            // Log error and keep original code block
                            // Fallback to original rendering for this diagram
                            error_log("Mermaid rendering failed for diagram {$diagram['id']}: " . $e->getMessage());
                            $this->config['__confluence__mermaid'] = true;
                        }
                    }
                }
            }
        }

        // Embed images
        // We do it after generation so we can catch the images that were in html already
        $content = (new EmbedImages($this->config->getTree()))
            ->embed(
                $content,
                $this->file,
                function ($src, array $attributes, Entry $file) {
                    // Add the attachment for later upload
                    if ($file instanceof Raw) {
                        $filename = basename($file->getPath());
                        $this->attachments[$filename] = ['filename' => $filename, 'file' => $file];
                    } elseif ($file instanceof ComputedRaw) {
                        $filename = $file->getUri();
                        $this->attachments[$filename] = ['filename' => $filename, 'content' => $file->getContent()];
                    } else {
                        throw new Exception("Cannot embed image as we don't understand its type.");
                    }

                    return $this->createImageTag($filename, $attributes);
                }
            );

        if (str_contains($content, '<details')) {
            $detailsToExpand = new DetailsToExpand();
            $content = $detailsToExpand->convert($content);
        }

        $intro = '';
        if ($this->config->getConfluenceConfiguration()->hasHeader()) {
            $intro = '<ac:structured-macro ac:name="info"><ac:rich-text-body>' . $this->config->getConfluenceConfiguration()->getHeader() . '</ac:rich-text-body></ac:structured-macro>';
        }

        return $intro . $content;
    }

    /**
     * Create an image tag for the specified filename.
     *
     * @param string $filename
     * @param array  $attributes
     *
     * @return string
     */
    private function createImageTag($filename, $attributes)
    {
        $img = '';

        foreach ($attributes as $name => $value) {
            if ($name == 'style') {
                $re = '/float:\s*?(left|right);?/';
                if (preg_match($re, $value, $matches)) {
                    $img .= ' ac:align="' . $matches[1] . '"';
                    $value = preg_replace($re, '', $value, 1);
                }
            }

            $img .= ' ac:' . $name . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8', false) . '"';
        }

        return '<ac:image' . $img . "><ri:attachment ri:filename=\"{$filename}\" /></ac:image>";
    }
}
