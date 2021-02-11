With the help of [highlight.js](https://highlightjs.org/) We can highlight more than 150 languages.

To be precise, we support all languages supported by highlight.js `10.5.0`.

> Note that fenced code blocks with a hardcoded language are rendered at build time 
> and don't need the library to be loaded on the client side.

Here is a quick example :

**HTML (with inline css and javascript)**

    <!DOCTYPE html>
    <title>Title</title>

    <style>body {width: 500px;}</style>

    <script type="application/javascript">
      function $init() {return true;}
    </script>

    <body>
      <p checked class="title" id='title'>Title</p>
      <!-- here goes the rest of the page -->
    </body>

[See more examples of supported languages](../02_Examples/Code_Highlighting.md)
