<?php namespace Todaymade\Daux\Format\Confluence\ContentTypes\Markdown;

class ContentType extends \Todaymade\Daux\ContentTypes\Markdown\ContentType
{
    protected function createConverter()
    {
        return new CommonMarkConverter(['daux' => $this->config]);
    }


    protected function addJS()
    {

return <<<EOD
<ac:structured-macro ac:name="html">
   <ac:plain-text-body> <![CDATA[
<script>
function daux_ready(fn) {
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", fn);
    } else {
        fn();
    }
}

function daux_loadJS(url, callback) {
    var head = document.getElementsByTagName("head")[0],
        script = document.createElement("script");
    script.type = "text/javascript";
    script.async = true;
    script.src = url;
    script.onload = callback;
    head.appendChild(script);
}

function daux_loadCSS(url) {
    var head = document.getElementsByTagName("head")[0],
        link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = url;
    head.appendChild(link);
}

daux_ready(function() {
    var codeBlocks = document.querySelectorAll("pre > code.katex");
    if (codeBlocks.length) {
        daux_loadCSS(`https://cdn.jsdelivr.net/npm/katex@0.12.0/dist/katex.min.css`);

        daux_loadJS(`https://cdn.jsdelivr.net/npm/katex@0.12.0/dist/katex.min.js`, function() {
            [].forEach.call(codeBlocks, function(e) {
                var content = e.innerHTML;
                var p = document.createElement("p");
                var span = document.createElement("span");
                p.className = "katex-display";
                p.appendChild(span);

                var pre = e.parentElement;
                pre.parentElement.insertBefore(p, pre);
                pre.parentElement.removeChild(pre);

                window.katex.render(content, span, {
                    throwOnError: false
                });
            });
        });
    }
});

daux_ready(function() {
    var mermaidBlocks = document.querySelectorAll("pre.mermaid");
    if (mermaidBlocks.length) {
        daux_loadJS(`https://cdn.jsdelivr.net/npm/mermaid@8.9.1/dist/mermaid.min.js`, function() {
            [].forEach.call(mermaidBlocks, function(pre) {
                var content = pre.innerHTML;
                var div = document.createElement("div");
                div.className = "mermaid";
                div.innerHTML = content;

                var container = pre.parentElement;
                container.insertBefore(div, pre);
                container.removeChild(pre);
            });

            window.mermaid.initialize({ startOnLoad: true });
        });
    }
});
</script>
]]></ac:plain-text-body>
</ac:structured-macro>

EOD;

    }


    protected function doConversion($raw)
    {
        $content = parent::doConversion($raw);

        if ($this->config->isTruthy('__confluence__tex') || $this->config->isTruthy('__confluence__mermaid')) {
            $content .= $this->addJS();
        }

        // Reset for the next conversion
        $this->config['__confluence__tex'] = false;
        $this->config['__confluence__mermaid'] = false;

        return $content;
    }
}
