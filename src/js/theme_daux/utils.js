/* eslint-disable @swissquote/swissquote/import/prefer-default-export */

export function ready(fn) {
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", fn);
    } else {
        fn();
    }
}
