<?php

if (!defined('ABSPATH')) exit;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

class Elementor_Sector_Taxonomy_Widget extends Widget_Base {
    public function get_name() {
        return 'sector-taxonomy-widget';
    }

    public function get_title() {
        return __('Taxonomy List', 'your-text-domain');
    }

    public function get_icon() {
        return 'e-icon-post-list';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'your-text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $taxonomies = get_taxonomies(['public' => true], 'objects');

        $taxonomy_options = [];
        foreach ($taxonomies as $taxonomy) {
            $taxonomy_options[$taxonomy->name] = $taxonomy->label;
        }

        $this->add_control(
            'display_style',
            [
                'label' => __('Style', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'list' => __('List (Default)', 'your-text-domain'),
                    'inline' => __('Inline', 'your-text-domain'),
                ],
                'default' => 'list',
            ]
        );

        $this->add_control(
            'taxonomy',
            [
                'label' => __('Taxonomy', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $taxonomy_options,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $post_id = get_the_ID();
        $settings = $this->get_settings_for_display();
        $taxonomy = !empty($settings['taxonomy']) ? $settings['taxonomy'] : '';
        $display_style = !empty($settings['display_style']) ? $settings['display_style'] : 'list';

        $terms = get_the_terms($post_id, $taxonomy);

        if (!empty($terms) && !is_wp_error($terms)) { 
            if ($display_style === 'list') { ?>
                <div class="elementor-widget-text-editor taxonomy-list link-underline">
                    <?php $term_name = ($taxonomy == 'sector') ? 'Sector Focus' : esc_html($taxonomy) ?>
                    <p><strong><?php echo esc_html(ucfirst($term_name)) ?></strong></p>
                    <ul>
                        <?php foreach ($terms as $term) {
                                $term_link = ($taxonomy == 'sector') ? '/investment-strategy#' . sanitize_title($term->name) : get_term_link($term);


                            ?>

                            <li>
                                <a href="<?php echo esc_url($term_link) ?>" title="<?php echo esc_html($term->name) ?>"><?php echo esc_html($term->name) ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } else { ?>
            <div class="elementor-widget-text-editor taxonomy-inline link-underline">
                <p><strong><?php echo esc_html(ucfirst($taxonomy)) ?>:</strong>
            
                <?php foreach ($terms as $term) {
                    $term_link = ($taxonomy == 'sector') ? '/investment-strategy#' . sanitize_title($term->name) : get_term_link($term);

                    $links[] = '<a href="' . esc_url($term_link) . '" title="' . esc_html($term->name) . '">' . esc_html($term->name) . '</a>';
                    ?>
                <?php } ?>
                <?php echo implode(', ', $links); ?>

                </p>
                
            </div>
         <?php }
        }
    }
}