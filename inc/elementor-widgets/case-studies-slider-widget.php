<?php
if (!defined('ABSPATH')) exit;

class Elementor_Case_Studies_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'case_studies_slider'; }
    public function get_title() { return __('Case Studies Slider', 'custom-elementor'); }
    public function get_icon() { return 'eicon-posts-group'; }
    public function get_categories() { return ['general']; }
    public function get_script_depends() { return ['swiper']; }
    public function get_style_depends() { return ['swiper']; }

    protected function register_controls() {
        $this->start_controls_section('slides_section', [
            'label' => __('Slides', 'custom-elementor'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $rep = new \Elementor\Repeater();

        $rep->add_control('city', [
            'label' => __('City', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $rep->add_control('title', [
            'label' => __('Title', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $rep->add_control('description', [
            'label' => __('Description', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::WYSIWYG,
            'rows'  => 4,
        ]);

        $rep->add_control('image', [
            'label' => __('Image', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::MEDIA,
        ]);

        $this->add_control('slides', [
            'label'       => __('Case Studies', 'custom-elementor'),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $rep->get_controls(),
            'title_field' => '{{{ title || "(untitled)" }}}',
        ]);

        $this->end_controls_section();
    }

    private function img_alt($media, $fallback='') {
        if (empty($media['id'])) return esc_attr($fallback);
        $alt = get_post_meta($media['id'], '_wp_attachment_image_alt', true);
        return esc_attr($alt ?: $fallback);
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $slides = $s['slides'] ?? [];
        if (empty($slides)) return;

        echo '<div class="slider-wrapper">';
        echo '  <div class="case-studies-slider swiper">';
        echo '    <div class="swiper-wrapper">';

        foreach ($slides as $slide) {
            $city        = trim($slide['city'] ?? '');
            $title       = trim($slide['title'] ?? '');
            $description = trim($slide['description'] ?? '');
            $image       = $slide['image'] ?? [];
            $image_url   = !empty($image['url']) ? $image['url'] : '';

            echo '      <div class="swiper-slide case-study-slide">';
            echo '        <div class="case-study-inner">';

            if ($image_url) {
                $alt = $this->img_alt($image, $title);
                echo '          <img src="'.esc_url($image_url).'" alt="'.$alt.'" class="case-study-image">';
            }

            $tag_output = $city !== '' ? esc_html($city) : '';

            echo '          <div class="case-study-content">';
            echo '            <span class="case-study-tag">'.$tag_output.'</span>';
            if ($title !== '')       echo '            <h3>'.esc_html($title).'</h3>';
            if ($description !== '') echo '    <div class="case-study-desc">'.wp_kses_post($description).'</div>';
            echo '          </div>';

            echo '        </div>';
            echo '      </div>';
        }

        echo '    </div>';
        echo '  </div>';
        echo '</div>';
        ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sliderEl = document.querySelector('.case-studies-slider');
    if (!sliderEl) return;

    const swiper = new Swiper(sliderEl, {
        slidesPerView: 3,
        loop: false,
        speed: 800,
        navigation: {
            nextEl: '.case-studies-slider-arrows .swiper-button-next',
            prevEl: '.case-studies-slider-arrows .swiper-button-prev',
        },
        breakpoints: {
            0:   { slidesPerView: 1, spaceBetween: 40, loop: true },
            575: { slidesPerView: 1, spaceBetween: 40 },
            768:{ slidesPerView: 2, spaceBetween: 40 },
            1200:{ slidesPerView: 3, spaceBetween: 40 }
        },
        on: {
            init(sw)        { updateVisibleSlides(sw); positionSliderArrows(); updateNavVisibility(sw); },
            afterInit(sw)   { positionSliderArrows(); updateNavVisibility(sw); },
            slideChange(sw) { updateVisibleSlides(sw); updateNavVisibility(sw); },
            resize(sw)      { updateVisibleSlides(sw); positionSliderArrows(); updateNavVisibility(sw); }
        }
    });

    function slidesPerViewNow() {
        const w = window.innerWidth;
        if (w < 575) return 1;
        if (w < 768) return 2;
        return 3;
    }

    function countOriginalSlides(swiper) {
        return Array.from(swiper.slides).filter(s => !s.classList.contains('swiper-slide-duplicate')).length;
    }

    function updateNavVisibility(swiper) {
        const originals = countOriginalSlides(swiper);
        const toShow    = slidesPerViewNow();

        const arrowsContainer =
            document.querySelector('.case-studies-slider-arrows--container') ||
            document.querySelector('.case-studies-slider-arrows');

        const needNav = originals > toShow;

        if (arrowsContainer) {
            arrowsContainer.style.display = needNav ? '' : 'none';
            arrowsContainer.setAttribute('aria-hidden', needNav ? 'false' : 'true');
        }

        if (swiper.navigation && swiper.params.navigation) {
            swiper.params.navigation.enabled = needNav;
            swiper.navigation.update();
        }
    }

    function updateVisibleSlides(swiper) {
        const n = slidesPerViewNow();
        const total = swiper.slides.length;

        swiper.slides.forEach(slide => slide.classList.remove('is-visible'));
        for (let i = 0; i < Math.min(n, total); i++) {
            const idx = (swiper.activeIndex + i) % total;
            const slide = swiper.slides[idx];
            if (slide) slide.classList.add('is-visible');
        }
    }

    function positionSliderArrows() {
        const sliderArrows = document.querySelector('.case-studies-slider-arrows--container');
        const sliderSlide  = document.querySelector('.case-study-slide');
        if (!sliderArrows || !sliderSlide) return;

        if (sliderArrows.style.display === 'none') return;

        const sliderSlideHeight = sliderSlide.offsetHeight;
        const sliderSlideWidth  = sliderSlide.offsetWidth;

        if (window.innerWidth < 768) {
            const prevSliderArrow = sliderArrows.querySelector('.swiper-button-prev');
            if (prevSliderArrow) {
                prevSliderArrow.setAttribute('disabled', true);
                prevSliderArrow.setAttribute('aria-hidden', true);
            }
            sliderArrows.style.top  = (sliderSlideHeight + 24) + 'px'; // 48/2
            sliderArrows.style.left = (sliderSlideWidth + 19) + 'px';  // 40 - 21
        } else {
            sliderArrows.style.top  = '';
            sliderArrows.style.left = '';
            const prevSliderArrow = sliderArrows.querySelector('.swiper-button-prev');
            if (prevSliderArrow) {
                prevSliderArrow.removeAttribute('disabled');
                prevSliderArrow.removeAttribute('aria-hidden');
            }
        }
    }
});
</script>

        <?php
    }
}
