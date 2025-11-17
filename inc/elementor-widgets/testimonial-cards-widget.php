<?php
if ( ! defined('ABSPATH') ) exit;

class Elementor_Testimonial_Cards_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'testimonials_cards_centered'; }
    public function get_title() { return __('Testimonials Cards)', 'custom-elementor'); }
    public function get_icon() { return 'eicon-testimonial-carousel'; }
    public function get_categories() { return ['general']; }

    public function get_script_depends() { return ['swiper']; }
    public function get_style_depends()  { return ['swiper']; }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Slides', 'custom-elementor'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $rep = new \Elementor\Repeater();

        $rep->add_control(
            'slide_headline',
            [
                'label'       => __('Headline', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => __('“Great partnership and amazing results.”', 'custom-elementor'),
            ]
        );
        $rep->add_control(
            'slide_testimonial',
            [
                'label'       => __('Testimonial', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows'        => 4,
                'dynamic'     => [ 'active' => true ],
            ]
        );
        $rep->add_control(
            'slide_name',
            [
                'label'       => __('Name', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => __('Jane Doe', 'custom-elementor'),
            ]
        );
        $rep->add_control(
            'slide_title',
            [
                'label'       => __('Title', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic'     => [ 'active' => true ],
                'default'     => __('VP, Company', 'custom-elementor'),
            ]
        );

        $this->add_control(
            'slides',
            [
                'label'       => __('Slides', 'custom-elementor'),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $rep->get_controls(),
                'title_field' => '{{{ slide_name || "Slide" }}}',
                'default'     => [],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s   = $this->get_settings_for_display();
        $arr = isset($s['slides']) && is_array($s['slides']) ? $s['slides'] : [];
        if (empty($arr)) {
            echo '<div class="testimonials-slider-empty">'.esc_html__('Add at least one slide.', 'custom-elementor').'</div>';
            return;
        }

        $uid   = $this->get_id();
        $root  = 'testimonials-slider-' . esc_attr($uid);
        $wrap  = '#' . $root;
        $swSel = $wrap . ' .testimonial-cards--swiper';
        $navN  = $wrap . ' .testimonial-arrows .swiper-button-next';
        $navP  = $wrap . ' .testimonial-arrows .swiper-button-prev';
        ?>

        <div id="<?php echo $root; ?>" class="testimonials-centered-wrap">
            <style>
                <?php echo $root; ?> { --t-pad:28px; --t-radius:24px; --t-space:32px; }
                <?php echo $root; ?> .testimonial-swiper{ padding:40px 6vw; }
                <?php echo $root; ?> .swiper-slide{ height:auto; width: clamp(260px, 32vw, 420px); transition: transform .35s ease, box-shadow .35s ease, opacity .35s ease; opacity:.65; }
                <?php echo $root; ?> .swiper-slide-active{ transform: translateY(-6px); box-shadow:0 14px 38px rgba(0,0,0,.18); opacity:1; }

                <?php echo $root; ?> .tslide{ position:relative; border-radius:var(--t-radius); overflow:hidden; }

                <?php echo $root; ?> .tslide-card .tslide-bg{ filter:grayscale(10%) brightness(.7); opacity:.12; }
                <?php echo $root; ?> .tslide-card{ position:relative; background:#f3f1ec; border-radius:var(--t-radius); margin:12px; box-shadow:0 8px 24px rgba(0,0,0,.12); overflow:hidden; }
                <?php echo $root; ?> .tslide-card .tslide-inner{ position:relative; padding:calc(var(--t-pad) + 6px); color:#20201e; }
                <?php echo $root; ?> .tslide-quote{ position:absolute; top:var(--t-pad); left:var(--t-pad); width:36px; height:36px; opacity:.9; background-repeat:no-repeat; background-size:contain;
                    background-image:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="%2322313a"><path d="M10 6H4v8h6V6zm10 0h-6v8h6V6z"/></svg>');
                }

                <?php echo $root; ?> .tslide-headline{ font-size:clamp(1.1rem,1.2rem + .6vw,1.6rem); line-height:1.25; margin:0 0 12px; font-weight:700; }
                <?php echo $root; ?> .tslide-body{ font-size:clamp(.95rem,.9rem + .3vw,1.05rem); line-height:1.6; margin:0 0 18px; }
                <?php echo $root; ?> .tslide-meta{ font-size:.95rem; opacity:.95; }
                <?php echo $root; ?> .tslide-name{ font-weight:700; }
                <?php echo $root; ?> .tslide-title{ opacity:.85; }

                /* Arrows */
                <?php echo $root; ?> .testimonial-arrows .swiper-button-prev,
                <?php echo $root; ?> .testimonial-arrows .swiper-button-next{ color:#22313a; }
            </style>

            <div class="swiper testimonial-cards--swiper" aria-label="<?php esc_attr_e('Testimonials', 'custom-elementor'); ?>">
                <div class="swiper-wrapper">
                    <?php
                    $i = 0;
                    foreach ($arr as $slide) :
                        $i++;
                        $img   = isset($slide['slide_image']['url']) ? $slide['slide_image']['url'] : '';
                        $head  = isset($slide['slide_headline']) ? $slide['slide_headline'] : '';
                        $body  = isset($slide['slide_testimonial']) ? $slide['slide_testimonial'] : '';
                        $name  = isset($slide['slide_name']) ? $slide['slide_name'] : '';
                        $title = isset($slide['slide_title']) ? $slide['slide_title'] : '';
                    ?>
                        <article class="swiper-slide testimonial-cards--slide variant-card" data-bg="<?php echo esc_url($img); ?>">
                            <div class="testimonial-card">
                                <span class="testimonial-card--quote" aria-hidden="true"></span>
                                <div class="testimonial-card--inner">
                                    <?php if ($head): ?><h3 class="testimonial-card--headline"><?php echo esc_html($head); ?></h3><?php endif; ?>
                                    <?php if ($body): ?><p class="testimonial-card--body"><?php echo esc_html($body); ?></p><?php endif; ?>
                                    <div class="testimonial-card--meta">
                                        <?php if ($name): ?><div class="testimonial-card--name"><?php echo esc_html($name); ?></div><?php endif; ?>
                                        <?php if ($title): ?><div class="testimonial-card--title"><?php echo esc_html($title); ?></div><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                                
                        </article>
                    <?php endforeach; ?>
                </div>
            
            </div>

            <div class="testimonial-card-arrows" id="testimonial-card-arrows">
                <div class="swiper-button swiper-button-prev" aria-label="<?php esc_attr_e('Previous', 'custom-elementor'); ?>"></div>
                <div class="swiper-button swiper-button-next" aria-label="<?php esc_attr_e('Next', 'custom-elementor'); ?>"></div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function(){

            new Swiper('.testimonial-cards--swiper', {
                speed: 800,
                loop: true,
                centeredSlides: true,
                slidesPerView: 3,
                spaceBetween: 40,
                navigation: {
                    nextEl: '.testimonial-card-arrows .swiper-button-next',
                    prevEl: '.testimonial-card-arrows .swiper-button-prev',
                },
                grabCursor: true,
                breakpoints: {
                    0:  { slidesPerView: 1, spaceBetween: 40 },
                    576:  { slidesPerView: 1.5, spaceBetween: 40 },
                    1200: { slidesPerView: 3,   spaceBetween: 40 }
                }
            });
        });
        </script>
        <?php
    }
}
