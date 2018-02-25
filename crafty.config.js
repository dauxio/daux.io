
module.exports = {
    presets: [
      "@swissquote/crafty-preset-postcss",
      "@swissquote/crafty-runner-gulp"
    ],
    destination_css: "themes",
    css: {
      "theme_blue": {
        source: "themes/daux/scss/theme-blue.scss",
        destination: "daux/css/theme-blue.min.css"
      },
      "theme_green": {
        source: "themes/daux/scss/theme-green.scss",
        destination: "daux/css/theme-green.min.css"
      },
      "theme_navy": {
        source: "themes/daux/scss/theme-navy.scss",
        destination: "daux/css/theme-navy.min.css"
      },
      "theme_red": {
        source: "themes/daux/scss/theme-red.scss",
        destination: "daux/css/theme-red.min.css"
      },
      "daux_singlepage": {
        source: "themes/daux_singlepage/scss/main.scss",
        destination: "daux_singlepage/css/main.min.css"
      }
    }
  };
