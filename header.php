<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header.
 *
 * @package HelloElementor
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' );
$enable_skip_link = apply_filters( 'hello_elementor_enable_skip_link', true );
$skip_link_url = apply_filters( 'hello_elementor_skip_link_url', '#content' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php if ( $enable_skip_link ) { ?>
<a class="skip-link screen-reader-text" href="<?php echo esc_url( $skip_link_url ); ?>"><?php echo esc_html__( 'Skip to content', 'hello-elementor' ); ?></a>
<?php } ?>

<header class="site-header" role="banner">
        <div class="site-header__inner">
            <div class="site-logo">
                <?php
                if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                    the_custom_logo();
                }
                ?>
            </div>

            <nav class="site-nav" role="navigation" aria-label="<?php esc_attr_e( 'Main Menu', 'hello-elementor' ); ?>">
                
                <button aria-expanded="false" aria-controls="menu-main" aria-label="Toggle navigation"></button>

                <?php
                wp_nav_menu([
                    'theme_location' => 'main',
                    'menu'           => 'Main',
                    'container'      => false,
                    'menu_class'     => 'menu',
                    'fallback_cb'    => false,          
                    'depth'          => 2,
                ]);
                ?>
            </nav>
        </div>
    </header>
