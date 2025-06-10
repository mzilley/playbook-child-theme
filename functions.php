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

// define( 'THEME_VERSION', '1.0.129' );
// define( 'THEME_VERSION', filemtime );


function hello_elementor_child_enqueue_scripts() {
    $cache_bust_css = filemtime( get_stylesheet_directory() . '/style.css' );
    $cache_bust_sass = filemtime( get_stylesheet_directory() . '/dist/css/main.css' );
    $cache_bust_js = filemtime( get_stylesheet_directory() . '/dist/js/script.js' );

	
	wp_enqueue_style( 'hello-elementor-child-style-sass', get_stylesheet_directory_uri() . '/dist/css/main.css', array(), $cache_bust_sass );
    wp_enqueue_style('hello-elementor-child-style', get_stylesheet_directory_uri() .'/style.css', array(), $cache_bust_css );

    wp_enqueue_script('script', get_stylesheet_directory_uri() . '/dist/js/script.js', array('jquery'), $cache_bust_js);

	wp_localize_script(
        'script',
        'ajaxurl',
        ['url' => admin_url('admin-ajax.php')]
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts' );

function elementor_widgets_enqueue_scripts() {
    wp_enqueue_script('gridiron-tabs-script', get_stylesheet_directory_uri() . '/assets/js/elementor-widgets/gridiron-tabs-widget.js', [], null, true);
}
add_action( 'wp_enqueue_scripts', 'elementor_widgets_enqueue_scripts' );

function elementor_widgets_enqueue_editor_scripts() {
    if (defined('ELEMENTOR_VERSION')) {
        wp_enqueue_script('gridiron-tabs-script', get_stylesheet_directory_uri() . '/assets/js/elementor-widgets/gridiron-tabs-widget.js', [], null, true);
    }
}

require_once get_stylesheet_directory() . '/inc/elementor-widgets/register-widgets.php';


function auto_populate_company_url($value, $post_id, $field) {
    // Ensure we're only modifying the 'company' post type
    if (get_post_type($post_id) !== 'company') {
        return $value;
    }

    // If the field is empty, populate it with the permalink
    if (empty($value)) {
        return get_permalink($post_id);
    }

    return $value;
}
add_filter('acf/update_value/name=company_url', 'auto_populate_company_url', 10, 3);


function sync_company_titles_to_company_tags() {
    // Get all company posts
    $companies = get_posts(array(
        'post_type' => 'company',
        'posts_per_page' => -1,
    ));

    // Loop through each company and add it to the taxonomy
    if (!empty($companies)) {
        foreach ($companies as $company) {
            $company_name = $company->post_title;

            // Check if the term exists in 'company_tags'
            $existing_term = term_exists($company_name, 'company-tag');

            // If it doesn't exist, create the term
            if (!$existing_term) {
                wp_insert_term($company_name, 'company-tag');
            }
        }
    }
}
add_action('init', 'sync_company_titles_to_company_tags');

// function assign_company_tag_to_post($post_id, $post) {
//     if ($post->post_type !== 'company') {
//         return;
//     }

//     $company_name = get_the_title($post_id);
//     wp_set_object_terms($post_id, $company_name, 'company-tag', true);
// }
// add_action('save_post_company', 'assign_company_tag_to_post', 20, 2);


// function change_robots_index( $presentation ) {
//     if (is_single() && has_tag('case-studies')) {
//         $robots = $presentation->robots;

//         $values = \array_map( function( $item ) {
//             if ( strpos( $item, 'index' ) !== false ) {
//                 $item = 'noindex';
//             }

//             return $item;
//         }, $robots );

//         return \implode( ', ', $values );
//     }
    
// }

// add_filter('wpseo_robots', 'change_robots_index');


function fetch_filtered_posts() {
    if (!isset($_POST['action']) || $_POST['action'] !== 'fetch_filtered_posts') {
        wp_send_json_error(['message' => 'Invalid action'], 400);
    }

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    // Query posts
    $args = [
        'post_type'      => 'post',
        'posts_per_page' => 12,
        'orderby' => 'date',
        'order'   => 'DESC',
        'paged'          => $paged,
        'post_status'    => 'publish',
        'category_name'  => $category,
        'tag'            => $tag,
        's'              => $search,
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Check if the ACF field 'case_study_url' exists and has a value
            $case_study_url = get_field('case_study_url');
            $post_url = (!empty($case_study_url)) ? esc_url($case_study_url) : get_permalink();

            echo '<article>';
            echo '<div class="post-meta">';
                echo '<div class="post-meta__content">';
                    echo '<h2><a href="' . $post_url . '">' . esc_html(get_the_title()) . '</a></h2>';
                    echo '<span class="post-meta__date">Posted ' . get_the_date('m/d/Y') . '</span>';
                echo '</div>';
                echo '<div class="post-meta__cta">';
                    echo '<a title="' . esc_attr(get_the_title()) . '" href="' . $post_url . '" target="_blank">Read</a>';
                echo '</div>';
            echo '</div>';
            echo '</article>';
        }

        // Output pagination
		$total_pages = $query->max_num_pages;
		if ($total_pages > 1) {
            echo '<div class="pagination">';
        
            if ($paged > 1) {
                echo '<button aria-label="Go to the previous page of news and insights posts" class="page-link prev" data-page="' . ($paged - 1) . '"></button>';
            } else {
                echo '<button aria-label="Go to the previous page of news and insights posts" class="page-link prev" disabled></button>';
            }
        
            for ($i = 1; $i <= $total_pages; $i++) {
                $active_class = ($i == $paged) ? ' active' : '';
                echo '<button class="page-link' . $active_class . '" data-page="' . $i . '">' . $i . '</button>';
            }
        
            if ($paged < $total_pages) {
                echo '<button aria-label="Go to the next page of news and insights posts" class="page-link next" data-page="' . ($paged + 1) . '"></button>';
            } else {
                echo '<button aria-label="Go to the next page of news and insights posts" class="page-link next" disabled></button>';
            }
        
            echo '</div>';
        }

    } else {
        echo '<p id="no-posts">No posts found.</p>';
    }

    wp_reset_postdata();
    wp_die();
}


add_action('wp_ajax_fetch_filtered_posts', 'fetch_filtered_posts');
add_action('wp_ajax_nopriv_fetch_filtered_posts', 'fetch_filtered_posts');

// Our Companies
function fetch_filtered_companies() {
    // Validate action
    if (!isset($_POST['action']) || $_POST['action'] !== 'fetch_filtered_companies') {
        wp_send_json_error(['message' => 'Invalid action'], 400); // Return 400 error if action is invalid
    }

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $sector = isset($_POST['sector']) ? sanitize_text_field($_POST['sector']) : '';
    $fund = isset($_POST['fund']) ? sanitize_text_field($_POST['fund']) : '';
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

    $args = [
        'post_type' => 'company',
        'posts_per_page' => 12,
        'paged' => $paged,
        'tax_query' => [],
    ];

    if ($sector) {
        $args['tax_query'][] = [
            'taxonomy' => 'sector',
            'field' => 'slug',
            'terms' => $sector,
        ];
    }

    if ($fund) {
        $args['tax_query'][] = [
            'taxonomy' => 'fund',
            'field' => 'slug',
            'terms' => $fund,
        ];
    }

    if ($status) {
        $args['tax_query'][] = [
            'taxonomy' => 'company-status',
            'field' => 'slug',
            'terms' => $status,
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Fetch ACF field for company logo
            $company_logo = get_field('company_logo'); // Returns an array
            $company_logo_url = $company_logo['url'] ?? ''; // Safely access the URL
            $company_logo_alt = $company_logo['alt'] ?? get_the_title(); // Fallback to post title for alt text


            echo '<article>';
                echo '<div class="company-logo">';
                    echo '<h2>';
                        if ($company_logo_url) {
                            echo '<a href="' . get_permalink() . '">';
                            echo '<img src="' . esc_url($company_logo_url) . '" alt="' . esc_attr($company_logo_alt) . '" />';
                            echo '</a>';
                        }
                    echo '</h2>';
                echo '<div>';
            echo '</article>';

		
        }
        $total_pages = $query->max_num_pages;
		if ($total_pages > 1) {
            echo '<div class="pagination">';
        
            if ($paged > 1) {
                echo '<button aria-label="Go to the previous page of news and insights posts" href="#" class="page-link prev" data-page="' . ($paged - 1) . '"></button>';
            } else {
                echo '<button aria-label="Go to the previous page of news and insights posts" class="page-link prev" disabled></button>';
            }
        
            for ($i = 1; $i <= $total_pages; $i++) {
                $active_class = ($i == $paged) ? ' active' : '';
                echo '<button class="page-link' . $active_class . '" data-page="' . $i . '">' . $i . '</button>';
            }
        
            if ($paged < $total_pages) {
                echo '<button aria-label="Go to the next page of news and insights posts" class="page-link next" data-page="' . ($paged + 1) . '"></button>';
            } else {
                echo '<button aria-label="Go to the next page of news and insights posts" class="page-link next" disabled></button>';
            }
        
            echo '</div>';
        }
    } else {
        echo '<p id="no-posts">No posts found.</p>';
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_fetch_filtered_companies', 'fetch_filtered_companies');
add_action('wp_ajax_nopriv_fetch_filtered_companies', 'fetch_filtered_companies');

function fetch_filtered_team_members() {
    if (!isset($_POST['action']) || $_POST['action'] !== 'fetch_filtered_team_members') {
        wp_send_json_error(['message' => 'Invalid action'], 400);
    }

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $team = isset($_POST['employees']) ? sanitize_text_field($_POST['employees']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    // Query posts
    $args = [
        'post_type' => 'team',
        'orderby'=> 'title',
        'order' => 'ASC',
        'posts_per_page' => 12,
        'paged' => $paged + 1,
        's' => $search,
    ];

    if (!empty($team)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'team', // Replace 'team' with the actual taxonomy name if different
                'field' => 'slug',
                'terms' => $team,
            ],
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get Featured Image ID
            $image_id = get_post_thumbnail_id($post->ID);

            // Get Image URLs
            $image_url = wp_get_attachment_image_src($image_id, 'full');
            $image_webp = wp_get_attachment_image_src($image_id, 'full', false, ['format' => 'webp']);

            // Ensure URLs are properly retrieved and force HTTPS
            $image_url_relative = !empty($image_url[0]) ? str_replace(['http://', get_site_url()], ['https://', ''], $image_url[0]) : '';
            $webp_url_relative = !empty($image_webp[0]) ? str_replace(['http://', get_site_url()], ['https://', ''], $image_webp[0]) : '';

            echo '<div class="team-member">';
                if ($image_url_relative) { // Ensure an image exists before outputting
                    echo '<div class="team-member__image">';
                        echo '<a href="' . get_permalink() . '" aria-label="Go to ' . esc_attr(get_the_title()) . '\'s bio">';
                            echo '<picture>';
                                echo '<source srcset="' . esc_url($webp_url_relative) . '.webp" type="image/webp">';
                                echo '<img src="' . esc_url($image_url_relative) . '" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                            echo '</picture>';
                        echo '</a>';
                    echo '</div>';
                }
                echo '<div class="team-member__info">';
                    echo '<h2 class="team-member__info-name"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                    echo '<p class="team-member__info-position">' . esc_html(get_field('title')) . '</p>';
                echo '</div>';
            echo '</div>';
        }

		echo '<div class="pagination" data-total-pages="' .  $query->max_num_pages . '"></div>';
    
    } else {
        echo '<p id="no-posts">No team members found.</p>';
        echo '<div class="pagination" data-total-pages="0"></div>';
    }

    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_fetch_filtered_team_members', 'fetch_filtered_team_members');
add_action('wp_ajax_nopriv_fetch_filtered_team_members', 'fetch_filtered_team_members');

// Shortcodes
function posts_loop_shortcode() {
    ob_start();

    echo '<div id="posts-container" class="dynamic-container">';

        echo '<article>';
            echo '<div class="post-meta">';
            echo '<div class="post-meta__content">';
            echo '<h2><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h2>';
            echo '<span class="post-meta__date">Posted ' . esc_html(get_the_date('m/d/Y', )) . '</span>';
            echo '</div>';
            echo '<div class="post-meta__cta">';
            echo '<a title="' . esc_attr(get_the_title()) . '" href="' . esc_url(get_permalink()) . '">View Post</a>';
            echo '</div>';
            echo '</div>';
        echo '</article>';
    echo '</div>';

    return ob_get_clean();
}

add_shortcode('posts_loop_post', 'posts_loop_shortcode');

function social_icons_shortcode() {
    ob_start();

    // Define the social media platforms and their corresponding custom field keys
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

function news_and_insights() {
    ob_start();
    include( (file_exists(get_stylesheet_directory().'/inc/news-and-insights.php') ? get_stylesheet_directory() : get_template_directory()) . '/inc/news-and-insights.php' );
    return ob_get_clean();    
}
add_shortcode('news-and-insights', 'news_and_insights');

function our_companies() {
    ob_start();
    include( (file_exists(get_stylesheet_directory().'/inc/our-companies.php')    ? get_stylesheet_directory() : get_template_directory()) . '/inc/our-companies.php' );
    return ob_get_clean();    
}
add_shortcode('our-companies', 'our_companies');

function team() {
    ob_start();
    include( (file_exists(get_stylesheet_directory().'/inc/team.php')    ? get_stylesheet_directory() : get_template_directory()) . '/inc/team.php' );
    return ob_get_clean();    
}
add_shortcode('team', 'team');

function current_year() {
    $year = date('Y');
    return $year;
}

add_shortcode('current_year', 'current_year');

class Elementor_ACF_Subfield_Tag extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'acf-nested-subfield';
    }
    
    public function get_title() {
        return __('ACF Nested Fields', 'your-text-domain');
    }

    public function get_group(): array {
		return [ 'action' ];
	}

    public function get_categories(): array {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}

    protected function register_controls() {
        $this->add_control(
            'parent_field',
            [
                'label' => __('Parent Field', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter parent field name', 'your-text-domain'),
            ]
        );

        $this->add_control(
            'sub_field',
            [
                'label' => __('Sub Field', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter subfield name', 'your-text-domain'),
            ]
        );

        $this->add_control(
            'sub_sub_field',
            [
                'label' => __('Sub Sub Field', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter sub subfield name', 'your-text-domain'),
            ]
        );
    }

    public function render() {
        $parent_field = $this->get_settings('parent_field');
        $sub_field = $this->get_settings('sub_field');
        $sub_sub_field = $this->get_settings('sub_sub_field');
        $post_id = get_the_id();

        if (!$parent_field || !$sub_field) {
            return;
        }

        if (have_rows($parent_field, $post_id)) {
            
                while (have_rows($parent_field, $post_id)) {
                    the_row();
                    if ($sub_field && !$sub_sub_field) {
                        echo wp_kses_post(get_sub_field($sub_field));
                    } else if ($sub_field && $sub_sub_field) {
                        while (have_rows($sub_field)) {
                            the_row();
                            echo wp_kses_post(get_sub_field($sub_sub_field));
                        }
                    }
                }
            
        }
    }
}

function register_custom_elementor_tags($dynamic_tags) {
    $dynamic_tags->register_tag('Elementor_ACF_Subfield_Tag');
}

add_action('elementor/dynamic_tags/register', 'register_custom_elementor_tags');

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