
# OpF Filter: Cache Control

This filter takes care to always load the latest version of CSS or JS file from the server.

**This means:**
> as soon as you make any changes to the files (CSS, JS) the browser will request the new version from the server instead of presenting a version from the browser's cache.

Withoug Cache Control:
````php
../css/file.css
````

Will result in:
````php
../css/file.css?1677324972
````
when Cache Control is turned on.

