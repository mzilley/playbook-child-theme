<?php
if ( ! defined('ABSPATH') ) exit;

class Elementor_Timeline_Widget extends \Elementor\Widget_Base {
    public function get_name(){ return 'timeline_grid'; }
    public function get_title(){ return __('Timeline Grid', 'your-theme'); }
    public function get_icon(){ return 'eicon-slider-push'; }
    public function get_categories(){ return ['general']; }
    public function get_script_depends(){ return ['timeline-grid']; }
    public function get_style_depends(){ return ['timeline-grid']; }

    protected function register_controls() {

        $this->start_controls_section('section_grid', [
            'label' => __('Grid', 'your-theme'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('start_year', [
            'label' => __('Start Year', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
        ]);

        $this->add_control('end_year', [
            'label' => __('End Year', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_cards', [
            'label' => __('Cards', 'your-theme'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $rep = new \Elementor\Repeater();

        $rep->add_control('year', [
            'label' => __('Year', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::NUMBER,
            'default' => date('Y'),
        ]);
        $rep->add_control('month', [
            'label' => __('Month (e.g., APRIL)', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'default' => 'JANUARY',
        ]);
        $rep->add_control('offset', [
            'label' => __('Vertical Offset', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => -400, 'max' => 1600, 'step' => 1]],
            'default' => ['size' => 0, 'unit' => 'px'],
        ]);
        $rep->add_control('h_offset_cols', [
            'label'       => __('Horizontal Offset (cols 0–3)', 'your-theme'),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'min'         => 0,
            'max'         => 3,
            'default'     => 0,
            'description' => __('Shifts the card right inside its year rail by N columns. Does not change width.', 'your-theme'),
        ]);
        $rep->add_control('h_span_cols', [
            'label'       => __('Horizontal Span into Next Year (cols 0–4)', 'your-theme'),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'min'         => 0,
            'max'         => 4,
            'default'     => 0,
            'description' => __('Extends the card\'s width by N columns into the next year rail (visual only).', 'your-theme'),
        ]);

        $rep->add_control('extend_to_year', [
            'label'       => __('Extend to Year (optional)', 'your-theme'),
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'description' => __('Card will visually move toward this year. Adds the year rail if missing.', 'your-theme'),
        ]);
        
        $rep->add_control('image', [
            'label' => __('Image', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::MEDIA,
        ]);
        $rep->add_control('title', [
            'label' => __('Title', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
        ]);
        $rep->add_control('label', [
            'label' => __('Label', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
        ]);
        $rep->add_control('icon', [
            'label' => __('Icon', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => '',
                'library' => 'solid',
            ],
        ]);
        $rep->add_control('playbook_bried', [
            'label' => __('Playbook Brief', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::TEXTAREA,
            'rows'  => 3,
        ]);

        $rep->add_control('competitor_brief', [
            'label' => __('Competitor Brief', 'your-theme'),
            'type'  => \Elementor\Controls_Manager::WYSIWYG,
            'rows'  => 3,
        ]);

        $this->add_control('items', [
            'label'       => __('Timeline Items', 'your-theme'),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $rep->get_controls(),
            'title_field' => '{{{ title || "(untitled)" }}}',
        ]);

        $this->end_controls_section();
    }

    private function img_alt($media, $fallback=''){
        if (empty($media['id'])) return esc_attr($fallback);
        $alt = get_post_meta($media['id'], '_wp_attachment_image_alt', true);
        return esc_attr( $alt ?: $fallback );
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $colsPerYear = 3;
    
        $yearsWithCards = [];
        if (!empty($s['items'])) {
            foreach ($s['items'] as $it) {
                $y = isset($it['year']) ? (int)$it['year'] : 0;
                if ($y) {
                    if (!isset($yearsWithCards[$y])) $yearsWithCards[$y] = 0;
                    $yearsWithCards[$y]++;
                }
            }
        }
    
        if (empty($yearsWithCards)) {
            echo '<section class="tl-grid-wrap" role="group" aria-label="'.esc_attr__('Timeline','your-theme').'">';
            echo '<div class="tl-grid"></div></section>';
            return;
        }
    
        $railYears = array_keys($yearsWithCards);
        sort($railYears, SORT_NUMERIC);
    
        echo '<section class="tl-grid-wrap" role="group" aria-label="'.esc_attr__('Timeline','your-theme').'">';
        echo '<div class="tl-grid">';
    
        foreach ($railYears as $railIndex => $year) {
    
            $cardsForYear = (int)$yearsWithCards[$year];
    
            $railsCount = max(4, 4 * max(1, $cardsForYear));
    
            echo '<div class="tl-year-rail">';
            echo   '<span class="tl-year-label">'.esc_html($year).'</span>';
            echo   '<span class="tl-rails" aria-hidden="true" data-rails="'.$railsCount.'" style="--rails: '.$railsCount.';">';
            for ($i = 0; $i < $railsCount; $i++) {
                echo '<span class="tl-rail" aria-hidden="true"></span>';
            }
            echo   '</span>';
    
            echo '<div class="tl-cards-wrapper">';
            if (!empty($s['items'])) {
                $idx = 0;
                foreach ($s['items'] as $item) {
                    if ((int)($item['year'] ?? 0) !== (int)$year) continue;
                    $idx++;
    
                    $vOffset   = isset($item['offset']['size']) ? (float)$item['offset']['size'] : 0;
                    $withinCols = isset($item['h_offset_cols']) ? (int)$item['h_offset_cols'] : 0;
    
                    $targetYear  = !empty($item['extend_to_year']) ? (int)$item['extend_to_year'] : $year;
                    $targetIndex = array_search($targetYear, $railYears, true);
                    $deltaRails  = ($targetIndex !== false) ? max(0, $targetIndex - $railIndex) : 0;
                    $totalCols   = $deltaRails * $colsPerYear + $withinCols;
    
                    echo '<article class="tl-card" style="margin-top: '.$vOffset.'px; --hoffset: '.$withinCols.';" role="article">';
                        echo '<div class="tl-body">';
                            echo '<div class="brief playbook-brief">';
                                if (!empty($item['label']) || !empty($item['icon']['value'])) {
                                    echo '<div class="tl-date-wrapper" style="display: flex; justify-content: space-between; align-items: center;">';
                                    if (!empty($item['label'])) {
                                        echo '<div class="tl-date" aria-label="'.esc_attr($item['label']).'">'.esc_html($item['label']).'</div>';
                                    }
                                    if (!empty($item['icon']['value'])) {
                                        echo '<div class="tl-icon">';
                                        \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']);
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                if (!empty($item['title'])) echo '<h3 class="tl-title">'.esc_html($item['title']).'</h3>';
                                if (!empty($item['playbook_bried'])) echo '<p class="tl-brief">'.esc_html($item['playbook_bried']).'</p>';
                            echo '</div>';
    
                            echo '<div class="brief competitor-brief">';
                                if (!empty($item['competitor_brief'])) {
                                    echo '<div class="tl-brief">'.$this->parse_text_editor($item['competitor_brief']).'</div>';
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</article>';
                }
            }
            echo '</div>';
            echo '</div>';
        }
    
        echo '</div></section>';
    }
    
}
