**This document has been translated automatically. Please notice that there might be some errors or non-exact matches to the actual wording used in the backend.**

# News with Images: A new news module for WBCE CMS
News with images (short: NWI) makes it easy to create news pages or posts.
It is based on the "old" news module (3.5.12), but has been extended by several functions:
- Post picture
- integrated picture gallery (Masonry or Fotorama)
- optional 2nd content area
- Sort articles with drag & drop
- Moving / copying posts between groups and sections
- Import of Topics and "Classic" News

The rudimentary and insecure commentary function from the old news module has been dropped; if required, this function can be integrated with appropriate modules (Global Comments / Easy Comments or Reviews).

## Download
The module is a core module as of WBCE CMS 1.4 and installed by default. In addition, the download is available in the [WBCE CMS Add-On Repository] (https://addons.wbce.org).

## License
NWI is under [GNU General Public License (GPL) v3.0] (http://www.gnu.org/licenses/gpl-3.0.html).

## System requirements
NWI does not require any special system requirements and also works with older WBCE versions as well as WebsiteBaker.


## installation
1. If necessary, download the latest version from [AOR] (https://addons.wbce.org)
2. Like any other WBCE module via extensions & gt; Install modules

## Use

### Getting started and writing
1. Create a new page with "News with Images"
2. Click on "Add post"
3. Fill out the heading and, if necessary, further fields, if necessary select images. The function of the input fields is probably self-explanatory.
4. Click "Save" or "Save and go back"
5. Repeat steps 1. - 4. a few times and look at the whole in the frontend

Basically, NWI can be combined with other modules on a page or in a block, but then it can, as with any module that generates its own detail pages, come to results that do not meet the expected / desirable.

### pictures in the post
For each post a preview image can be uploaded, which is shown on the overview page and if necessary the post page. In addition, it is possible to add any number of images to a post, which are displayed as a picture gallery. The gallery presentation is shown either as a Fotorama gallery (thumbnails, full-width image) or as a Masonry gallery (picture mosaic).

Which gallery script is used is set for all posts in the settings of each section.

The gallery images are uploaded as the post is saved, and can then be captioned, resorted, or deleted.

When uploading files with the same name as already existing images, the existing files are not overwritten, but the following files are supplemented with consecutive numbering (bild.jpg, bild_1.jpg, etc.).

The management of the pictures takes place only over the post page, not over the WBCE media administration, since NWI does not "know" otherwise, where which images belong / are missing etc.

### Groups
Posts can be assigned to groups. On the one hand, this has an influence on the order (the posts are sorted according to the group and then according to a further criterion to be specified), and on the other hand, it is possible to generate topic-specific overview pages. These can then be accessed via the URL of the NWI page with the parameter g?=GROUP_ID, e.g. news.php?g=2.

A post can be assigned to one group only.

Single or multiple posts can be copied and moved between groups.

### import function
As long as no post has been made in the respective NWI section, posts from the classic news module, other NWI sections as well as topics can be imported automatically.
The page settings of the source page are applied. When importing Topics posts, however, manual rework is still required, if the "Additional Images" function was used in Topics.

### Copy / move posts
From the post overview in the backend, individual, multiple selected or all (marked) posts within a section can be copied or either copied or moved between different sections (even on different pages). Copied posts are always initially not visible in the frontend (Active selection: "no").

### Delete posts
You can delete single, multiple selected or all (selected) posts from the post overview. After confirming, the respective posts are irrevocable ** DESTROYED **, there is ** no ** way to restore them!

## configuration
All adjustments, except for whether a second block is to be used, can be made via the backend in the module settings (accessible via the "Options" button).

### overview page
- ** Order by **: definition of the order of posts (custom = manual definition, posts appear as they are arranged in the backend, start date / expiry date / submitted (= creation date) / Submission ID: each descending order according to the corresponding criterion)
- ** Posts per page **: Selection of how many entries (teaser image / text) per page should be displayed
- ** header, post loop, footer **: HTML code to format the output
- ** Resize preview image to ** Width / height of image in pixels. ** no ** automatic recalculation will take place if changes are made, so it makes sense to think in advance about the desired size and then not change the value again.

Allowed placeholders:
#### Header / Footer
- [NEXT_PAGE_LINK] "Next page", linked to the next page (if the overview page is split over several pages),
- [NEXT_LINK], "Next", s.o.,
- [PREVIOUS_PAGE_LINK], "Previous Page", s.o.,
- [PREVIOUS_LINK], "Previous", s.o.,
- [OUT_OF], [OF], "x of y",
- [DISPLAY_PREVIOUS_NEXT_LINKS] "hidden" / "visible", depending on whether pagination is required
#### post loop
- [PAGE_TITLE] headline of the page,
- [GROUP_ID] ID of the group to which the post is assigned, for posts without group "0"
- [GROUP_TITLE] Title of the group to which the post is assigned, for posts without group "",
- [GROUP_IMAGE] Image (&lt;img src ... /&gt;) of the group to which the post is assigned for posts without group "",
- [DISPLAY_GROUP] * inherit * or * none *,
- [DISPLAY_IMAGE] * inherit * or * none *,
- [TITLE] title (heading) of the article,
- [IMAGE] post image (&lt;img src = ... /&gt;),
- [SHORT] short text,
- [LINK] Link to the article detail view,
- [MODI_DATE] date of the last change of the post,
- [MODI_TIME] Time (time) of the last change of the post,
- [CREATED_DATE] Date when the post was created,
- [CREATED_TIME] time at which the post was created,
- [PUBLISHED_DATE] start date,
- [PUBLISHED_TIME] start time,
- [USER_ID] ID of the creator of the post,
- [USERNAME] username of the creator of the post,
- [DISPLAY_NAME] Display name of the creator of the post,
- [EMAIL] Email address of the creator of the post,
- [TEXT_READ_MORE] "Show details",
- [SHOW_READ_MORE], * hidden * or * visible *,
- [GROUP_IMAGE_URL] URL of the group image

### post view
- ** Message Header, Content, Footer, Block 2 **: HTML code for formatting the message

Allowed placeholders:
#### Message Header, Message Footer, Block 2
- [PAGE_TITLE] headline of the page,
- [GROUP_ID] ID of the group to which the post is assigned, for posts without group "0"
- [GROUP_TITLE] Title of the group to which the post is assigned, for posts without group "",
- [GROUP_IMAGE] Image (&lt;img src ... /&gt;) of the group to which the post is assigned for posts without group "",
- [DISPLAY_GROUP] * inherit * or * none *,
- [DISPLAY_IMAGE] * inherit * or * none *,
- [TITLE] title (heading) of the article,
- [IMAGE] post image (&lt;img src = ... /&gt;),
- [SHORT] short text,
- [MODI_DATE] date of the last change of the post,
- [MODI_TIME] Time (time) of the last change of the post,
- [CREATED_DATE] Date when the post was created,
- [CREATED_TIME] time at which the post was created,
- [PUBLISHED_DATE] start date,
- [PUBLISHED_TIME] start time,
- [USER_ID] ID of the creator of the post,
- [USERNAME] username of the creator of the post,
- [DISPLAY_NAME] Display name of the creator of the post,
- [EMAIL] Email address of the creator of the post

#### news content
- [CONTENT] Post Content (HTML)
- [IMAGES] Images / Gallery HTML

### Gallery / Picture Settings
- ** Image Gallery **: Selection of the gallery script to use. Please note that any customizations made to the gallery code in the Message Content field will be lost in case of a change.
- ** Image loop **: HTML code for the representation of a single image must match the respective gallery script
- **Max. Image size in bytes **: File size per image file, why this must now be specified in bytes and not in readable KB or MB, I just do not know
- ** Resize gallery images to / Thumbnail size width x height **: exactly same. ** no ** automatic recalculation will take place if changes are made, so it makes sense to think in advance about the desired size and then not change the value again.
- ** Crop **: See the explanation on the page.

### 2nd block
Optionally, a second block can be displayed if the template supports it.
- Use block 2 (default): No entry or entry * define ('NWI_USE_SECOND_BLOCK', true); * in the config.php in the root
- do not use block 2: entry * define ('NWI_USE_SECOND_BLOCK', false); * in the config.php in the root