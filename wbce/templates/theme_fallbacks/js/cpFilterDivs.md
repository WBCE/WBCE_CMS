jQuery Plugin `cpFilterDivs`
============================

`cpFilterDivs` is a jQuery plugin that filters a set of elements based on the text entered in an input field.

## Installation

To use the `cpFilterDivs` plugin, you need to include it in your HTML file after including jQuery:

```html
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="path/to/cpFilterDivs.js"></script>
```

Make sure to replace `path/to/cpFilterDivs.js` with the actual path to the `cpFilterDivs.js` file.

### Install in WBCE backend, module, tool
The plugin is available in the jQueryPlugins folder in `theme_fallbacks` directory.
You can call it using the jquery insert method.
```
$.insert(WB_URL + '/templates/theme_fallbacks/js/cpFilterDivs/cpFilterDivs.js');
```


## Usage

To use the `cpFilterDivs` plugin, you need to call it on a jQuery object containing the parent element of the elements you want to filter:

```javascript
$(".subpages").cpFilterDivs();
```

This will use the default options for the plugin. You can also pass in an options object to customize the behavior of the plugin:

```javascript
$(".subpages").cpFilterDivs({
    inputField: "#my-input-field",
    filterSelector: ".my-filter-selector",
    textSelector: ".my-text-selector"
});
```

The options object can contain the following properties:
- `inputField`: The selector for the input field used for filtering. Defaults to `#filter-input`.
- `filterSelector`: The selector for the elements to be filtered. Defaults to `.subpage`.
- `textSelector`: The selector for the element containing the text to filter by. Defaults to `.subpage-title a`.

Here's an example of how you can use the `cpFilterDivs` plugin:

```html
<!-- HTML for input field and elements to be filtered -->
<input type="text" id="filter-input">
<div class="subpages">
    <div class="subpage">
        <div class="subpage-title">
            <a href="#">Item 1</a>
        </div>
    </div>
    <div class="subpage">
        <div class="subpage-title">
            <a href="#">Item 2</a>
        </div>
    </div>
    <div class="subpage">
        <div class="subpage-title">
            <a href="#">Item 3</a>
        </div>
    </div>
</div>

<!-- Call cpFilterDivs plugin -->
<script>
    $(document).ready(function() {
        $(".subpages").cpFilterDivs();
    });
</script>
```

This code sets up an input field with the id `filter-input` and three `subpage` divs containing `<a>` elements with text. When you type in the input field, the `subpage` divs will be filtered based on whether their text contains the text entered in the input field.

## License

The `cpFilterDivs` jQuery plugin is licensed under the MIT license.

## Author
Written by Christian M. Stefan (Stefek) for the WBCE CMS Project

