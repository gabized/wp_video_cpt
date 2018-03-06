## WordPress

Simple CPT plugin for video content.

* The plugin creates a custom post type called videos
* CPT has these fields for admin: Title, Subtitle, Description, ID, and Type (options are Youtube, Vimeo and Dailymotion - dropdown or radio buttons)
* Has a shortcode that will display a video CPT with the following attributes:
```
#!php

[prefix_video id="POST ID" border_color="#3498db"]
```
* Video output is responsive
