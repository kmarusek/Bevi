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
            $cat->color = get_field('background_color', $cat);
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

    public static function getPostData()
    {
        // Get Post Data
        $postData['post_title'] = get_the_title();
        $postData['post_permalink'] = get_the_permalink();
        $postData['post_author'] = get_the_author();
        $postData['post_author_avatar'] = get_avatar_url(get_the_author_meta('ID'));
        $authorId = get_the_author_meta('ID');
        $postData['post_author_role'] = get_the_author_meta('user_description', $authorId);
        $postData['post_content'] = get_the_content();
        $postData['featured_image'] = array_map(function ($image) {
            return [
                'src' => $image[0],
                'width' => $image[1],
                'height' => $image[2],
                'alt' => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true),
            ];
        }, [wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')])[0];

        // Get categories, map permalink to each cat
        $categories = get_the_category();
        $categories = array_map(function ($cat) {
            $cat->permalink = get_category_link($cat->cat_ID);
            return $cat;
        }, $categories);

        // Add to post array
        $postData['post_category'] = $categories;

        return $postData;
    }

    public function getCounters($counters = [])
    {
        $posts = get_posts([
            'numberposts' => -1,
            'post_type' => 'counters',
            'include' => $counters,
        ]);

        return collect($posts)->map(function ($post) {
            $counter = get_field('counter_data', $post->ID);

            return [
                'featured_image' => get_the_post_thumbnail_url($post->ID),
                'permalink' => get_the_permalink($post->ID),
                'post_title' => get_the_title($post->ID),
                'short_description' => $counter['short_description'],
                'counter_thumb' => $counter['counter_tumb'],
                'counter_image' => $counter['counter_image'],
                'counter_details' => $counter['counter_details'],
                'counter_link' => $counter['counter_link'],
            ];
        })->toArray();
    }

    public function getFlavors()
    {
        $posts = get_posts([
            'numberposts'=>-1,
            'post_type'=> 'flavors',
        ]);

        $posts = array_map(function ($post) {
            $post->featured_image = get_the_post_thumbnail_url($post->ID);
            $post->permalink = get_the_permalink($post->ID);
            $post->flavor_title = get_the_title($post->ID);
            $post->flavor_tags = wp_get_post_terms($post->ID, 'flavor-tags');
            $post->flavor_badge = get_field('flavor_data', $post->ID)['badge'];
            $post->flavor_ingredients = get_field('flavor_data', $post->ID)['ingredients'];
            $post->flavor_icons = get_field('flavor_data', $post->ID)['icons'];
            $post->flavor_calorie_table = get_field('flavor_data', $post->ID)['calorie_table'];
            $post->flavor_accent_color = get_field('flavor_data', $post->ID)['accent_color'];


            return $post;
        }, $posts);

        return $posts;
    }

    public function getFlavorsTags()
    {
        $tags = get_terms(array(
            'taxonomy' => 'flavor-tags',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        return $tags;
    }

    public function featuredArticles()
    {
        $posts = get_field('featured_articles', 'option');
        if ($posts) {
            $posts = array_map(function ($post) {
                $post->featured_image = get_the_post_thumbnail_url($post->ID);
                $post->permalink = get_the_permalink($post->ID);
                $post->post_content = wp_trim_words(get_the_content(null, false, $post->ID), 30, '...');
                $post->author = get_the_author_meta($post->ID);

                $categories = get_the_category($post->ID);
                $categories = array_map(function ($cat) {
                    $cat->link = get_category_link($cat->cat_ID);
                    $cat->color = get_field('background_color', $cat);
                    return $cat;
                }, $categories);

                $post->post_category = $categories;

                return $post;
            }, $posts);
            return $posts;
        }
    }

    public function getFaqs()
    {
        $posts = get_posts([
            'numberposts'=>-1,
            'post_type'=> 'faqs',
        ]);

        $posts = array_map(function ($post) {
            $post->post_title = get_the_title($post->ID);
            $postData['post_content'] = get_the_content();
            return $post;
        }, $posts);
        return $posts;
    }
}
