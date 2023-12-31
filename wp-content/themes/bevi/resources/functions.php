<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7.1', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7.1 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__.'/../vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (!locate_template($file, true, true)) {
        $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
    }
}, ['helpers', 'setup', 'filters', 'admin']);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
Container::getInstance()
    ->bindIf('config', function () {
        return new Config([
            'assets' => require dirname(__DIR__).'/config/assets.php',
            'theme' => require dirname(__DIR__).'/config/theme.php',
            'view' => require dirname(__DIR__).'/config/view.php',
        ]);
    }, true);

/**
 * Get Menu Items From Location
 *
 * @param $location : location slug given as key in register_nav_menus
 */

function getMenuItemsFromLocation($location)
{
    $theme_locations = get_nav_menu_locations();
    $menu_obj = get_term($theme_locations[$location], 'nav_menu');
    return is_wp_error($menu_obj) ? [] : getMenuItemsForParent($menu_obj->slug, 0);
}


/**
 * Get Menu Items For Parent
 *
 * @param $menuSlug : menu slug for the CMS entry (not the key in register_nav_menus)
 * @param $parentId
 * @return array of items formatted as objects with : name / url / children (fetched recursively)
 */

function getMenuItemsForParent($menuSlug, $parentId)
{
    $args = [
            'post_type' => 'nav_menu_item',
            'meta_key' => '_menu_item_menu_item_parent',
            'meta_value' => $parentId,
            'tax_query' => [
                [
                    'taxonomy' => 'nav_menu',
                    'field' => 'slug',
                    'terms' => [$menuSlug]
                ]
            ],
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'posts_per_page' => -1
        ];
    $tmpItems = query_posts($args);

    $items = [];

    foreach ($tmpItems as $tmpItem) {
        $item = new stdClass;
        $type = get_post_meta($tmpItem->ID, '_menu_item_type', true);
        switch ($type) :
            case 'post_type':
                $postId = get_post_meta($tmpItem->ID, '_menu_item_object_id', true);
        $post = get_post($postId);
        $item->name = $post->post_title;
        $item->label = $tmpItem->post_title;
        $item->url = get_the_permalink($postId);
        $item->pageNavId = intval($postId);
        $item->pageId = get_the_id();
        break;
        case 'custom':
                $item->name = $tmpItem->post_title;
        $item->url = get_post_meta($tmpItem->ID, '_menu_item_url', true);
        endswitch;

        $item->children = getMenuItemsForParent($menuSlug, $tmpItem->ID);
        $items[] = $item;
    }

    return $items;
}

function cptui_register_my_cpts()
    {
        /**
         * Post Type: Counters.
         */

        $labels = [
          "name" => __( "Counters", "sage" ),
          "singular_name" => __( "Counter", "sage" ),
          "menu_name" => __( "Counters", "sage" ),
        ];

        $args = [
          "label" => __( "Counters", "sage" ),
          "labels" => $labels,
          "public" => true,
          "publicly_queryable" => true,
          "show_ui" => true,
          "show_in_rest" => true,
          "rest_base" => "",
          "rest_controller_class" => "WP_REST_Posts_Controller",
          "has_archive" => true,
          "show_in_menu" => true,
          "show_in_nav_menus" => true,
          "delete_with_user" => false,
          "exclude_from_search" => false,
          "capability_type" => "post",
          "map_meta_cap" => true,
          "hierarchical" => false,
          "rewrite" => [ "slug" => "counters", "with_front" => true ],
          "query_var" => true,
          "supports" => [ "title", "editor", "thumbnail" ],
        ];

        register_post_type( "counters", $args );

        /**
         * Post Type: Flavors.
         */

        $labels = [
          "name" => __( "Flavors", "sage" ),
          "singular_name" => __( "flavor", "sage" ),
          "menu_name" => __( "Flavors", "sage" ),
        ];

        $args = [
          "label" => __( "Flavors", "sage" ),
          "labels" => $labels,
          "public" => true,
          "publicly_queryable" => true,
          "show_ui" => true,
          "show_in_rest" => true,
          "rest_base" => "",
          "rest_controller_class" => "WP_REST_Posts_Controller",
          "has_archive" => true,
          "show_in_menu" => true,
          "show_in_nav_menus" => true,
          "delete_with_user" => false,
          "exclude_from_search" => false,
          "capability_type" => "post",
          "map_meta_cap" => true,
          "hierarchical" => false,
          "rewrite" => [ "slug" => "flavors", "with_front" => true ],
          "query_var" => true,
          "supports" => [ "title", "editor", "thumbnail" ],
        ];

        register_post_type( "flavors", $args );
    }

    add_action( 'init', 'cptui_register_my_cpts' );

    /**
     * Register Flavor Tags Taxonomy.
     */
    function flavor_tags_taxononmy()
    {

        $labels = array(
            'name'                       => 'Flavor Tag',
            'singular_name'              => 'Flavor Tag',
            'menu_name'                  => 'Flavor Tags',
            'all_items'                  => 'All Flavor Tags',
            'parent_item'                => 'Parent Flavor Tag',
            'parent_item_colon'          => 'Parent Flavor Tag:',
            'new_item_name'              => 'New Flavor Tag',
            'add_new_item'               => 'Add New Flavor Tag',
            'edit_item'                  => 'Edit Flavor Tag',
            'update_item'                => 'Update Flavor Tag',
            'separate_items_with_commas' => 'Separate Flavor Tags with commas',
            'search_items'               => 'Search Flavor Tags',
            'add_or_remove_items'        => 'Add or remove Flavor Tags',
            'choose_from_most_used'      => 'Choose from the most used Flavor Tags',
            'not_found'                  => 'Not Found',
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
        );
        register_taxonomy('flavor-tags', array('flavor'), $args);
    }

    add_action('init', 'flavor_tags_taxononmy', 0);

    function reg_tag()
    {
        /**
         * Add tags to Flavors post type.
         */

        register_taxonomy_for_object_type('flavor-tags', 'flavors');
    }

    add_action('init', 'reg_tag');

function cptui_register_my_cpts_faqs() {

    /**
     * Post Type: FAQs.
     */

    $labels = [
        "name" => __( "FAQs", "sage" ),
        "singular_name" => __( "FAQ", "sage" ),
    ];

    $args = [
        "label" => __( "FAQs", "sage" ),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => [ "slug" => "faqs", "with_front" => true ],
        "query_var" => true,
        "supports" => [ "title", "editor", "thumbnail" ],
    ];

    register_post_type( "faqs", $args );
}

add_action( 'init', 'cptui_register_my_cpts_faqs' );


    // ACF Options Page
    if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Bevi Settings',
        'menu_title' => 'Bevi Settings',
        'menu_slug' => 'bevi-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
    }

// for customizer error, delete all post meta with description name
add_action( 'admin_bar_menu', 'bevi_admin_bar_menu', 999 );
function bevi_admin_bar_menu( $wp_admin_bar ) {
    global $wp_the_query;

    if ( @$wp_the_query->post->ID ) {

        $enabled = get_page_template_slug( $wp_the_query->post->ID ) == 'views/space-station-page.blade.php' ? true : false;
        $dot     = ' <span class="fl-builder-admin-bar-status-dot" style="color:' . ( $enabled ? '#6bc373' : '#d9d9d9' ) . '; font-size:18px; line-height:1;">&bull;</span>';

        $wp_admin_bar->add_node( array(
            'id'    => 'fl-builder-frontend-edit-link',
            'title' => '<span class="ab-icon"></span>' . FLBuilderModel::get_branding() . $dot,
            'href'  => FLBuilderModel::get_edit_url( $wp_the_query->post->ID ),
        ));
    }
}