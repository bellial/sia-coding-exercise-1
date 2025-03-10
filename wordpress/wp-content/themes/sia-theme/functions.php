<?php
/**
 * The main Functions File.
 *
 */
/**
 * Register widget areas.
 */
function siatheme_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Primary Sidebar' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'siatheme_widgets_init' );

function create_posttype() {

    register_post_type( 'menus',

    array(
    'labels' => array(
    'name' => __( 'Menus' ),
    'singular_name' => __( 'Menu' )
    ),
    'public' => true,
    'has_archive' => true,
    'show_in_menu'       => true,
    'supports'    => array('title'),
    'rewrite' => array('slug' => 'menus'),
    )
    );
    }
    add_action( 'init', 'create_posttype' );

    
    function register_custom_meta_boxes() {
        add_meta_box('menus_details', 'Menu Details', 'menus_meta_box_callback', 'menus', 'normal', 'default');
    }
    add_action('add_meta_boxes', 'register_custom_meta_boxes');
    
    function menus_meta_box_callback($post) {
        $cuisine = get_post_meta($post->ID, 'cuisine', true);
        $recipe = get_post_meta($post->ID, 'recipe', true);
        
        echo '<label for="cuisine">Cuisine:</label>';
        echo '<input type="text" id="cuisine" name="cuisine" value="' . esc_attr($cuisine) . '" style="width:100%;" />';
    
        echo '<label for="recipe">Recipe:</label>';
        echo '<textarea id="recipe" name="recipe" style="width:100%; height:150px;">' . esc_textarea($recipe) . '</textarea>';
    }
    
    function save_menus_meta($post_id) {
        if (array_key_exists('cuisine', $_POST)) {
            update_post_meta($post_id, 'cuisine', sanitize_text_field($_POST['cuisine']));
        }
    
        if (array_key_exists('recipe', $_POST)) {
            update_post_meta($post_id, 'recipe', sanitize_textarea_field($_POST['recipe']));
        }
    }
    add_action('save_post', 'save_menus_meta');
    