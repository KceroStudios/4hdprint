# Development Notes

Miscellaneous development notes and references used during the development of the 4HD PRINT WordPress theme.

## Dashicons

### Load Dashicons on the Frontend

```php
function cargar_dashicons() {
    wp_enqueue_style('dashicons');
}

add_action('wp_enqueue_scripts', 'cargar_dashicons');

