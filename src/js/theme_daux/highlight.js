const codeBlocks = document.querySelectorAll("pre > code:not(.hljs)");
if (codeBlocks.length) {
    const head = document.getElementsByTagName("head")[0],
        script = document.createElement("script");
    script.type = "text/javascript";
    script.async = true;
    script.src = `${window.base_url}daux_libraries/highlight.pack.js`;
    script.onload = function(src) {
        [].forEach.call(codeBlocks, window.hljs.highlightBlock);
    };
    head.appendChild(script);
}
