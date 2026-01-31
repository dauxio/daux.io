**Table of contents**

[TOC]

## Configuring the connection

The connection requires three parameters `base_url`, `user` and `pass`. While `user` and `pass` don't really need an explanation, for `base_url` you need to set the path to the server without `rest/api`, this will be added automatically.

```json
{
    "confluence": {
        "base_url": "http://my_confluence_server.com/",
        "user": "my_username",
        "pass": "my_password_or_token"
    }
}
```

If you are using Atlassian.com you might need to use a token instead of a password.
You can create a token by [following this documentation](https://support.atlassian.com/atlassian-account/docs/manage-api-tokens-for-your-atlassian-account/).

## Where to upload

Now that the connection is defined, you need to tell it where you want your documentation to be uploaded.

For that you need an `ancestor_id` or `root_id`; the id of the page that will be the parent of the documentation's homepage or the page that will be the homepage of your documentation respectively.

You can obtain a page's id by checking the links of the actions on the page (page information, show source code, export, etc) : the ID corresponds to the query parameter `pageId`.

```json
{
    "confluence": {
        "ancestor_id": 50370632
    }
}
```

If using an `ancestor_id` you may wish to create the page automatically if it doesn't exist.
To do so, set `create_root_if_missing` to true.

```json
{
    "confluence": {
        "ancestor_id": 50370632,
        "create_root_if_missing": true
    }
}
```

## Prefix

Because confluence can't have two pages with the same name in a space, I recommend you define a prefix for your pages.

```json
{
    "confluence": { "prefix": "DAUX -" }
}
```

## Update threshold

To make the upload quicker, we try to determine if a page changed or not, first with a strict comparison and if it's not completely identical, we compute the difference.

```json
{
    "confluence": { "update_threshold": 1 }
}
```

If you set `update_threshold` to 1, it will upload only if the page has more than 1% difference with the previous one.

By default the threshold is 2%.

Setting the value to `0` disables the feature altogether.

## Delete old pages

When a page is renamed, there is no way to tell it was renamed, so when uploading to Confluence, the page will be uploaded and the old page will stay here.

By default, it will inform you that some pages aren't needed anymore and you can delete them by hand.

```json
{
    "confluence": { "delete": true }
}
```

By setting `delete` to `true` (or running `daux` with the `--delete` flag) you tell the generator that it can safely delete the pages.

## Information message

When you create your page. there is no indication that the upload process will override the content of the pages.

It happens sometimes that users edit the pages to add / fix an information.

You can add a text in a "information" macro on top of the document by setting the following configuration :

```json
{
    "confluence": {
        "header": "These pages are updated automatically, your changes will be overriden."
    }
}
```

It will look like this :

![Info macro](info_macro.png)

## Pre-rendering Mermaid Diagrams

By default, daux uses client-side JavaScript to render Mermaid diagrams in Confluence. However, this approach may fail due to Content Security Policy restrictions in Confluence Cloud.

To ensure reliable diagram rendering, you can enable pre-rendering, which converts Mermaid diagrams to images before publishing:

```json
{
    "confluence": {
        "pre_render_mermaid": true,
        "mermaid_cli_path": "mmdc",
        "mermaid_image_format": "svg"
    }
}
```

### Options

- **pre_render_mermaid** (boolean, default: `false`): Enable pre-rendering of Mermaid diagrams
- **mermaid_cli_path** (string, default: `mmdc`): Path to mermaid-cli executable. Can be `mmdc`, `npx @mermaid-js/mermaid-cli`, or full path
- **mermaid_image_format** (string, default: `svg`): Output format. Options: `svg` (recommended) or `png`
- **mermaid_image_width** (integer, optional): Width in pixels for rendered Mermaid diagrams in Confluence. If not set, Confluence will use the image's natural width
- **mermaid_image_height** (integer, optional): Height in pixels for rendered Mermaid diagrams in Confluence. If not set, Confluence will use the image's natural height

### Image Sizing

To control the size of Mermaid diagrams in Confluence, you can specify width and/or height:

```json
{
    "confluence": {
        "pre_render_mermaid": true,
        "mermaid_cli_path": "mmdc",
        "mermaid_image_format": "svg",
        "mermaid_image_width": 800,
        "mermaid_image_height": 600
    }
}
```

**Note**: 
- If only width is specified, height will scale proportionally
- If only height is specified, width will scale proportionally
- If both are specified, the image may be stretched or compressed
- For SVG format, it's recommended to set only width and let height scale automatically

### Prerequisites

You must have `@mermaid-js/mermaid-cli` installed:

```bash
npm install -g @mermaid-js/mermaid-cli
```

Or use npx (no installation required):

```json
{
    "confluence": {
        "pre_render_mermaid": true,
        "mermaid_cli_path": "npx @mermaid-js/mermaid-cli"
    }
}
```

### Troubleshooting

If diagrams fail to render:

1. Verify mermaid-cli is installed: `mmdc --version`
2. Check the path in `mermaid_cli_path` configuration
3. Ensure Node.js is available in your PATH: `node --version`
4. Check file permissions for temporary directory
5. Review error messages in console output
6. Try using npx: Set `mermaid_cli_path` to `npx @mermaid-js/mermaid-cli`
