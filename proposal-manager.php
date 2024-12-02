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

/**
 * Adds a meta box for managing proposal prices.
 *
 * @return void
 */
function add_proposal_meta_boxes() {
    add_meta_box(
        'proposal_prices',
        __('Proposal Prices', 'proposal-manager'),
        'render_proposal_prices_meta_box',
        'proposal',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_proposal_meta_boxes');

/**
 * Renders the meta box for managing proposal prices.
 *
 * @param WP_Post $post The current post object.
 * @return void
 */
function render_proposal_prices_meta_box($post) {
    $monthly_price = get_post_meta($post->ID, '_monthly_price', true);
    $oneoff_price = get_post_meta($post->ID, '_oneoff_price', true);
    wp_nonce_field('save_proposal_prices', 'proposal_prices_nonce');

    echo render_proposal_price_field('monthly_price', __('Monthly Price (£):', 'proposal-manager'), $monthly_price);
    echo render_proposal_price_field('oneoff_price', __('One-Off Price (£):', 'proposal-manager'), $oneoff_price);
}

/**
 * Renders an input field for proposal prices.
 *
 * @param string $field_id The ID and name of the field.
 * @param string $label The label for the field.
 * @param mixed $value The current value of the field.
 * @return string The HTML markup for the field.
 */
function render_proposal_price_field($field_id, $label, $value) {
    return sprintf(
        '<p><label for="%1$s">%2$s</label><input type="number" id="%1$s" name="%1$s" class="widefat" value="%3$s" step="0.01"></p>',
        esc_attr($field_id),
        esc_html($label),
        esc_attr($value)
    );
}

/**
 * Saves the meta box values for 'monthly_price' and 'oneoff_price'.
 *
 * @param int $post_id The ID of the current post.
 * @return void
 */
function save_proposal_prices($post_id) {
    // Verify nonce.
    if (!isset($_POST['proposal_prices_nonce']) || !wp_verify_nonce($_POST['proposal_prices_nonce'], 'save_proposal_prices')) {
        return;
    }

    // Prevent auto-save interference.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save meta fields with helper function.
    save_proposal_meta_field($post_id, 'monthly_price');
    save_proposal_meta_field($post_id, 'oneoff_price');
}
add_action('save_post', 'save_proposal_prices');

/**
 * Saves a meta field for the current post.
 *
 * @param int $post_id The ID of the current post.
 * @param string $field_id The ID and name of the field.
 * @return void
 */
function save_proposal_meta_field($post_id, $field_id) {
    if (isset($_POST[$field_id])) {
        update_post_meta($post_id, '_' . $field_id, sanitize_text_field($_POST[$field_id]));
    }
}

/**
 * Creates a new proposal post programmatically.
 *
 * @param string $title The title of the proposal.
 * @param float  $monthly_price The monthly price value.
 * @param float  $oneoff_price The one-off price value.
 * @return int|WP_Error The post ID on success, or WP_Error on failure.
 */
function create_proposal($title, $monthly_price, $oneoff_price) {
    $post_id = wp_insert_post([
        'post_title'    => $title,
        'post_type'     => 'proposal',
        'post_status'   => 'publish',
    ]);

    if ($post_id && !is_wp_error($post_id)) {
        save_proposal_meta_field($post_id, 'monthly_price');
        save_proposal_meta_field($post_id, 'oneoff_price');
    }

    return $post_id;
}

/**
 * Retrieves all proposals with their custom meta values.
 *
 * @return array An array of proposal objects, each including meta values.
 */
function get_proposals() {
    $proposals = get_posts([
        'post_type'   => 'proposal',
        'post_status' => 'publish',
        'numberposts' => -1,
    ]);

    foreach ($proposals as &$proposal) {
        $proposal->monthly_price = get_post_meta($proposal->ID, '_monthly_price', true);
        $proposal->oneoff_price = get_post_meta($proposal->ID, '_oneoff_price', true);
    }

    return $proposals;
}
