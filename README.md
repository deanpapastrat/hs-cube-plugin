High School Cube WP
==============

> Quick Notice: This plugin is only compatible with WordPress 3.9 'Smith' and above!

High School Cube is one of the premier video streaming services for high schools. However, until now, it has not been available for schools to use in their WordPress sites. High School Cube WP fixes that issue and makes it easy for broadcasters to integrate it directly into posts, pages, and sidebars.

##Features

High School Cube WP, as of v0.2.0, has 2 main components for integrating videos/scoreboards:
* Shortcodes
* Widgets

However, it also includes a variety of features within the code of the plugin itself that many developers may find useful:

* Fully commented code
* Fully indented code
* Fully prefixed code to prevent interference
* Use of the WordPress Transients API to cache High School Cube API requests for speed improvements
* Sanitization of all data entered by users

## Installation

1. Download the ZIP file from here
2. Go to the plugin menu in WP-Admin
3. Click add new
4. Select upload
5. Upload the plugin
6. Activate the plugin

## Quick Reference

The following paragraphs describe the functions currently available with the High School Cube WordPress Plugin as of v0.2.0 "Rough Turf".

### Shortcodes

#### (1) Video Player

Parameters:

* **url (REQUIRED)** - the url of the page the video is located on (**must** have the http:// prefix)
* width (_OPTIONAL_) - the width you want the video to be (accepts the following values: px|em|%|pt)
* height (_OPTIONAL_) - the height you want the video to be (accepts the following values: px|em|%|pt)

Syntax:

`[hscube-video url="url" width="width" height="height"]`

#### (2) Scoreboard

Parameters:

* **url (REQUIRED)** - the url of the page the video whose scoreboard you want is located on (**must** have the http:// prefix)
* width (_OPTIONAL_) - the width you want the scoreboard to be (accepts the following values: px|em|%|pt)
* height (_OPTIONAL_) - the height you want the scoreboard to be (accepts the following values: px|em|%|pt)

Syntax:

`[hscube-scoreboard url="url" width="width" height="height"]`

### Widgets

#### (1) Video Player

Parameters:

* title - the title listed above the widget
* **url (REQUIRED)** - the url of the page the video is located on (**must** have the http:// prefix)
* width (_OPTIONAL_) - the width you want the video to be (accepts the following values: px|em|%|pt)
* height (_OPTIONAL_) - the height you want the video to be (accepts the following values: px|em|%|pt)

#### (2) Scoreboard

Parameters:

* title - the title listed above the widget
* **url (REQUIRED)** - the url of the page the video whose scoreboard you want is located on (**must** have the http:// prefix)
* width (_OPTIONAL_) - the width you want the scoreboard to be (accepts the following values: px|em|%|pt)
* height (_OPTIONAL_) - the height you want the scoreboard to be (accepts the following values: px|em|%|pt)

## Authors and Contributors

Dean Papastrat (@deanpapastrat) made this plugin after finding it difficult to properly integrate High School Cube with WordPress without continually pasting iframe after iframe, so he made a plugin to make it easier. He continues to develop it. He enjoys hiking and video games in his free time.

## Support or Contact

Having trouble? Leave a comment or send @deanpapastrat a message.
