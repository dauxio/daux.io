To customize the look and feel of your documentation, you can create a `config.json` file in the of the `/docs` folder. The `config.json` file is a JSON object that you can use to change some of the basic settings of the documentation.

**Table of contents**

[TOC]

### Title

Change the title bar in the docs

```json
{
    "title": "Daux.io"
}
```

### Tagline

Change the tagline bar in the docs

```json
{
    "tagline": "The Easiest Way To Document Your Project"
}
```

### Author

Change the documentation's author

```json
{
    "author": "Stéphane Goetz"
}
```

### Image

An image to show on the landing page. A relative path from the documentation root.

```json
{
    "image": "image.png"
}
```

### Format

Change the output format. It is recommended you set only formats that support the live mode as this will also
be read by the integrated web server. And you set the other formats (like confluence) only by command line

```json
{
    "format": "html"
}
```

- **html** with [its options](./Html_export.md)
- **confluence** with [its options](./Confluence_upload.md)

Available formats are **HTML** and **Confluence**

### Ignore

Set custom files and entire folders to ignore within your `/docs` folder. For files make sure to include the file extension in the name. For both files and folders, names are case-sensitive.

```json
{
    "ignore": {
        "files": ["Work_In_Progress.md"],
        "folders": ["99_Not_Ready"]
    }
}
```

### Timezone

If your server does not have a default timezone set in php.ini, it may return errors when it tries to generate the last modified date/time for docs. To fix these errors, specify a timezone in your config file. Valid options are available in the [PHP Manual](http://php.net/manual/en/timezones.php).

```json
{
    "timezone": "America/Los_Angeles"
}
```

### Multi-language

Enables multi-language support which needs separate directories for each language in `docs/` folder.

```json
{
    "languages": { "en": "English", "de": "German" }
}
```

Directory structure:

```
├── docs/
│   ├── _index.md
│   ├── en
│   │   ├── 00_Getting_Started.md
│   │   ├── 01_Examples
│   │   │   ├── 01_CommonMark_compliant.md
│   │   │   ├── 05_Code_Highlighting.md
│   │   ├── 05_More_Examples
│   │   │   ├── Hello_World.md
│   │   │   ├── 05_Code_Highlighting.md
│   ├── de
│   │   ├── 00_Getting_Started.md
│   │   ├── 01_Examples
│   │   │   ├── 01_CommonMark_compliant.md
│   │   │   ├── 05_Code_Highlighting.md
│   │   ├── 05_More_Examples
│   │   │   ├── Hello_World.md
│   │   │   ├── 05_Code_Highlighting.md
```

### Language

You can change the default language with the `language` option.

```json
{
    "language": "fr"
}
```

This will change the language of the text within Daux.io and also the `<html lang>` attribute.

You can override the translations with the `strings` option.
A string that isn't found will fall back to english.

```json
{
    "strings": {
        "fr": {
            "CodeBlocks_show": "Afficher le code",
            "Search_placeholder": "Rechercher...",
            "Link_previous": "Précédent",
            "Link_next": "Suivant",
            "Edit_on": "Editer sur :name:",
            "View_on_github": "Voir sur GitHub",
            "View_documentation": "Voir la Documentation"
        }
    }
}
```

### Processor

You can set the processor in the documentation or as an option to the command line. If you need it when running the server, you should add it to the configuration.

More information on how to create a Processor can be found [here](!For_Developers/Creating_a_Processor).

```json
{
    "processor": "MyProcessor"
}
```

### Heading permalinks

Every heading gets an anchor link so it can be linked to directly. You can change how that anchor is rendered.

```json
{
    "heading_permalink": {
        "symbol": "¶"
    }
}
```

Any option of the league/commonmark [Heading Permalink extension](https://commonmark.thephpleague.com/2.x/extensions/heading-permalinks/) can be set here. Options you don't set keep Daux's defaults, which differ from CommonMark's:

| Option            | Daux default | CommonMark default  |
| ----------------- | ------------ | ------------------- |
| `html_class`      | `Permalink`  | `heading-permalink` |
| `symbol`          | `#`          | `¶`                 |
| `fragment_prefix` | _(empty)_    | `content`           |
| `id_prefix`       | _(empty)_    | `content`           |

Changing `html_class` will break the styling that comes with the bundled themes.

These options apply to the **html** and **html-file** formats only, the **confluence** format ignores them.

### Table of contents

Change how the table of contents added by `[TOC]` is rendered. Read more about the feature [here](../01_Features/Table_of_contents.md).

```json
{
    "table_of_contents": {
        "max_heading_level": 3,
        "style": "ordered"
    }
}
```

Any option of the league/commonmark [Table of Contents extension](https://commonmark.thephpleague.com/2.x/extensions/table-of-contents/) can be set here. Options you don't set keep Daux's defaults, which differ from CommonMark's:

| Option        | Daux default      | CommonMark default  |
| ------------- | ----------------- | ------------------- |
| `html_class`  | `TableOfContents` | `table-of-contents` |
| `position`    | `placeholder`     | `top`               |
| `placeholder` | `[TOC]`           | `null`              |

Leave `position` and `placeholder` as they are, they are what makes the `[TOC]` marker and the automatic table of contents work. As with permalinks, changing `html_class` will break the styling of the bundled themes.

These options apply to the **html** and **html-file** formats only, the **confluence** format ignores them.
