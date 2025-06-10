<?php

if (!defined('ABSPATH')) exit;

use \Elementor\Widget_Base;

class Elementor_Company_Coverage_Widget extends Widget_Base {
    public function get_name() {
        return 'company-coverage-widget';
    }

    public function get_title() {
        return __('Company Coverage', 'your-text-domain');
    }

    public function get_icon() {
        return 'e-icon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function render() {

        $post_id = get_the_ID();
        $related_companies = get_field('managed_companies', $post_id);

         ?>

            <div class="elementor-widget-text-editor company-detail__managed-companies">
                <p><strong>Company Coverage</strong></p>
                <ul>
                    <?php foreach ($related_companies as $company) {
                        $company_title = get_the_title($company);
                        $company_logo = get_field("company_logo", $company);
                    ?>
                        <li>
                            <a href="<?php echo esc_url(get_the_permalink($company)) ?>" title="<?php echo esc_attr($company_title) ?>" aria-label="Visit <?php echo esc_attr($company_title) ?>'s company page">
                                <img src="<?php echo esc_url($company_logo['url']) ?>" alt="<?php echo esc_attr($company_title) ?>" />
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                
            </div>
        <?php
    }
}