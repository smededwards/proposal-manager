<?php
/**
 * Plugin Name: Proposal Manager
 * Description: A WordPress plugin to manage proposals with custom metadata for prices.
 * Version: 1.0
 * Author: Michael Edwards
 * Text Domain: proposal-manager
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

/**
 * Registers the custom post type 'proposal'.
 *
 * @return void
 */
function register_proposal_cpt() {
    $labels = [
        'name'                  => __('Proposals', 'proposal-manager'),
        'singular_name'         => __('Proposal', 'proposal-manager'),
        'menu_name'             => __('Proposals', 'proposal-manager'),
        'add_new_item'          => __('Add New Proposal', 'proposal-manager'),
        'edit_item'             => __('Edit Proposal', 'proposal-manager'),
        'all_items'             => __('All Proposals', 'proposal-manager'),
        'view_item'             => __('View Proposal', 'proposal-manager'),
        'search_items'          => __('Search Proposals', 'proposal-manager'),
    ];

    $args = [
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'show_in_menu'          => true,
        'show_in_rest'          => true,
        'menu_icon'             => 'dashicons-media-document',
        'supports'              => ['title', 'editor', 'custom-fields'],
        'capability_type'       => 'post',
    ];

    register_post_type('proposal', $args);
}
add_action('init', 'register_proposal_cpt');
