
module.exports = {
    browsers: "> 0.25%, Edge >= 15, Safari >= 10, iOS >= 10, Chrome >= 56, Firefox >= 51, IE >= 11, not op_mini all",
    presets: [
      "@swissquote/crafty-preset-postcss",
      "@swissquote/crafty-runner-gulp"
    ],
    destination_css: "themes",
    stylelint_pattern: [
      "themes/daux/scss/**/*.scss",
      "themes/daux_singlepage/scss/**/*.scss",
      "!*.min.css",
      "!**/vendor/**/*.scss"
    ],
    stylelint: {
      rules: {
        "swissquote/no-type-outside-scope": null,
        "plugin/no-unsupported-browser-features": null
      }
    },
    css: {
      "theme_blue": {
        source: "themes/daux/scss/theme-blue.scss",
        destination: "daux/css/theme-blue.min.css",
        watch: ["themes/daux/scss/**"]
      },
      "theme_green": {
        source: "themes/daux/scss/theme-green.scss",
        destination: "daux/css/theme-green.min.css",
        watch: ["themes/daux/scss/**"]
      },
      "theme_navy": {
        source: "themes/daux/scss/theme-navy.scss",
        destination: "daux/css/theme-navy.min.css",
        watch: ["themes/daux/scss/**"]
      },
      "theme_red": {
        source: "themes/daux/scss/theme-red.scss",
        destination: "daux/css/theme-red.min.css",
        watch: ["themes/daux/scss/**"]
      },
      "daux_singlepage": {
        source: "themes/daux_singlepage/scss/main.scss",
        destination: "daux_singlepage/css/main.min.css",
        watch: ["themes/daux_singlepage/scss/**"]
      }
    }
  };
