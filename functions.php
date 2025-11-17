<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */

define( 'THEME_VERSION', '1.0.129' );
// define( 'THEME_VERSION', filemtime );

function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style( 'hello-elementor-child-style-sass', get_stylesheet_directory_uri() . '/dist/css/main.css', array(), THEME_VERSION );
    wp_enqueue_style('hello-elementor-child-style', get_stylesheet_directory_uri() .'/style.css', array(), THEME_VERSION );

    wp_enqueue_script('script', get_stylesheet_directory_uri() . '/dist/js/script.js', array('jquery'), THEME_VERSION);

	wp_localize_script(
        'script',
        'ajaxurl',
        ['url' => admin_url('admin-ajax.php')]
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts' );

add_filter( 'hello_elementor_page_title', '__return_false' );

// Shortcodes
add_shortcode('posts_loop_post', 'posts_loop_shortcode');

function social_icons_shortcode() {
    ob_start();

    $social_media = [
        'facebook' => 'social_media_facebook_link',
        'x' => 'social_media_x_link',
        'linkedin' => 'social_media_linkedin_link',
        'instagram' => 'social_media_instagram_link',
    ];

    echo '<ul class="social-icons">';

    foreach ($social_media as $platform => $field_key) {
        $link = get_field($field_key);
        if ($link) {
            echo '<li class="social-icons__icon">';
                echo '<a href="' . esc_url($link) . '" target="_blank" title="' . $platform . '" class="' . $platform . '"></a>';
            echo '</li>';
        }
    }

    echo '</ul>';

    return ob_get_clean();
}

add_shortcode('social_icons', 'social_icons_shortcode');

function team() {
    ob_start();
    include( (file_exists(get_stylesheet_directory() . '/inc/team.php') ? get_stylesheet_directory() : get_template_directory()) . '/inc/team.php' );
    return ob_get_clean();
}

add_shortcode('team', 'team');

function current_year() {
    $year = date('Y');
    return $year;
}

add_shortcode('current_year', 'current_year');

/**
* Filters the next, previous and submit buttons.
* Replaces the form's <input> buttons with <button> while maintaining attributes from original <input>.
*
* @param string $button Contains the <input> tag to be filtered.
* @param array  $form    Contains all the properties of the current form.
*
* @return string The filtered button.
*/
// add_filter( 'gform_next_button', 'input_to_button', 10, 2 );
// add_filter( 'gform_previous_button', 'input_to_button', 10, 2 );
add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
function input_to_button( $button, $form ) {
    $fragment = WP_HTML_Processor::create_fragment( $button );
    $fragment->next_token();
 
    $attributes = array( 'id', 'type', 'class', 'onclick' );
    $new_attributes = array();
    foreach ( $attributes as $attribute ) {
        $value = $fragment->get_attribute( $attribute );
        if ( ! empty( $value ) ) {
            $new_attributes[] = sprintf( '%s="%s"', $attribute, esc_attr( $value ) );
        }
    }
 
    return sprintf( '<button %s>%s</button>', implode( ' ', $new_attributes ), esc_html( $fragment->get_attribute( 'value' ) ) );
}

require_once get_stylesheet_directory() . '/inc/elementor-widgets/register-widgets.php';