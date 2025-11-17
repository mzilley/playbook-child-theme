<?php
if ( ! defined('ABSPATH') ) exit;

class Elementor_Before_After_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'before_after_slider'; }
    public function get_title() { return __('Before/After Slider', 'custom-elementor'); }
    public function get_icon() { return 'eicon-image-before-after'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('images_section', [
            'label' => __('Images', 'custom-elementor'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('before_image', [
            'label'   => __('Before Image', 'custom-elementor'),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
        ]);

        $this->add_control('after_image', [
            'label'   => __('After Image', 'custom-elementor'),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_section', [
            'label' => __('Style', 'custom-elementor'),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('handle_color', [
            'label'   => __('Handle Color', 'custom-elementor'),
            'type'    => \Elementor\Controls_Manager::COLOR,
            'default' => '#2F5D8A',
        ]);

        $this->add_control('track_color', [
            'label'   => __('Divider/Track Color', 'custom-elementor'),
            'type'    => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFFFFF',
        ]);

        $this->add_control('handle_size', [
            'label'   => __('Handle Size (px)', 'custom-elementor'),
            'type'    => \Elementor\Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 16, 'max' => 80]],
            'default' => ['size' => 36],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s   = $this->get_settings_for_display();
        $uid = $this->get_id();
        $root_id = 'ba-' . esc_attr($uid);

        $before = $s['before_image']['url'] ?? '';
        $after  = $s['after_image']['url'] ?? '';
        if (!$before || !$after) {
            echo '<div class="ba-empty">'.esc_html__('Add both images.', 'custom-elementor').'</div>';
            return;
        }

        $handleColor = $s['handle_color'] ?: '#2F5D8A';
        $trackColor  = $s['track_color']  ?: '#FFFFFF';
        $handleSize  = $s['handle_size']['size'] ?? 36;
        ?>
        <style>
            .elementor-widget-before_after_slider {
                border-radius: 40px;
            }
            #<?php echo $root_id; ?>.ba-slider {
                position: relative;
                overflow: hidden;
                width: 100%;
                line-height: 0;
                user-select: none;
                border-radius: 40px;
            }
            #<?php echo $root_id; ?> .ba-img,
            #<?php echo $root_id; ?> .ba-img img {
                display: block;
                width: 100%;
                height: auto;
            }
            #<?php echo $root_id; ?> .ba-top {
                position: absolute;
                inset: 0;
                overflow: hidden;
            }
            #<?php echo $root_id; ?> .ba-divider {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }
    #<?php echo $root_id; ?> .ba-divider::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: <?php echo esc_html($trackColor); ?>;
        left: var(--pos, 50%);
        transform: translateX(-50%);
    }
            #<?php echo $root_id; ?> .ba-handle {
                position: absolute;
                width: <?php echo $handleSize; ?>px;
                height: <?php echo $handleSize; ?>px;
                border-radius: 50%;
                background: <?php echo esc_html($handleColor); ?>;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                cursor: ew-resize;
            }
        </style>

        <div id="<?php echo $root_id; ?>" class="ba-slider" data-start="50">
            <div class="ba-img ba-bottom">
                <img src="<?php echo esc_url($after); ?>" alt="">
            </div>
            <div class="ba-top">
                <div class="ba-img">
                    <img src="<?php echo esc_url($before); ?>" alt="">
                </div>
            </div>
            <div class="ba-divider"></div>
            <div class="ba-handle"></div>
        </div>

        <script>
        (function(){
            const root   = document.getElementById('<?php echo $root_id; ?>');
            const top    = root.querySelector('.ba-top');
            const handle = root.querySelector('.ba-handle');
            const divider= root.querySelector('.ba-divider');

            let dragging = false;
            let pos = 50;

            function setPosition(p){
                pos = Math.max(0, Math.min(100, p));
                const w = root.clientWidth;
                const px = (pos/100) * w;
                top.style.clipPath = `inset(0 ${w - px}px 0 0)`;
                handle.style.left = px + 'px';
                divider.style.setProperty('--pos', pos + '%');
            }

            function pctFromEvent(e){
                const rect = root.getBoundingClientRect();
                const x = (e.touches ? e.touches[0].clientX : e.clientX) - rect.left;
                return (x / rect.width) * 100;
            }

            function down(e){ dragging = true; setPosition(pctFromEvent(e)); e.preventDefault(); }
            function move(e){ if (!dragging) return; setPosition(pctFromEvent(e)); }
            function up(){ dragging = false; }

            ['mousedown','touchstart'].forEach(ev => root.addEventListener(ev, down, {passive:false}));
            ['mousemove','touchmove'].forEach(ev => window.addEventListener(ev, move, {passive:false}));
            ['mouseup','mouseleave','touchend','touchcancel'].forEach(ev => window.addEventListener(ev, up));

            const ro = new ResizeObserver(() => setPosition(pos));
            ro.observe(root);

            window.requestAnimationFrame(() => setPosition(50));
        })();
        </script>
        <?php
    }
}
