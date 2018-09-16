<?php
/**
* Plugin Name: Secretary Rule: Gallery at top
* Description: Ensures that the first thing in your post is a gallery.
* Version: 1.0.0
* Author: ChrisBAshton
* Author URI: https://ashton.codes
*/

add_action('plugins_loaded', 'define_custom_secretary_rule');

function define_custom_secretary_rule() {
    SecretaryRules::register(array(
        'id' => 'gallery-at-top',
        'meta' => array(
            'title' => 'Gallery At Top',
            'description' => 'Ensures that the first thing in your post is a gallery.',
            'example' => '
gallery-at-top:
    link-to: Media File
    size: Medium
            ',
        ),
        'apply' => array('SecretaryRuleGalleryAtTop', 'apply'),
    ));
}

class SecretaryRuleGalleryAtTop {
    public static function apply($rules, $postID) {
        $mappings = [
            'links' => [
                'Attachment Page' => null, // default (doesn't appear in the shortcode)
                'Media File' => 'file',
                'None' => 'none',
            ],
            'sizes' => [
                'Thumbnail' => null, // default (doesn't appear in the shortcode)
                'Medium' => 'medium',
                'Large' => 'large',
                'Full Size' => 'full',
            ],
        ];
        $content = get_the_content_by_id($postID);
        $galleryConfig = [];
        $isGalleryAtTop = '/^\[gallery (?:.+)\]/';
        $errors = SecretaryRuleGalleryAtTop::validateConfig($rules, $mappings);
        if (sizeof($errors) > 0) return $errors;

        if (!preg_match($isGalleryAtTop, $content)) {
            return ["Warning: no gallery found at beginning of post."];
        }
        else {
            preg_match($isGalleryAtTop, $content, $galleryShortcode);
            preg_match('/link="([^"]+)"/', $galleryShortcode[0], $link);
            preg_match('/size="([^"]+)"/', $galleryShortcode[0], $size);
            preg_match('/ids="([^"]+)"/', $galleryShortcode[0], $ids);
            $errors = [];
            if ($rules['link-to'] && $mappings['links'][$rules['link-to']] !== $link[1]) {
                $errors[] = "Warning: gallery links should point to 'Media File'.";
            }
            if ($rules['size'] && $mappings['sizes'][$rules['size']] !== $size[1]) {
                $errors[] = "Warning: gallery images should be 'Medium' size.";
            }
            return $errors;
        }
    }

    public static function validateConfig($rules, $mappings) {
        $errors = [];
        if ($rules['link-to'] && !in_array($rules['link-to'], array_keys($mappings['links']))) {
            $errors[] = "Config error: Invalid 'link-to' property '" . $rules['link-to'] . "' (must be one of " . join(', ', array_keys($mappings['links'])) . ").";
        }
        if ($rules['size'] && !in_array($rules['size'], array_keys($mappings['sizes']))) {
            $errors[] = "Config error: Invalid 'size' property '" . $rules['link-to'] . "' (must be one of " . join(', ', array_keys($mappings['sizes'])) . ").";
        }
        return $errors;
    }
}
