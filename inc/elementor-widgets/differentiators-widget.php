<?php
if (!defined('ABSPATH')) exit;

class Elementor_Differentiators_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'differentiators';
    }

    public function get_title() {
        return __('Differentiators Grid', 'custom-elementor');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section('content_section', [
            'label' => __('Differentiators', 'custom-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new \Elementor\Repeater();

        $repeater->add_control('title', [
            'label' => __('Title', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
        ]);

        $repeater->add_control('text', [
            'label' => __('Text', 'custom-elementor'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
        ]);

        $repeater->add_control('button_text', [
            'label' => __('Button Text', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'placeholder' => __('Learn more', 'custom-elementor'),
        ]);

        $repeater->add_control('button_url', [
            'label' => __('Button Link', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::URL,
            'placeholder' => __('https://example.com', 'custom-elementor'),
            'options' => ['url', 'is_external', 'nofollow'],
            'default' => [
                'url' => '',
                'is_external' => false,
                'nofollow' => false,
            ],
        ]);

        $repeater->add_control('bg_image', [
            'label' => __('Background Image', 'custom-elementor'),
            'type'  => \Elementor\Controls_Manager::MEDIA,
        ]);

        $this->add_control('differentiators', [
            'label'       => __('Differentiators List', 'custom-elementor'),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ title || "Item" }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s   = $this->get_settings_for_display();
        $items = $s['differentiators'] ?? [];
        if (empty($items)) return;

        $uid = 'diff-' . $this->get_id();

        // Wrapper
        echo '<div id="'.esc_attr($uid).'" class="diff-wrapper">';

        // Grid
        echo '<div class="diff-grid">';

        foreach ($items as $it) {
            $title    = $it['title'] ?? '';
            $text     = $it['text'] ?? '';
            $btn_text = $it['button_text'] ?? '';
            $btn_url  = $it['button_url']['url'] ?? '';
            $bg_url   = $it['bg_image']['url'] ?? '';

            $style = $bg_url ? ' style="background-image:url('.esc_url($bg_url).'); background-position: center; background-size: 101%;"' : '';

            echo '<div class="diff-card"'.$style.'>';
                // Clip path button
                if ($title) echo '<h3 class="diff-title" aria-hidden="true">'.esc_html($title).'</h3>';

                echo '<div class="diff-overlay">';

                    if ($title) echo '<h3 class="diff-title">'.esc_html($title).'</h3>';

                    if ($text)  echo '<p class="diff-text">'.esc_html($text).'</p>';

                    if ($btn_text && $btn_url) {
                        echo '<p class="diff-actions"><a class="diff-button" href="'.esc_url($btn_url).'">'.esc_html($btn_text).'</a></p>';
                    }
                    
                    echo '<button class="diff-toggle" type="button" aria-controls="overlay-placemaking" aria-expanded="false" aria-label="Hover for more information about '.esc_html($title).' at Playbook">';
                        echo '<span aria-hidden="true"></span>';
                        echo '<span aria-hidden="true"></span>';
                    echo '</button>';
                    
                echo '</div>';

            echo '</div>';
        }

        echo '</div>'; // .diff-grid
        echo '</div>'; // .diff-wrapper

        echo '<style>
            #'.esc_attr($uid).' .diff-grid{
                display:grid;
                gap: 21px;
                grid-template-columns:1fr;
            }
            @media(min-width:768px){
             #'.esc_attr($uid).' .diff-grid{ grid-template-columns:repeat(2,1fr); }
            }
            @media(min-width:1025px){
             #'.esc_attr($uid).' .diff-grid{ grid-template-columns:repeat(3,1fr); }
            }

            #'.esc_attr($uid).' .diff-card {
                position: relative;
                background:#fff;
                border-radius:40px;
                height:100%;
                overflow: hidden;
                display:flex;
                flex-direction:column;
                aspect-ratio: 405 / 515;
            }

            #'.esc_attr($uid).' .diff-card:before {
                content: "";
                position: absolute;
                border-radius: 40px;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(90deg, rgba(0, 0, 0, 0.80) 0%, rgba(0, 0, 0, 0.0) 50%);
            }

            #'.esc_attr($uid).' .diff-card .diff-overlay {
                height: 100%;
                background-color: var(--e-global-color-accent);
                padding: 0 33px;
                border-radius: 40px;
                webkit-clip-path: circle(25px at calc(100% - 61px) calc(100% - 53px));
                clip-path: circle(25px at calc(100% - 61px) calc(100% - 53px));
                transition: -webkit-clip-path .8s ease-in-out, clip-path .8s ease-in-out;
                z-index: 10;
            }

            #'.esc_attr($uid).' .diff-card .diff-overlay:hover {
                -webkit-clip-path: circle(150% at calc(100% - 61px) calc(100% - 53px));
                clip-path: circle(150% at calc(100% - 61px) calc(100% - 53px));
            }

            #'.esc_attr($uid).' .diff-card .diff-overlay .diff-title {
                color: var(--e-global-color-primary);
                padding-top: 25px;
                padding-bottom: 10px;
                left: 33px;
            }

            #'.esc_attr($uid).' .diff-card > .diff-title {
                color: #FFF;
            }

            #'.esc_attr($uid).' .diff-card .diff-overlay:hover .diff-text, #'.esc_attr($uid).' .diff-card .diff-overlay:hover .diff-actions {
                opacity: 1;
                visibility: visible;
                pointer-events: all;
            }

            #'.esc_attr($uid).' .diff-card button {
                position: absolute;
                right: 33px;
                bottom: 25px;
                display: inline-block;
                padding: 0; 
                background-image: url(/wp-content/uploads/2025/08/playbook-plus.svg);
                background-size: 56px;
                background-repeat: no-repeat;
                border: 0;
                border-radius: 500px;
                width: 56px;
                height: 56px;
                transition: .75s cubic-bezier(.60,.01,.25,1) all;
                z-index: 10;
            }

            #'.esc_attr($uid).' .diff-card button:hover {
                transform: scale(1.1) rotate(45deg);
            }

            #'.esc_attr($uid).' .diff-title{
                font-family: "Instrument Sans";
                font-size: 32px;
                font-weight: 600;
                line-height: 1.25;
                
                margin: 0;
                transition: .3s ease-in-out all;
            }

            #'.esc_attr($uid).' .diff-card > .diff-title {
                position: absolute;
                top: 25px;
                left: 33px;
                right: 25px;
                text-shadow: 0 0 10px rgba(0,0,0,0.3);
            }

            #'.esc_attr($uid).' .diff-text{
                font-family: var(--e-global-typography-primary-font-family);
                letter-spacing: 0;
                font-style: normal;
                color: var(--e-global-color-primary);
                margin-bottom: 25px;
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                // padding-top: 75px;
                transition: .4s ease-in-out all;
            }

            #'.esc_attr($uid).' .diff-actions{
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transition: .4s ease-in-out all;
            }

            #'.esc_attr($uid).' .diff-button {
                font-family: var(--e-global-typography-primary-font-family);
                letter-spacing: 0;
                font-style: normal;
                display: flex;
                align-items: center;
                padding: 0;
                background: transparent;
                color: var(--e-global-color-primary);
                font-weight: 500;
                text-decoration: none;
                transition: .4s ease-in-out all;
            }
            #'.esc_attr($uid).' .diff-button:after {
                content: "";
                position: relative;
                margin-left: 7px;
                display: inline-block;
                width: 16px;
                height: 16px;
                mask-image: url(/wp-content/uploads/2025/08/playbook-arrow-right.svg);
                mask-size: 16px;
                mask-repeat: no-repeat;
                background-color: var(--e-global-color-primary);
                transition: .4s ease-in-out all;
            }

            #'.esc_attr($uid).' .diff-button:hover, #'.esc_attr($uid).' .diff-button:focus {
                color: var(--e-global-color-text);
            }

            #'.esc_attr($uid).' .diff-button:hover:after, #'.esc_attr($uid).' .diff-button:focus:after {
                background-color: var(--e-global-color-text);
            }
        </style>';
        

    }
}