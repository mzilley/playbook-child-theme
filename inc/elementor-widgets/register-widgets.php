<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function register_custom_elementor_widgets( $widgets_manager ) {
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/testimonial-slider-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/testimonial-cards-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/case-studies-slider-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/buildings-slider-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/marquee-logos-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/marquee-text-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/differentiators-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/timeline-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/careers-slider-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/team-slider-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/before-after-widget.php';


    $widgets_manager->register( new \Elementor_Testimonial_Slider_Widget() );
    $widgets_manager->register( new \Elementor_Testimonial_Cards_Widget() );
    $widgets_manager->register( new \Elementor_Case_Studies_Slider_Widget() );
    $widgets_manager->register( new \Elementor_Buildings_Slider_Widget() );
    $widgets_manager->register( new \Elementor_Marquee_Logos_Widget() );
    $widgets_manager->register( new \Elementor_Marquee_Text_Widget() );
    $widgets_manager->register( new \Elementor_Differentiators_Widget() );
    $widgets_manager->register( new \Elementor_Timeline_Widget() );
    $widgets_manager->register( new \Elementor_Careers_Slider_Widget() );
    $widgets_manager->register( new \Elementor_Team_Slider_Widget() );
    $widgets_manager->register( new \Elementor_Before_After_Widget() );

}
add_action( 'elementor/widgets/register', 'register_custom_elementor_widgets' );
