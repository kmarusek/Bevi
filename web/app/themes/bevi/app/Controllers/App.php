<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use App\Controllers\Templates\FlexibleContent;

class App extends Controller
{
    use FlexibleContent;

    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }

    public function getCounters()
    {
        $posts = get_posts([
            'numberposts'=>2,
            'post_type'=> 'counters',
        ]);

        $posts = array_map(function ($post) {
            $post->featured_image = get_the_post_thumbnail_url($post->ID);
            $post->permalink = get_the_permalink($post->ID);
            $post->post_title = get_the_title($post->ID);
            
            return $post;
        }, $posts);

        return $posts;
    }
}
