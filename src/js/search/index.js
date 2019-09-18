import preact from "preact";

import Search from "./Search";

import { getURLP } from "./utils";

/** @jsx preact.h */

const originalTitle = document.title;

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
            contentLocation: "search/search_index.json",
            debug: false,
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
                    this.searchIndex = json;
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
        preact.render("", this.resultContainer, this.renderedElement);
        this.resultContainer = null;
        this.renderedElement = null;
    };

    displaySearch() {
        if (!this.resultContainer) {
            this.resultContainer = document.createElement("div");
            document.body.appendChild(this.resultContainer);
        }

        document.addEventListener("keyup", this.keyUpHandler);

        this.renderedElement = preact.render(
            <Search
                searchIndex={this.searchIndex}
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
