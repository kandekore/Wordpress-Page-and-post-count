<?php
/**
 * Plugin Name: Page & Post Count
 * Description: Counts pages, posts, tags, and categories on the site.
 * Version: 1.0
 * Author: D.Kandekore
 */

// Function to get the total count of posts, pages, tags, categories, and WooCommerce entities.
function get_site_statistics() {
    $stats = array();
    $total_count = 0;
    
    // Count posts
    $post_count = wp_count_posts();
    $stats['Posts'] = $post_count->publish;
    $total_count += $post_count->publish;

    // Count pages
    $pages = get_pages();
    $stats['Pages'] = count($pages);
    $total_count += count($pages);

    // Count tags
    $tags = wp_count_terms('post_tag');
    $stats['Tags'] = $tags;
    $total_count += $tags;

    // Count categories
    $categories = wp_count_terms('category');
    $stats['Categories'] = $categories;
    $total_count += $categories;

    // WooCommerce statistics
    if (class_exists('WooCommerce')) {
        // Count products
        $product_count = wp_count_posts('product');
        $stats['Products'] = $product_count->publish;
        $total_count += $product_count->publish;

        // Count product tags
        $product_tags = wp_count_terms('product_tag');
        $stats['Product Tags'] = $product_tags;
        $total_count += $product_tags;

        // Count product categories
        $product_categories = wp_count_terms('product_cat');
        $stats['Product Categories'] = $product_categories;
        $total_count += $product_categories;
    }

    // Add total count
    $stats['Total'] = $total_count;

    return $stats;
}

// Function to display the Dashboard widget.
function site_stats_dashboard_widget() {
    $stats = get_site_statistics();

    echo '<ul>';
    foreach ($stats as $type => $count) {
        echo '<li>' . $type . ': ' . $count . '</li>';
    }
    echo '</ul>';
}

// Function to add the Dashboard widget.
function add_site_stats_dashboard_widget() {
    wp_add_dashboard_widget('site_stats_dashboard_widget', 'Number of pages & posts', 'site_stats_dashboard_widget');
}

// Hook into the 'wp_dashboard_setup' action to register our widget.
add_action('wp_dashboard_setup', 'add_site_stats_dashboard_widget');