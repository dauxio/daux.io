**Daux.io** is an documentation generator that uses a simple folder structure and Markdown files to create custom documentation on the fly. It helps you create great looking documentation in a developer friendly way.

[TOC]

## Features

### For Authors

-   [Auto Generated Navigation / Page sorting](01_Features/Navigation_and_Sorting.md)
-   [Internal documentation links](01_Features/Internal_links.md)
-   [CommonMark compliant](01_Features/CommonMark_compliant.md)
-   [Auto created homepage/landing page](01_Features/Landing_page.md)
-   [Multiple Output Formats](01_Features/Multiple_Output_Formats.md)
-   [Multiple Languages Support](01_Features/Multilanguage.md)
-   [No Build Step](01_Features/Live_mode.md)
-   [Static Output Generation](01_Features/Static_Site_Generation.md)
-   [Table of Contents](01_Features/Table_of_contents.md)

### For Developers

-   [Auto Syntax Highlighting](01_Features/Auto_Syntax_Highlight.md)
-   [Math, Diagrams and Flowcharts](01_Features/Math_Diagrams_Flowcharts.md)
-   [Extend Daux.io with Processors](01_For_Developers/Creating_a_Processor.md)
-   Full access to the internal API to create new pages programatically
-   Work with pages metadata

### For Marketing

-   100% Mobile Responsive
-   4 Built-In Themes or roll your own
-   Functional, Flat Design Style
-   Shareable/Linkable SEO Friendly URLs
-   Supports Google Analytics and Piwik Analytics

## Demos

This is a list of sites using Daux.io:

-   With a custom theme:
    -   [Crafty](https://swissquote.github.io/crafty)
    -   [Pixolution flow](https://docs.pixolution.org) \* [Soisy](https://doc.soisy.it/)
    -   [Vulkan Tutorial](https://vulkan-tutorial.com)
    -   [3Q](https://docs.3q.video/)
-   With the default Theme
    -   [Daux.io](https://daux.io/)
        _ [DoctrineWatcher](https://dsentker.github.io/WatcherDocumentation/)
        _ [DrupalGap](http://docs.drupalgap.org/8/)
    -   [ICADMIN: An admin panel powered by CodeIgniter.](http://istocode.com/shared/ic-admin/)
    -   [Munee: Standalone PHP 5.3 Asset Optimisation & Manipulation](http://mun.ee)
    -   [Nuntius: A PHP framework for bots](https://roysegall.github.io/nuntius-bot/)

Do you use Daux.io? Send us a pull request or open an [issue](https://github.com/dauxio/daux.io/issues) and I will add you to the list.

## Getting Started

### Install

#### PHP and Composer

If you have PHP and Composer installed, you can install the dependency

```bash
composer global require daux/daux.io

# Next to your `docs` folder, run
daux generate
```

You can then use the `daux` command line to generate your documentation.

If the command isn't found, ensure your `$PATH` contains `~/.composer/vendor/bin` or `~/.config/composer/vendor/bin`.

#### Docker

Or if you wish to use Docker, the start of the command will be :

```bash
docker run --rm -it -p 8085:8085 -w /build -v "$PWD":/build daux/daux.io daux
```

Any parameter valid in the PHP version is valid in the Docker version

### Writing pages

Creating new pages is very easy:

1. Create a markdown file (`*.md` or `*.markdown`)
2. Start writing

By default, the generator will look for folders in the `docs` folder.
Add your folders inside the `docs` folder. This project contains some example folders and files to get you started.

You can nest folders any number of levels to get the exact structure you want.
The folder structure will be converted to the nested navigation.

You must use underscores instead of spaces. Here are some example file names and what they will be converted to:

**Good:**

-   01_Getting_Started.md = Getting Started
-   API_Calls.md = API Calls
-   200_Something_Else-Cool.md = Something Else-Cool
-   \_5_Ways_to_Be_Happy.md = 5 Ways To Be Happy

**Bad:**

-   File Name With Space.md = FAIL

### See your pages

Now you can see your pages. you have two options for that : serve them directly, or generate to various formats.

We recommend the first one while you write your documentation, you get a much quicker feedback while writing.

#### Serving files

You can use PHP's embedded web server by running the following command in the root of your documentation

```
./serve
```

Upload your files to an apache / nginx server and see your documentation

[More informations here](01_Features/Live_mode.md)

#### Export to other formats

Daux.io is extendable and comes by default with three export formats:

-   Export to HTML, same as the website, but can be hosted without PHP.
-   Export all documentation in a single HTML page
-   Upload to your Atlassian Confluence server.

[See a detailed feature comparison matrix](01_Features/Multiple_Output_Formats.md)

To export, run the `daux` command and your documentation will be generated in `static` (you can change the destination with the `--destination` option)

[See here for all options](01_Features/Static_Site_Generation.md)

## Configuration

Now that you got the basics, you can also [see what you can configure](05_Configuration/_index.md)

### Server Configuration

We are using `.mjs` file types. Which not every web server properly understands and serves.

You might see an error like `Failed to load module script: Expected a JavaScript module script but the server responded with a MIME type of "application/octet-stream". Strict MIME type checking is enforced for module scripts per HTML spec.`

#### Apache

If you are using a version under 2.5.1 of Apache HTTPd.

Add the following line within `.htaccess`, vhost or server configuration.

```
AddType application/javascript .mjs
```

#### nginx

```
  include mime.types;
  types {
      application/javascript js mjs;
  }
```

## PHP Requirements

Daux.io is compatible with the [officially supported](https://www.php.net/supported-versions.php) PHP versions; 8.1.0 and up.

### Extensions

Daux.io needs the following PHP extensions to work : `php-mbstring` and `php-xml`.

If you use non-english characters in your page names, it is recommended to install the `php-intl` extension as well.

## Support

If you need help using Daux.io, or have found a bug, please create an issue on the <a href="https://github.com/dauxio/daux.io/issues" target="_blank">GitHub repo</a>.
