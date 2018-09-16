# Secretary Rule: Gallery at Top

This plugin adds a new rule to the Secretary plugin, which forces the first element in a post to be a gallery (required for studentmunch.com):

```yaml
gallery-at-top:
    link-to: Media File
    size: Medium
```

`link-to` is optional, and can be either `Attachment Page`, `Media File`, `None` (ie the same options as in the WYSIWYG).

`size` is optional, can can be either `Thumbnail`, `Medium`, `Large`, `Full Size` (ie the same options as in the WYSIWYG).

