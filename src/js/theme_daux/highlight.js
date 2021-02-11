import { ready, loadJS, loadCSS } from "./utils";

ready(() => {
    const codeBlocks = document.querySelectorAll(
        "pre > code:not(.hljs, .katex)"
    );
    if (codeBlocks.length) {
        loadJS(`${window.base_url}daux_libraries/highlight.pack.js`, () => {
            [].forEach.call(codeBlocks, window.hljs.highlightBlock);
        });
    }
});

ready(() => {
    const codeBlocks = document.querySelectorAll("pre > code.katex");
    if (codeBlocks.length) {
        loadCSS(`${window.base_url}daux_libraries/katex.min.css`);

        loadJS(`${window.base_url}daux_libraries/katex.min.js`, () => {
            [].forEach.call(codeBlocks, (/** @type {HTMLElement} */ e) => {
                const content = e.innerHTML;
                const p = document.createElement("p");
                const span = document.createElement("span");
                p.className = "katex-display";
                p.appendChild(span);

                const pre = e.parentElement;
                pre.parentElement.insertBefore(p, pre);
                pre.parentElement.removeChild(pre);

                window.katex.render(content, span, {
                    throwOnError: false
                });
            });
        });
    }
});

ready(() => {
    const mermaidBlocks = document.querySelectorAll("div.mermaid");
    if (mermaidBlocks.length) {
        loadJS(`${window.base_url}daux_libraries/mermaid.min.js`, () => {
            window.mermaid.initialize({ startOnLoad: true });
        });
    }
});
