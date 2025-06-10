<?php

if (!defined('ABSPATH')) exit;

function register_custom_elementor_widgets($widgets_manager) {
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/sector-taxonomy-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/company-coverage-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/company-slider-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/gridiron-tabs-widget.php';
    require_once get_stylesheet_directory() . '/inc/elementor-widgets/logo-slider-widget.php';

    $widgets_manager->register_widget_type(new Elementor_Sector_Taxonomy_Widget());
    $widgets_manager->register_widget_type(new Elementor_Company_Coverage_Widget());
    $widgets_manager->register_widget_type(new Elementor_Company_Slider_Widget());
    $widgets_manager->register_widget_type(new Elementor_Gridiron_Tabs_Widget());
    $widgets_manager->register_widget_type(new \Logo_Slider_Widget());
}

add_action('elementor/widgets/register', 'register_custom_elementor_widgets');