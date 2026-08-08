# 4hdprint
4hd Print, wordPress Theme


# Dashicons
functions.php: 
function cargar_dashicons() {
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'cargar_dashicons');

HTML:
<span class="dashicons dashicons-admin-home"></span>

gallery url:
[text](https://developer.wordpress.org/resource/dashicons/)


net stop was /y
net stop w3svc

netstat -ano | findstr :80