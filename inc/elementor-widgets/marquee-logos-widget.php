<?php
if (!defined('ABSPATH')) exit;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Elementor_Marquee_Logos_Widget extends \Elementor\Widget_Base {


    public function get_name() { return 'marquee_logos'; }
    public function get_title() { return __('Marquee Logos', 'custom-elementor'); }
    public function get_icon() { return 'eicon-carousel'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('content_section', [
            'label' => __('Logos', 'custom-elementor'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $rep = new \Elementor\Repeater();
        $rep->add_control('image', [
            'label' => __('Logo Image', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
        ]);
        $rep->add_control('alt', [
            'label' => __('Alt text', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('logos', [
            'label'       => __('Logos', 'custom-elementor'),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $rep->get_controls(),
            'title_field' => '{{{ alt || "Logo" }}}',
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

        $this->add_control('logo_size_desktop', [
            'label' => __('Logo width (Desktop, px)', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 150,
        ]);

        $this->add_control('logo_size_mobile', [
            'label' => __('Logo width (≤1024px, px)', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => 120,
        ]);

        $this->add_control('fade_color', [
            'label' => __('Fade color', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::COLOR,
            'default' => '#FFF',
            'selectors' => [
                '{{WRAPPER}}' => '--fade-color: {{VALUE}};', // <— writes var/hex into inline style
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
        $logos = $s['logos'];
        if (empty($logos)) return;

        $uid = 'marquee-' . $this->get_id();
        $gapDesktop = (int)($s['gap_desktop'] ?? 80);
        $gapMobile  = (int)($s['gap_mobile'] ?? 40);
        $speedDesktop = (float)($s['speed_desktop'] ?? 40);
        $speedMobile  = (float)($s['speed_mobile'] ?? 50);
        $logoDesk = (int)($s['logo_size_desktop'] ?? 150);
        $logoMob  = (int)($s['logo_size_mobile'] ?? 120);
        $fadeWidth = (float)($s['fade_width'] ?? 10);

        // Wrapper + styles scoped to this widget instance
        echo '<div id="'.esc_attr($uid).'" class="logo-marquee">';

        // Primary marquee
        echo '<div class="marquee-container" style="--gap: '.esc_attr($gapDesktop).'px">';
        echo '  <div class="marquee">';
        foreach ($logos as $item) {
            $src = $item['image']['url'] ?? '';
            if (!$src) continue;
            $alt = $item['alt'] ?? '';
            echo '<img src="'.esc_url($src).'" alt="'.esc_attr($alt).'">';
        }
        echo '  </div>';

        // Duplicate marquee for seamless loop
        echo '  <div class="marquee" aria-hidden="true">';
        foreach ($logos as $item) {
            $src = $item['image']['url'] ?? '';
            if (!$src) continue;
            $alt = $item['alt'] ?? '';
            echo '<img src="'.esc_url($src).'" alt="'.esc_attr($alt).'">';
        }
        echo '  </div>';
        echo '</div>'; // .marquee-container
        echo '</div>'; // #uid
        
        static $printed_keyframes = false;
        echo '<style>';
        if (!$printed_keyframes) {
            $printed_keyframes = true;
            echo '@keyframes marquee-scroll{from{transform:translateX(0)}to{transform:translateX(calc(-100% - var(--gap)))}}';
        }
        echo '#'.esc_attr($uid).' .marquee-container{position:relative;display:flex;overflow:hidden;user-select:none;gap:var(--gap)}';
        echo '#'.esc_attr($uid).' .marquee{flex-shrink:0;display:flex;justify-content:space-around;gap:var(--gap);min-width:70vw}';
        echo '@media(min-width:1025px){';
        echo '#'.esc_attr($uid).' .marquee{animation:marquee-scroll '.esc_attr($speedDesktop).'s linear infinite}';
        echo '#'.esc_attr($uid).' .marquee.paused{animation-play-state:paused!important}';
        echo '#'.esc_attr($uid).' .marquee img{width:'.esc_attr($logoDesk).'px;max-height: 100px;aspect-ratio:1/1}';
        echo '#'.esc_attr($uid).'{position:relative}';
        echo '#'.esc_attr($uid).':before,#'.esc_attr($uid).':after{content:"";display:inline-block;position:absolute;top:0;pointer-events:none;width:'.esc_attr($fadeWidth).'%;height:100%;z-index:10}';
        echo '#'.esc_attr($uid).':before{left:0;background:linear-gradient(to right,var(--fade-color, #fff),transparent)}';
        echo '#'.esc_attr($uid).':after{right:0;background:linear-gradient(to left,var(--fade-color, #fff),transparent)}';

        echo '}';

        echo '@media(max-width:1024px){';
        echo '#'.esc_attr($uid).' .marquee{animation:marquee-scroll '.esc_attr($speedMobile).'s linear infinite}';
        echo '#'.esc_attr($uid).' .marquee-container{overflow-x:auto;-webkit-overflow-scrolling:touch;gap:'.esc_attr($gapMobile).'px}';
        echo '#'.esc_attr($uid).' .marquee-container {--gap: '.esc_attr($gapMobile).'px!important; gap: var(--gap!important);}';
        echo '#'.esc_attr($uid).' .marquee img{width:'.esc_attr($logoMob).'px;aspect-ratio:1/1}';
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
