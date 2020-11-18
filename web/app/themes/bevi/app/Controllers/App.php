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

    public function allCategories()
    {
        $categories = get_categories([
            'orderby' => 'name',
            'order'   => 'ASC'
        ]);

        $categories = array_map(function ($cat) {
            $cat->link = get_category_link($cat->cat_ID);
            return $cat;
        }, $categories);

        return $categories;
    }

    public function allPosts()
    {
        $posts = get_posts([
            'numberposts'=>-1,
        ]);

        $posts = array_map(function ($post) {
            $post->featured_image = get_the_post_thumbnail_url($post->ID);
            $post->permalink = get_the_permalink($post->ID);
            $post->post_category = get_the_category($post->ID);

            return $post;
        }, $posts);

        return $posts;
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
            $post->short_description = get_field('counter_data', $post->ID)['short_description'];
            $post->counter_thumb = get_field('counter_data', $post->ID)['counter_tumb'];
            $post->counter_image = get_field('counter_data', $post->ID)['counter_image'];
            $post->counter_details = get_field('counter_data', $post->ID)['counter_details'];
            $post->counter_link = get_field('counter_data', $post->ID)['counter_link'];

            return $post;
        }, $posts);

        return $posts;
    }

    public function featuredArticles()
    {
        $posts = get_field('featured_articles', 'option');
        if ($posts) {
            $posts = array_map(function ($post) {
                $post->featured_image = get_the_post_thumbnail_url($post->ID);
                $post->permalink = get_the_permalink($post->ID);
                $post->author = get_the_author_meta($post->ID);
                $post->post_category = get_the_category($post->ID);
                return $post;
            }, $posts);
            return $posts;
        }
    }
}
