import * as preact from "preact";
import FlexSearch from "flexsearch";

import Search from "./Search";

/** @jsx preact.h */

const originalTitle = document.title;

function getURLP(name) {
    const elements = new RegExp(`[?|&]${name}=([^&;]+?)(&|#|;|$)`).exec(
        window.location.search
    );

    return (
        decodeURIComponent(
            ((elements && elements[1]) || "").replace(/\+/g, "%20")
        ) || null
    );
}

class SearchEngine {
    constructor(options) {
        this.settings = {
            field: document.getElementById("search_input"),
            show: 10,
            showURL: true,
            showTitleCount: true,
            minimumLength: 3,
            descriptiveWords: 25,
            highlightTerms: true,
            highlightEveryTerm: false,
            contentLocation: "daux_search_index.json",
            ...options
        };

        this.searchIndex = {
            pages: []
        };
    }

    loadData() {
        if (!this.loadingPromise) {
            this.loadingPromise = fetch(
                this.settings.base_url + this.settings.contentLocation
            )
                .then(data => data.json())
                .then(json => {
                    this.searchIndex = new FlexSearch({
                        doc: {
                            id: "url",
                            field: ["title", "text", "tags"]
                        }
                    });

                    let pages = json.pages;

                    // Only keep the pages related to the current language
                    if (window.searchLanguage) {
                        const pagePrefix = `${window.searchLanguage}/`;
                        pages = pages.filter(
                            item => item.url.indexOf(pagePrefix) === 0
                        );
                    }

                    this.searchIndex.add(pages);
                });
        }

        return this.loadingPromise;
    }

    run() {
        if (getURLP("q")) {
            this.settings.field.value = getURLP("q");

            this.loadData().then(() => {
                this.displaySearch();
            });
        }

        this.settings.field.addEventListener("keyup", event => {
            // Start loading index once the user types text in the field, not before
            this.loadData();

            if (parseInt(event.keyCode, 10) === 13) {
                this.loadData().then(() => {
                    this.displaySearch();
                });
            }
        });
    }

    keyUpHandler = e => {
        if (e.which === 27) {
            //escape
            this.handleClose();
        }
    };

    handleClose = () => {
        document.title = originalTitle;

        document.removeEventListener("keyup", this.keyUpHandler);

        document.body.classList.remove("with-search");
        preact.render(null, this.resultContainer);
        this.resultContainer = null;
    };

    displaySearch() {
        if (!this.resultContainer) {
            this.resultContainer = document.createElement("div");
            document.body.appendChild(this.resultContainer);
        }

        document.addEventListener("keyup", this.keyUpHandler);

        preact.render(
            <Search
                onSearch={term => this.searchIndex.search(term)}
                onClose={this.handleClose}
                onTitleChange={title => {
                    document.title = `${title} ${originalTitle}`;
                }}
                settings={this.settings}
            />,
            this.resultContainer
        );

        document.body.classList.add("with-search");
        document.body.scrollTop = 0;
    }
}

// Main containers

function search(options) {
    const instance = new SearchEngine(options);
    instance.run();
}

// Declare globally
window.search = search;
