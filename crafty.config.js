module.exports = {
    browsers:
        "> 0.25%, Edge >= 15, Safari >= 10, iOS >= 10, Chrome >= 56, Firefox >= 51, IE >= 11, not op_mini all",
    presets: [
        "@swissquote/crafty-preset-babel",
        "@swissquote/crafty-runner-rollup",
        "@swissquote/crafty-preset-postcss",
        "@swissquote/crafty-runner-gulp"
    ],
    destination_css: ".",
    destination_js: ".",
    stylelint_pattern: [
        "src/css/**/*.scss",
        "!*.min.css",
        "!**/vendor/**/*.scss"
    ],
    stylelint: {
        rules: {
            "swissquote/no-type-outside-scope": null,
            "plugin/no-unsupported-browser-features": null
        }
    },
    js: {
        search: {
            runner: "rollup",
            source: "src/js/search/index.js",
            destination: "_libraries/search.min.js"
        },
        theme_daux: {
            runner: "rollup",
            source: "src/js/theme_daux/index.js",
            destination: "themes/daux/js/daux.min.js"
        }
    },
    css: {
        theme_blue: {
            source: "src/css/theme_daux/theme-blue.scss",
            destination: "themes/daux/css/theme-blue.min.css"
        },
        theme_green: {
            source: "src/css/theme_daux/theme-green.scss",
            destination: "themes/daux/css/theme-green.min.css"
        },
        theme_navy: {
            source: "src/css/theme_daux/theme-navy.scss",
            destination: "themes/daux/css/theme-navy.min.css"
        },
        theme_red: {
            source: "src/css/theme_daux/theme-red.scss",
            destination: "themes/daux/css/theme-red.min.css"
        },
        daux_singlepage: {
            source: "src/css/theme_daux_singlepage/main.scss",
            destination: "themes/daux_singlepage/css/main.min.css"
        }
    }
};
