## Piwik modifications to libs/

In general, bug fixes and improvements are reported upstream.  Until these are
included upstream, we maintain a list of bug fixes and local mods made to
third-party libraries:

 * PclZip/
   - line 1720, added possibility to define a callable for `PCLZIP_CB_PRE_EXTRACT`. Before one needed to pass a function name
   - line 3676, ignore touch() - utime failed warning
   - line 5401, replaced `php_uname()` by `PHP_OS` (see [#2](https://github.com/piwik/component-decompress/issues/2))

This version is from :

https://github.com/piwik/component-decompress/commit/deca40d71d29d6140aad39db007aea82676b7631