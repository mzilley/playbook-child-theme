<form id="filters" class="two-col">
    <div class="filter-group dropdown">
        <button aria-label="Filter team members by team">All Employees</button>
        <ul id="team-filter" class="filter">
            <li>
                <button data-team="">All Employees</button>
            </li>
            <?php
            $teams = get_terms(['taxonomy' => 'team', 'hide_empty' => false]);
            foreach ($teams as $team) {
                echo '<li>';
                    echo '<button data-team="' . esc_attr($team->slug) . '">' . esc_html($team->name) . '</button>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>

    <div class="filter-group search">
        <input type="text" id="search-input" placeholder="Search">
        <button type="button" id="filter-submit" aria-label="Search Team Members"><span class="sr-only">Search</span></button>
        <button type="button" id="filter-reset" aria-label="Reset Team Member Search"><span class="sr-only">Reset Search</span></button>
    </div>

</form>

<div id="team-container" class="dynamic-container">
    <!-- Team members will be loaded here dynamically -->
</div>