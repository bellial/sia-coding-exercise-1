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
