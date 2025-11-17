<?php
if ( ! defined('ABSPATH') ) exit;

class Elementor_Buildings_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'buildings_slider';
    }

    public function get_title() {
        return __('Buildings Slider', 'custom-elementor');
    }

    public function get_icon() {
        return 'eicon-posts-group';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['swiper'];
    }

    public function get_style_depends() {
        return ['swiper'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Slides', 'custom-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_image',
            [
                'label'   => __('Image', 'custom-elementor'),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'slide_title',
            [
                'label'       => __('Building Title', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => __('Building Title', 'custom-elementor'),
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label'       => __('Building Description', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows'        => 4,
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label'       => __('Slides', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ slide_title || "Slide" }}}',
                'default'     => [],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides   = isset($settings['slides']) && is_array($settings['slides']) ? array_slice($settings['slides'], 0, 10) : [];
        $uid      = $this->get_id();

        if (empty($slides)) {
            echo '<div class="buildings-slider-empty">'.esc_html__('Add at least one slide.', 'custom-elementor').'</div>';
            return;
        }

        $root_id         = 'buildings-slider-' . esc_attr($uid);
        $swiper_selector = '#' . $root_id . ' .buildings-slider';
        $nav_next        = '#' . $root_id . ' .buildings-slider-arrows .swiper-button-next';
        $nav_prev        = '#' . $root_id . ' .buildings-slider-arrows .swiper-button-prev';
        ?>
        
        <div class="buildings-slider-inner">
            <div class="swiper buildings-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide) :
                        $img  = isset($slide['slide_image']['url']) ? $slide['slide_image']['url'] : '';
                        $alt  = isset($slide['slide_title']) ? $slide['slide_title'] : '';
                        $title = isset($slide['slide_title']) ? $slide['slide_title'] : '';
                        $desc  = isset($slide['slide_description']) ? $slide['slide_description'] : '';
                    ?>
                    <div class="swiper-slide building-slide">
                        <div class="building-slide__media">
                            <?php if ($img): ?>
                                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async" />
                            <?php endif; ?>
                        </div>
                        <div class="building-slide__content">
                            <?php if ($title): ?>
                                <h3 class="building-slide__title"><?php echo esc_html($title); ?></h3>
                            <?php endif; ?>
                            <?php if ($desc): ?>
                                <div class="building-slide__desc">
                                    <?php echo wp_kses_post(wpautop($desc)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="buildings-slider-arrows">
                <div class="swiper-button swiper-button-prev" aria-label="<?php esc_attr_e('Previous', 'custom-elementor'); ?>"></div>
                <div class="swiper-button swiper-button-next" aria-label="<?php esc_attr_e('Next', 'custom-elementor'); ?>"></div>
            </div>
        </div>

        <script>

        document.addEventListener('DOMContentLoaded', function () {

            new Swiper('.buildings-slider', {
                slidesPerView: 1,
                loop: false,
                centerMode: true,
                spaceBetween: 76,
                speed: 800,
                navigation: {
                    nextEl: '.buildings-slider-arrows .swiper-button-next',
                    prevEl: '.buildings-slider-arrows .swiper-button-prev',
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                        spaceBetween: 40,
                        loop: true
                    },
                    1200: {
                        slidesPerView: 1,
                        spaceBetween: 76,
                    }
                }
            });
        });
        </script>
        <?php
    }
}
