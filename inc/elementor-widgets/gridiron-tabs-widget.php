<?php

if (!defined('ABSPATH')) exit;

use \Elementor\Widget_Base;

class Elementor_Gridiron_Tabs_Widget extends Widget_Base {
    public function get_name() {
        return 'gridiron-tabs-widget';
    }

    public function get_title() {
        return __('Gridiron Tabs', 'your-text-domain');
    }

    public function get_icon() {
        return 'e-icon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return['gridiron-tabs-script'];
    }

    public function register_controls() {
        $this->start_controls_section(
            'tabs_content_section',
            [
                'label' => __('Tabs Content', 'your-text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => __('Tabs', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'tab_title',
                        'label' => __('Tab Title', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __('Tab Title', 'your-text-domain'),
                    ],
                    [
                        'name' => 'tab_content',
                        'label' => __('Tab Content Left', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::WYSIWYG,
                        'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'your-text-domain'),
                    ],
                    [
                        'name' => 'tab_image',
                        'label' => __('Tab Image', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => ['url' => 'https://placehold.co/400x300/3E94F2/FFFFFF'],
                    ],
                    [
                        'name' => 'tab_video',
                        'label' => __('Tab Video Link', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                    ],
                    [
                        'name' => 'tab_link',
                        'label' => __('Tab Button Link', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::URL,
                        'default' => [
                            'url' => '/',
                            'is_external' => false,
                        ],
                    ],
                    [
                        'name' => 'tab_button_text',
                        'label' => __('Tab Button Text', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'View Case Study'
                    ],
                    [
                        'name' => 'tab_content_2',
                        'label' => __('Tab Content Right', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::WYSIWYG,
                        'default' => __('Tab content 2 lorem sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'your-text-domain'),
                    ],
                    [
                        'name' => 'tab_link_2',
                        'label' => __('Tab Link 2', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::URL,
                        'default' => [
                            'url' => '/',
                            'is_external' => false,
                        ],
                    ],
                    [
                        'name' => 'tab_link_2_text',
                        'label' => __('Tab Link 2 Text', 'your-text-domain'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
                'max_items' => 3,
            ]
        );

        $this->end_controls_section();
    }

    public function _enqueue_scripts() {
        wp_register_script('gridiron-tabs-script', get_stylesheet_directory_uri() . '/assets/js/elementor-widgets/gridiron-tabs-widget.js');
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['tabs'])) {
            return;
        } ?>
        <script src="https://player.vimeo.com/api/player.js"></script>
        <div class="gridiron-tabs__container">
            <div class="gridiron-tabs">
                <ul class="gridiron-tabs__nav" role="tablist">
                    <?php 
                    $total_tabs = count($settings['tabs']); // Get the total number of tabs
                    foreach ($settings['tabs'] as $index => $tab) {
                        $tab_id = 'tab-' . ($index + 1);
                        ?>
                        <li class="nav-item" role="presentation">
                            <button class="gridiron-tabs__nav-link <?php echo $index === 0 ? 'active' : ''; ?>" 
                                    id="<?php echo esc_attr($tab_id . '-button'); ?>" 
                                    data-target="#<?php echo esc_attr($tab_id); ?>" 
                                    type="button" role="tab" 
                                    aria-controls="<?php echo esc_attr($tab_id); ?>" 
                                    aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                                <?php if ($total_tabs > 1) { // Only show numbering if more than 1 tab ?>
                                    0<?php echo ($index + 1); ?>. 
                                <?php } ?>
                                <?php echo esc_html($tab['tab_title']); ?>
                            </button>
                        </li>
                    <?php } ?>
                </ul>
    
                <?php foreach ($settings['tabs'] as $index => $tab) {
                    $tab_id = 'tab-' . ($index + 1); ?>
    
                    <div class="gridiron-tabs__tab <?php echo $index === 0 ? 'active' : ''; ?>" id="<?php echo esc_attr($tab_id); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($tab_id . '-button'); ?>">
                        <div class="gridiron-tabs__tab-content">
                            <?php echo wp_kses_post($tab['tab_content']) ?>
                            <?php if (!empty($tab['tab_link']['url'])) { ?>
                                <div class="btn-success">
                                    <a href="<?php echo esc_url($tab['tab_link']['url']); ?>" <?php echo $tab['tab_link']['is_external'] ? 'target="_blank"' : ''; ?>><?php echo esc_html($tab['tab_button_text']); ?></a>
                                </div>
                            <?php } ?>
                        </div>
    
                        <div class="gridiron-tabs__right position-absolute">
                            <?php 
                            // Check if a video URL exists
                            if (!empty($tab['tab_video'])) {
                                $video_url = esc_url($tab['tab_video']);
                                $video_embed_url = '';
                            
                                // Check if it's a YouTube URL
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_-]+)/', $video_url, $matches)) {
                                    $youtube_id = $matches[1];
                                    $video_embed_url = "https://www.youtube.com/embed/{$youtube_id}?autoplay=1&mute=1&loop=0&modestbranding=1&rel=0&showinfo=0";
                                } 
                                // Check if it's a Vimeo URL and handle potential extra parameters
                                elseif (preg_match('/vimeo\.com\/(?:video\/)?(\d+)(?:\/([A-Za-z0-9]+))?/', $video_url, $matches)) {
                                    $vimeo_id = $matches[1];
                                    $private_hash = isset($matches[2]) ? $matches[2] : '';
                            
                                    // Construct the base Vimeo embed URL
                                    $video_embed_url = "https://player.vimeo.com/video/{$vimeo_id}?autopause=0&loop=0&muted=0&title=1&portrait=1&byline=1";
                            
                                    // If there's a private hash, append it with the correct format
                                    if (!empty($private_hash)) {
                                        $video_embed_url .= "&h={$private_hash}#t=";
                                    }
                                }
                            
                                // Output the correct iframe embed
                                if ($video_embed_url) {
                                    echo '<div class="iframe-container">';
                                    echo '<iframe src="' . esc_url($video_embed_url) . '" width="390" height="225" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                                    echo '</div>';
                                }
                            } 
                            // If no video, check if an image exists
                            elseif (!empty($tab['tab_image']['url'])) { 
                                $image_url_relative = !empty($tab['tab_image']['url']) ? str_replace(['http://', get_site_url()], ['https://', ''], $tab['tab_image']['url']) : '';

                            ?>

                                <img src="<?php echo esc_url( $image_url_relative); ?>" alt="<?php echo esc_attr($tab['tab_title']); ?>">
                            <?php 
                            } 
                            ?>
                            
                            
                            <?php echo wp_kses_post($tab['tab_content_2']) ?>
    
                            <?php if (!empty($tab['tab_link_2']['url'])) { ?>
                                <div class="link-accent">
                                    <a href="<?php echo esc_url($tab['tab_link_2']['url']); ?>" <?php echo $tab['tab_link_2']['is_external'] ? 'target="_blank"' : ''; ?>><?php echo esc_attr($tab['tab_link_2_text']) ?></a>
                                </div>
                            <?php } ?>
                        </div>
    
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }
}