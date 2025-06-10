<?php

if (!defined('ABSPATH')) exit;

use \Elementor\Widget_Base;

class Elementor_Company_Slider_Widget extends Widget_Base {
    public function get_name() {
        return 'company-slider-widget';
    }

    public function get_title() {
        return __('Company Slider', 'your-text-domain');
    }

    public function get_icon() {
        return 'e-icon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function render() {

        $args = array(
            'post_type' => 'company',
            'posts_per_page' => '-1',
            'orderby' => 'title',
            'order' => 'ASC'
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) { ?>
            <div class="splide company-slider">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php while ($query->have_posts()) {
                            $query->the_post();
                            $logo = get_field('company_logo');
                            if ($logo) { ?>
                                <li class="splide__slide company-slider__slide">
                                    <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                                        <img src="<?php echo esc_url($logo['url']); ?>" alt=<?php echo esc_attr(get_the_title()); ?>>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <div class="company-slider-pagination"></div>
                </div>
            </div>
        <?php wp_reset_postdata(); }
    }
}