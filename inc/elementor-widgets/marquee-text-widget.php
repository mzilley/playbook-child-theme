<?php
if (!defined('ABSPATH')) exit;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Elementor_Marquee_Text_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'marquee_text'; }
    public function get_title() { return __('Marquee Text', 'custom-elementor'); }
    public function get_icon() { return 'eicon-editor-bold'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        // Text input
        $this->start_controls_section('content_section', [
            'label' => __('Text', 'custom-elementor'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('marquee_text', [
            'label' => __('Text Content', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => 'Text <em>Goes Here</em>',
            'dynamic' => ['active' => true],
        ]);

        $this->end_controls_section();

        // Settings
        $this->start_controls_section('settings_section', [
            'label' => __('Settings', 'custom-elementor'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('gap_desktop', [
            'label' => __('Gap (Desktop)', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 80,
        ]);

        $this->add_control('gap_mobile', [
            'label' => __('Gap (≤1024px)', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 40,
        ]);

        $this->add_control('speed_desktop', [
            'label' => __('Scroll Duration in seconds (Desktop)', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 40,
        ]);

        $this->add_control('speed_mobile', [
            'label' => __('Scroll Duration in seconds (≤1024px)', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 50,
        ]);

        $this->add_control('fade_color', [
            'label' => __('Fade color', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFF',
            'selectors' => [
                '{{WRAPPER}}' => '--fade-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('fade_width', [
            'label' => __('Fade width (%)', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 10,
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $text = $s['marquee_text'] ?? '';
        if (empty($text)) return;

        $uid = 'marquee-' . $this->get_id();
        $gapDesktop = (int)($s['gap_desktop'] ?? 80);
        $gapMobile  = (int)($s['gap_mobile'] ?? 40);
        $speedDesktop = (float)($s['speed_desktop'] ?? 40);
        $speedMobile  = (float)($s['speed_mobile'] ?? 50);
        $fadeWidth = (float)($s['fade_width'] ?? 10);

        echo '<div id="'.esc_attr($uid).'" class="text-marquee">';

        // Primary marquee
        echo '<div class="marquee-container" style="--gap: '.esc_attr($gapDesktop).'px">';
        echo '  <div class="marquee">'. $text .'</div>';

        // Duplicate marquee for seamless loop
        echo '  <div class="marquee" aria-hidden="true">'. $text .'</div>';
        echo '  <div class="marquee" aria-hidden="true">'. $text .'</div>';
        echo '  <div class="marquee" aria-hidden="true">'. $text .'</div>';
        echo '  <div class="marquee" aria-hidden="true">'. $text .'</div>';
        echo '</div>'; // .marquee-container
        echo '</div>'; // #uid

        static $printed_keyframes = false;
        echo '<style>';
        if (!$printed_keyframes) {
            $printed_keyframes = true;
            echo '@keyframes marquee-scroll{from{transform:translateX(0)}to{transform:translateX(calc(-100% - var(--gap)))}}';
        }

        echo '#'.esc_attr($uid).' .marquee-container{position:relative;display:flex;overflow:hidden;user-select:none;gap:var(--gap)}';
        echo '#'.esc_attr($uid).' .marquee{flex-shrink:0;white-space:nowrap;gap:var(--gap);display:inline-block; font-size: clamp(32px, 3vw, 48px); color: #716F6F; font-weight: 500;}';

        echo '@media(min-width:1025px){';
        echo '#'.esc_attr($uid).' .marquee{animation:marquee-scroll '.esc_attr($speedDesktop).'s linear infinite}';
        echo '#'.esc_attr($uid).' .marquee.paused{animation-play-state:paused!important}';
        echo '#'.esc_attr($uid).'{position:relative}';
        echo '#'.esc_attr($uid).':before,#'.esc_attr($uid).':after{content:"";display:inline-block;position:absolute;top:0;pointer-events:none;width:'.esc_attr($fadeWidth).'%;height:100%;z-index:10}';
        echo '#'.esc_attr($uid).':before{left:0;background:linear-gradient(to right,var(--fade-color, #fff),transparent)}';
        echo '#'.esc_attr($uid).':after{right:0;background:linear-gradient(to left,var(--fade-color, #fff),transparent)}';
        echo '}';

        echo '@media(max-width:1024px){';
        echo '#'.esc_attr($uid).' .marquee{animation:marquee-scroll '.esc_attr($speedMobile).'s linear infinite}';
        echo '#'.esc_attr($uid).' .marquee[aria-hidden="true"]{display:none}';
        echo '#'.esc_attr($uid).' .marquee-container{overflow-x:auto;-webkit-overflow-scrolling:touch;gap:'.esc_attr($gapMobile).'px}';
        echo '}';

        echo '@media(prefers-reduced-motion:reduce){#'.esc_attr($uid).' .marquee{animation-play-state:paused!important}}';
        echo '</style>';

        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function(){
            const root = document.getElementById('<?php echo esc_js($uid); ?>');
            if(!root) return;
            const container = root.querySelector('.marquee-container');
            const rows = root.querySelectorAll('.marquee');
            const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (prefersReduced) {
                rows.forEach(m => m.classList.add('paused'));
            }
        });
        </script>
        <?php
    }
}
