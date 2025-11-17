<?php
if ( ! defined('ABSPATH') ) exit;

class Elementor_Team_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'team_slider';
    }

    public function get_title() {
        return __('Team Slider', 'custom-elementor');
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
            'name',
            [
                'label'       => __('Name', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => __('Name', 'custom-elementor'),
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'       => __('Title', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'rows'        => 4,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => __('Product Manager', 'custom-elementor'),
            ]
        );

        $repeater->add_control(
            'bio',
            [
                'label'       => __('Bio', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows'        => 6,
                'dynamic'     => [ 'active' => true ]
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label'   => __('Image', 'custom-elementor'),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'dynamic' => [ 'active' => true ],
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label'       => __('Slides', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ name || "Slide" }}}',
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
            echo '<div class="team-slider-empty">'.esc_html__('Add at least one slide.', 'custom-elementor').'</div>';
            return;
        }

        $root_id         = 'team-slider-' . esc_attr($uid);
        $swiper_selector = '#' . $root_id . ' .team-slider';
        $nav_next        = '#' . $root_id . ' .team-slider-arrows .swiper-button-next';
        $nav_prev        = '#' . $root_id . ' .team-slider-arrows .swiper-button-prev';
        ?>
        
        <div class="team-slider-inner">
            <div class="swiper team-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide) :
                        $img  = isset($slide['image']['url']) ? $slide['image']['url'] : '';
                        $name = isset($slide['name']) ? $slide['name'] : '';
                        $desc  = isset($slide['description']) ? $slide['description'] : '';
                        $title  = isset($slide['title']) ? $slide['title'] : '';
                        $bio  = isset($slide['bio']) ? $slide['bio'] : '';
                    ?>
                    <div class="swiper-slide team-slide">
                        <div class="team-slide__image">
                        <?php if ($img): ?>
                            <img src="<?php echo esc_url($img) ?>" alt="<?php echo esc_attr($name) ?>">
                        <?php endif; ?>
                        </div>
                        <div class="team-slide__content">
                            <?php if ($bio): ?>
                                <div class="team-slide__bio">
                                    <?php echo wp_kses_post(wpautop($bio)); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($name): ?>
                                <h3 class="team-slide__name"><?php echo esc_html($name); ?></h3>
                            <?php endif; ?>
                            <?php if ($title): ?>
                                <h3 class="team-slide__title"><?php echo esc_html($title); ?></h3>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="team-slider-arrows" id="team-slider-arrows">
                <div class="swiper-button swiper-button-prev" aria-label="<?php esc_attr_e('Previous', 'custom-elementor'); ?>"></div>
                <div class="swiper-button swiper-button-next" aria-label="<?php esc_attr_e('Next', 'custom-elementor'); ?>"></div>
            </div>
        </div>

        <script>

        document.addEventListener('DOMContentLoaded', function () {

            new Swiper('.team-slider', {
                spaceBetween: 40,
                slidesPerView: 1,
                centerMode: true,
                autoHeight: true,
                loop: true,
                speed: 800,
                navigation: {
                    nextEl: '.team-slider-arrows .swiper-button-next',
                    prevEl: '.team-slider-arrows .swiper-button-prev',
                }
            });
        });
        </script>
        <?php
    }
}
