<form id="filters" class="three-col">
    <div class="filter-group dropdown" id="sectors">
        <button aria-label="Filter company Sectors">Sector</button>
        <ul id="sector-filter" class="filter">
            <li>
                <button data-sector="">All Sectors</button>
            </li>
            <?php
            $sectors = get_terms(['taxonomy' => 'sector', 'hide_empty' => false]);
            foreach ($sectors as $sector) {
                echo '<li>';
                    echo '<button data-sector="' . esc_attr($sector->slug) . '">' . esc_html($sector->name) . '</button>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>

    <div class="filter-group dropdown" id="funds">
        <button aria-label="Filter company funds">Funds</button>
        <ul id="fund-filter" class="filter">
            <li>
                <button data-fund="">All Funds</button>
            </li>
            <?php
            $funds = get_terms(['taxonomy' => 'fund', 'hide_empty' => false]);
            foreach ($funds as $fund) {
                echo '<li>';
                    echo '<button data-fund="' . esc_attr($fund->slug) . '">' . esc_html($fund->name) . '</button>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>

    <div class="filter-group dropdown" id="status">
        <button aria-label="Filter company status">Status</button>
        <ul id="status-filter" class="filter">
            <li>
                <button data-status="">All Statuses</button>
            </li>
            <?php
            $statuses = get_terms(['taxonomy' => 'company-status', 'hide_empty' => false]);
            foreach ($statuses as $status) {
                echo '<li>';
                    echo '<button data-status="' . esc_attr($status->slug) . '">' . esc_html($status->name) . '</button>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</form>

<div id="companies-container" class="dynamic-container">
    <!-- Companies will be loaded here dynamically -->
</div>