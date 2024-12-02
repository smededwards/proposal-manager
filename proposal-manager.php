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
    $singular = __('Proposal', 'proposal-manager');
    $plural   = __('Proposals', 'proposal-manager');

    $labels = [
        'name'                  => $plural,
        'singular_name'         => $singular,
        'menu_name'             => $plural,
        'add_new'               => __('Add New', 'proposal-manager'),
        'add_new_item'          => sprintf(__('Add New %s', 'proposal-manager'), $singular),
        'edit_item'             => sprintf(__('Edit %s', 'proposal-manager'), $singular),
        'new_item'              => sprintf(__('New %s', 'proposal-manager'), $singular),
        'view_item'             => sprintf(__('View %s', 'proposal-manager'), $singular),
        'all_items'             => sprintf(__('All %s', 'proposal-manager'), $plural),
        'view_items'            => sprintf(__('View %s', 'proposal-manager'), $plural),
        'search_items'          => sprintf(__('Search %s', 'proposal-manager'), $plural),
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
