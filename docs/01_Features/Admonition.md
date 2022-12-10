
## Syntax

Admonitions are created using the following syntax:

```
!!! type "optional explicit title within double quotes"
    Any number of other indented markdown elements.

    This is the second paragraph.
```

Valid values for `type` are: `note`, `info`, `warning`, and `danger`.

Any value other than these will be treated as `note`

## Examples

!!! note "Note"
    A `note` will render with a neutral color.

    * Sugar
    * Eggs
    * Flour

!!! info "Some title"
    `info` renders like this.

!!! warning "WARNING"
    `warning` will warn you.

    > With a blockquote

!!! danger "Danger"
    `danger` is Dangerous

!!! note
    This note has no title !

### Confluence

Confluence upload will convert admonition into [confluence macros](https://confluence.atlassian.com/doc/info-tip-note-and-warning-macros-51872369.html).

* `note` becomes an __Info__ macro
* `info` becomes an __Info__ macro
* `warning` becomes a __Note__ macro
* `danger` becomes a __Warning__ macro

Everything that isn't recognized will be rendered as __Info__
