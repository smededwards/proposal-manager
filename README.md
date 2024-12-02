# Proposal Manager

A WordPress plugin to manage proposals with custom metadata for prices.

## Description

The Proposal Manager plugin allows WordPress users to manage proposals as a custom post type. It includes fields for storing `monthly_price` and `oneoff_price`.

## Features

- Custom Post Type: `proposal`
- Meta Boxes: `monthly_price` and `oneoff_price`
- Programmatic Proposal Creation (`create_proposal`)
- Retrieve All Proposals with Metadata (`get_proposals`)

## Installation

1. Save the plugin file as `proposal-manager.php`.
2. Upload it to the `/wp-content/plugins/` directory.
3. Activate the plugin through the WordPress admin dashboard.

## Usage

### Creating a Proposal Programmatically

Use the `create_proposal` function to create a new proposal:

```php
create_proposal('New Proposal', 100.00, 500.00);
```

## Retrieving Proposals

```php
$proposals = get_proposals();
foreach ($proposals as $proposal) {
    echo '<h2>' . $proposal->post_title . '</h2>';
    echo 'Monthly Price: $' . $proposal->monthly_price . '<br>';
    echo 'One-off Price: $' . $proposal->oneoff_price . '<br>';
}
```
