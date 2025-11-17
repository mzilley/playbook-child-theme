<?php
if (!defined('ABSPATH')) exit;

class Elementor_Testimonial_Slider_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'testimonial_slider';
    }

    public function get_title() {
        return __('Testimonial Slider', 'custom-elementor');
    }

    public function get_icon() {
        return 'eicon-slider-full-screen';
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
        $this->start_controls_section('content_section', [
            'label' => __('Testimonials', 'custom-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('testimonial_text', [
            'label' => __('Testimonial Text', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
        ]);

        $repeater->add_control('logo', [
            'label' => __('Logo', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::MEDIA,
        ]);

        $repeater->add_control('first_name', [
            'label' => __('First Name', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
        ]);

        $repeater->add_control('last_name', [
            'label' => __('Last Name', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
        ]);

        $repeater->add_control('designation', [
            'label' => __('Designation / Title', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
        ]);

        $this->add_control('testimonials', [
            'label' => __('Testimonials List', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['testimonials'])) return;

        echo '<div class="testimonial-slider swiper">';
        echo '<div class="swiper-wrapper">';

        foreach ($settings['testimonials'] as $item) {
            $first_name = esc_html($item['first_name']);
            $last_name = esc_html($item['last_name']);
            $logo_url = $item['logo']['url'];

            echo '<div class="swiper-slide testimonial-slide">';
                echo '<div class="testimonial-content">';
                    echo '<div class="testimonial-person">';
                        if ($logo_url) {
                            echo '<img class="logo" src="' . esc_url($logo_url) . '">';
                        }
                        echo '<div class="person-details">';
                            echo '<strong>' . $first_name . ' ' . $last_name . '</strong><br>';
                            echo '<span class="designation">' . esc_html($item['designation']) . '</span>';
                        echo '</div>';
                    echo '</div>';
                    echo '<p class="testimonial-text">' . esc_html($item['testimonial_text']) . '</p>';
                echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        
        echo '</div>';
        echo '<div class="testimonial-slider-arrows">';
            echo '<div class="swiper-button swiper-button-prev"></div>';
            echo '<div class="swiper-button swiper-button-next"></div>';
        echo '</div>';
        ?>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper('.testimonial-slider', {
                    slidesPerView: 1,
                    loop: true,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    speed: 800,
                    navigation: {
                        nextEl: '.testimonial-slider-arrows .swiper-button-next',
                        prevEl: '.testimonial-slider-arrows .swiper-button-prev',
                    },
                    // on: {
                    //     init: updateArrowPosition,
                    //     slideChangeTransitionStart: updateArrowPosition
                    // }
                });

            });
        </script>
        
    <?php }
    
}

