Adding a table of contents becomes very easy with Daux.io

## Manual

Add `[TOC]` anywhere in your document and it will be replaced by a table of contents.

You can add it more than once in a page.

## Automatic

> Works only for html mode

A table of contents can be added automatically to all pages.

If `[TOC]` isn't present it will add it at the beginning of the page.

You can enable this feature in your configuration

```json
{
    "html": {
        "auto_toc": true
    }
}
```

## Customizing

The depth of the table of contents, its list style and other details can be changed with the `table_of_contents` option, read about it in [the configuration](../05_Configuration/_index.md).
