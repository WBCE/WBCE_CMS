jQuery Plugin `rearrangeByLocalStorage`
=======================================

The jQuery Plugin `rearrangeByLocalStorage` allows you to rearrange elements based on click counts stored in the browser's localStorage.

## Installation

Include jQuery library and the plugin file in your HTML document:

```html
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="jquery.rearrangeByLocalStorage.js"></script>
```

### Install in WBCE backend, module, tool
The plugin is available in the jQueryPlugins folder in `theme_fallbacks` directory.
You can call it using the jquery insert method.
```
$.insert(WB_URL + '/templates/theme_fallbacks/js/rearangeByLocalStorage/rearangeByLocalStorage.js');
```

## Usage

To use the plugin, follow these steps:

1. Add the necessary HTML elements to your page. For example:

```html
<div class="subpages">
  <div class="subpage" id="tool1">Tool 1</div>
  <div class="subpage" id="tool2">Tool 2</div>
  <div class="subpage" id="tool3">Tool 3</div>
</div>
```

2. Initialize the plugin by using the class of the elements you want to rearrange by calling the `rearrangeByLocalStorage` function. For example:

```javascript
$(document).ready(function() {
  $('.subpage').rearrangeByLocalStorage();
});
```

3. Now, when a user clicks on any of the `.subpage` elements, the click count will be stored in localStorage, and the elements will be rearranged based on the click counts.

## Options

The plugin accepts the following options:

- `storageKey`: The key to use for storing the click count data in localStorage. Default: `'AdminTools'`.
- `parentClass`: The class of the container element for the subelements. Default: `'subpages'`.

You can customize the options by passing an object when calling the `rearrangeByLocalStorage` function. For example:

```javascript
$('.subpage').rearrangeByLocalStorage({
  storageKey: 'MyClickCounts',
  subpagesClass: 'my-subpages'
});
```

## License

This plugin is licensed under the MIT License.

## Author
Written by Christian M. Stefan (Stefek) for the WBCE CMS Project