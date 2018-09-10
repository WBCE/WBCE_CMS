How to install:
===============
You have to patch two files:
 wb/index.php
 wb/framework/frontend.functions.php
to make this module work.

See Documentation (docs/) how to apply the patch.



Translators:
============
Do NOT translate the documentation in docs/, because
the html-files are created automatically by a special
software.
Instead, translate the text-files in naturaldocs_txt/EN/!
Create a new directory inside naturaldoc_txt/, e.g. "DK",
and copy all files from EN/ to DK/.
Now, you can translate these files in DK/.

There are some special tokens that must be kept as is,
as shown by this example:

Original text:
--------------
Header: Admin Tool (en)
Topic: A new Topic
        Some Text follows *bold* !monospaced!
        (see img_en.png)
        Some more text follows

Translation:
------------
Header Admin-Tool (de)
Topic: Eine &Uuml;berschrift
        Es folgt Text *fett* !fixed!
        (see img_de.png)
        Noch mehr Text

Images:
-------
Copy the directory EN/images/ to DK/images, too,
and rename all images from e.g. img_en.png to img_dk.png
You may replace the images by new ones, taken from your
(DK) backend.
The standard images are reduced to 75%, and saved as
8bit PNG images (optimal palette, 256 colors, no dither).


Build process for the documentation:
------------------------------------
call the build script in naturaldocs_txt
