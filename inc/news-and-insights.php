<form id="filters" class="three-col">
    <div class="filter-group dropdown" id="category-dropdown">
        <button aria-label="Filter post categories">Category</button>
        <ul id="category-filter" class="filter">
            <li>
                <button data-category="">All Categories</button>
            </li>
            <?php
            $categories = get_categories();
            foreach ($categories as $category) {
                echo '<li>';
                    echo '<button data-category="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</button>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>

    <div class="filter-group dropdown" id="tag-dropdown">
        <button aria-label="Filter post tags">Topic</button>
        <ul id="tag-filter" class="filter">
            <li>
                <button data-tag="">All Topics</button>
            </li>
            <?php
            $tags = get_tags();
            foreach ($tags as $tag) {
                echo '<li>';
                    echo '<button data-tag="' . esc_attr($tag->slug) . '">' . esc_html($tag->name) . '</button>';
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

<div id="posts-container" class="dynamic-container">
    <!-- Posts will be loaded here dynamically -->
</div>