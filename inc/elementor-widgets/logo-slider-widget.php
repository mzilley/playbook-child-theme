<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Logo_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'logo_slider';
    }

    public function get_title() {
        return __('Logo Slider', 'text-domain');
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Slides', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => __('Logo Slides', 'text-domain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'image',
                        'label' => __('Image', 'text-domain'),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
                'title_field' => '{{{ link.url }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['slides'])) {
            return;
        }
        ?>

        <div class="logo-slider swiper">
        <div class="swiper-wrapper">
            <?php foreach ($settings['slides'] as $slide) : ?>
                <?php
                // Ensure the keys exist before using them
                $image_url = isset($slide['image']['url']) ? esc_url($slide['image']['url']) : '';
                // $link_url = isset($slide['link']['url']) ? esc_url($slide['link']['url']) : '';
                // $is_external = !empty($slide['link']['is_external']) ? 'target="_blank" rel="noopener"' : '';

                // Skip if there's no image
                if (empty($image_url)) {
                    continue;
                }
                ?>
                <div class="swiper-slide">
                    

                    <img src="<?php echo $image_url; ?>" alt="Logo">

                    
                </div>
            <?php endforeach; ?>
        </div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', function () {
                new Swiper('.logo-slider', {
                    slidesPerView: 4,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    breakpoints: {
                        1024: { slidesPerView: 4 },
                        768: { slidesPerView: 3 },
                        480: { slidesPerView: 2 },
                        320: { slidesPerView: 1 },
                    },
                });
            });
        </script>

        <?php
    }
}
