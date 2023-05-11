const path = require("path");

module.exports = {
    mode: "production",
    entry: {
        main: "./src/js/theme_daux/index.js",
    },
    output: {
        path: path.resolve(__dirname, "themes/daux/js"),
        chunkFilename: "[name].mjs",
        chunkLoading: "import",
        chunkFormat: "module",
    },
    experiments: {
        outputModule: true,
    },
};
