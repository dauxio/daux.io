---
description: With Front Matter you can customize your pages even further.
keywords: "Front Matter, Customize, Title, Description, Keywords, Author"
author: Daux.io Team
---

To customize your pages even further, you can add a Front Matter to your files.

Front Matter is a block you add at the top of your file and looks like this:

    ---
    title: Hallo Welt
    keywords: "Hallo, Hello, Welt, World, Erde, Earth"
    author: German Daux.io Team
    date: 12th December 1984
    ---

## Changing the title

If your file is named "Hello_World_de.md" and your front matter is the one displayed above, you will get a page named "Hallo Welt"

## Search Engine Optimization

For a better **SEO** experience you can change the `description`, `keywords` and `author` meta tags.

## For Developers

You can then access this information in each `Content` with `$content->getAttributes()` or with `$page['attributes']` in a template.
