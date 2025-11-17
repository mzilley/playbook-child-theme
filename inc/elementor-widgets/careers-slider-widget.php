<?php
if ( ! defined('ABSPATH') ) exit;

class Elementor_Careers_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'careers_slider';
    }

    public function get_title() {
        return __('Careers Slider', 'custom-elementor');
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
            'slide_title',
            [
                'label'       => __('Slide Title', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => __('Slide Title', 'custom-elementor'),
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label'       => __('Description', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows'        => 4,
                'dynamic'     => [ 'active' => true ],
            ]
        );

        $repeater->add_control(
            'slide_description_font_size',
            [
                'label'       => __('Description Font Size (PX)', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'rows'        => 4,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => __('20', 'custom-elementor'),
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
        $slides   = isset($settings['slides']) && is_array($settings['slides']) ? array_slice($settings['slides'], 0, 16) : [];
        $uid      = $this->get_id();

        if (empty($slides)) {
            echo '<div class="careers-slider-empty">'.esc_html__('Add at least one slide.', 'custom-elementor').'</div>';
            return;
        }

        $root_id         = 'careers-slider-' . esc_attr($uid);
        $swiper_selector = '#' . $root_id . ' .careers-slider';
        $nav_next        = '#' . $root_id . ' .careers-slider-arrows .swiper-button-next';
        $nav_prev        = '#' . $root_id . ' .careers-slider-arrows .swiper-button-prev';
        ?>
        
        <div class="careers-slider-inner">
            <div class="swiper careers-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide) :
                        $title = isset($slide['slide_title']) ? $slide['slide_title'] : '';
                        $desc  = isset($slide['slide_description']) ? $slide['slide_description'] : '';
                        $font_size  = isset($slide['slide_description_font_size']) ? $slide['slide_description_font_size'] : '20.4px';
                    ?>
                    <div class="swiper-slide careers-slide">
                        <div class="careers-slide__content">
                            <?php if ($desc): ?>
                                <div class="careers-slide__desc" style="font-size: <?php echo esc_html($font_size); ?>px;">
                                    <?php echo wp_kses_post(wpautop($desc)); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($title): ?>
                                <h3 class="careers-slide__title"><?php echo esc_html($title); ?></h3>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="careers-slider-arrows" id="careers-slider-arrows">
                <div class="swiper-button swiper-button-prev" aria-label="<?php esc_attr_e('Previous', 'custom-elementor'); ?>"></div>
                <div class="swiper-button swiper-button-next" aria-label="<?php esc_attr_e('Next', 'custom-elementor'); ?>"></div>
            </div>
        </div>

        <script>

        document.addEventListener('DOMContentLoaded', function () {

            new Swiper('.careers-slider', {
                loop: false,
                speed: 800,
                navigation: {
                    nextEl: '.careers-slider-arrows .swiper-button-next',
                    prevEl: '.careers-slider-arrows .swiper-button-prev',
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1,
                        spaceBetween: 40,
                        loop: true
                    },
                    575: {
                        spaceBetween: 40,
                        slidesPerView: 2,
                    },
                    1025: {
                        spaceBetween: 40,
                        slidesPerView: 3,
                        centerMode: false,
                    },
                    1200: {
                        spaceBetween: 40,
                        slidesPerView: 3,
                        centerMode: true,
                    }
                }
            });
        });
        </script>
        <?php
    }
}
