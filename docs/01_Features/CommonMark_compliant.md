As we support CommonMark, a broad range of markdown features is available to you.

Many of the features shown below were known as Github Flavored Markdown.

## We all like making lists

The above header should be an H2 tag. Now, for a list of fruits:

-   Red Apples
-   Purple Grapes
-   Green Kiwifruits

Let's get crazy:

1.  This is a list item with two paragraphs. Lorem ipsum dolor
    sit amet, consectetuer adipiscing elit. Aliquam hendrerit
    mi posuere lectus.

    Vestibulum enim wisi, viverra nec, fringilla in, laoreet
    vitae, risus. Donec sit amet nisl. Aliquam semper ipsum
    sit amet velit.

2.  Suspendisse id sem consectetuer libero luctus adipiscing.

What about some code **in** a list? That's insane, right?

1.  In Ruby you can map like this:

        ['a', 'b'].map { |x| x.uppercase }

2.  In Rails, you can do a shortcut:

        ['a', 'b'].map(&:uppercase)

Some people seem to like definition lists

## I am a robot

Maybe you want to print `robot` to the console 1000 times. Why not?

    def robot_invasion
      puts("robot " * 1000)
    end

You see, that was formatted as code because it's been indented by four spaces.

How about we throw some angle braces and ampersands in there?

    <div class="footer">
        &copy; 2004 Foo Corporation
    </div>

## Set in stone

Preformatted blocks are useful for ASCII art:

<pre>
             ,-.
    ,     ,-.   ,-.
   / \   (   )-(   )
   \ |  ,.>-(   )-&lt;
    \|,' (   )-(   )
     Y ___`-'   `-'
     |/__/   `-'
     |
     |
     |    -hrr-
  ___|_____________
</pre>

## Playing the blame game

If you need to blame someone, the best way to do so is by quoting them:

> I, at any rate, am convinced that He does not throw dice.

Or perhaps someone a little less eloquent:

> I wish you'd have given me this written question ahead of time so I
> could plan for it... I'm sure something will pop into my head here in
> the midst of this press conference, with all the pressure of trying to
> come up with answer, but it hadn't yet...
>
> I don't want to sound like
> I have made no mistakes. I'm confident I have. I just haven't - you
> just put me under the spot here, and maybe I'm not as quick on my feet
> as I should be in coming up with one.

## Table for two

| ID  |        Name        |              Rank |
| --- | :----------------: | ----------------: |
| 1   | Tom Preston-Werner |           Awesome |
| 2   |  Albert Einstein   | Nearly as awesome |

## Crazy linking action

I get 10 times more traffic from [Google][1] than from
[Yahoo][2] or [MSN][3].

[1]: http://google.com/ "Google"
[2]: http://search.yahoo.com/ "Yahoo Search"
[3]: http://search.msn.com/ "MSN Search"

## Images

Here's an image from a file.

![This is an image](sampleimage.png)

It's also possible to use inline images.

![This is an image](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAMAAAD04JH5AAAAbFBMVEX///8AAAAHBwfz8/P39/fb29uoqKh3d3cXFxdnZ2c4ODgLCwsvLy+QkJDV1dWVlZW9vb0eHh6cnJxRUVHExMTi4uLNzc3s7OxGRkZZWVkzMzOJiYliYmJwcHATExOysrIlJSU8PDx+fn7m5uZn8t+XAAADKElEQVR4nO2b25aqMAyGIyAHURRUdDzP+P7vuGE0KThs29qW3LRXulZJPiBt85cGgFoYZ7M0mDhuQTrL4hD+tmmWu/YtWp5NX9xHifNb77cgiXq3Px/XfdvmnYdQp+P7n0zSmu6fxX9D8HwGEcPzf7T5Iw4SLv+TSfL7AkT8zxfF0Ai12sJiIZ540L6EjP4tXTvHtqR7PjVEOP8Eq7H8A6yQIA8hRpjR7r9tS/Qa0xuYj+kfAOMgg9nz12JcgMXT7QxwEirGBSieblPAcHA+/votxNAHjIZx/YPw6wE8gAfwAB7AA3gAD+ABnADUm0jWxSXAqmwkjyzNdwhQPxLtig3g8DBYcgFUaHHHAxCi1Arex6EzANpvWb/v5wqA9lsCidh0BbBGe2dJR0cAKzR3eR+CzgBo/0m63+IGgHZe5PstTgB2F7Qm3/ByAnBWHIKuAAoagq9fA0YC2KOtRKGzA4ArmkpV9pvsA0Q3NCVZiF0BHNHSQam7dYA7ffPa8ACc0NCXWn/bADXaybc8AN9o50fxAssAtOlfSvNxJwBRiWZi1UvsAvyglW/lS6wCbGkI1vLOLgC+0MhJ/RqbABsagncegAPaOGpcZBGApJDyELQLQFJIfQh+AFBU8f/ybJJCex3/egBRG+b58DKvLIVMAM5vYoykUKblXwsgwnlmINdTl0IGAFPsMzDRkBTS/fKqAxCKEwbrl5GmIYUMAMRU24R6L+PVkUImALuDIDh03zUdflCQQiYAEO4FwU3kXFpSyAgAorUgKGnAa0khM4BO1tuMuOeqryeFTAG6p33y35DTlELGAEL6NO88Bm0pZA4AVefEXaUthSwAwLVDcNSVQjYAYDVw6FFVClkBgPryB0BVCtkBgGn54l8rD7MAANtbH+D6mX+DnLC7MOjmYVYAeguDZh5mB6C7MMi2pN0AiGX4k0XACgAuDJ8sAnYAYNFOihpa1DoA3Kul0QE8YwDT5gE8gAfwAB7AA3gAD+AByC97gQN7iQd7kQt7mQ97oRN7qRd7sRt/uR97wSN/ySd70St/2S9/4TN/6Xf7EE5jFr+fhr7x8JT//wOsXyOmYFM3vQAAAABJRU5ErkJggg==)

Note: to use images on a landing page (index.md), prefix the image URL with the name of the directory it appears in, omitting the numerical prefix used to order the sections. For example in this section, to display this image on the landing page (index.md), the URL for the image would be "Features/sampleimage.png" to display the same image.

_View the [source of this content](https://github.com/dauxio/daux.io/blob/master/docs/01_Features/CommonMark_compliant.md)._
