window.addEventListener('load', function() {
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "start" });

        }
    }
})

window.addEventListener('DOMContentLoaded', function () {

    function autoplaySliders() {
        const insightsSliders = document.querySelectorAll('.elementor-widget-testimonial-carousel .swiper');
        const testSliders = document.querySelectorAll('.elementor-widget-loop-carousel .swiper');

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.target.swiper) {
                            const swiperInstance = entry.target.swiper;
                            swiperInstance.params.autoplay.delay = 7500;
                            entry.target.swiper.update();
                            if (entry.isIntersecting) {
                                entry.target.swiper.autoplay.start();
                            } else {
                                entry.target.swiper.autoplay.stop();
                            }
                        }
                    });
                },
                { threshold: 0.5 }
            );

            testSliders.forEach((slider) => {
                if (slider.swiper) {
                    observer.observe(slider);
                }
            });
        }

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.target.swiper) {
                            const swiperInstance = entry.target.swiper;
                            swiperInstance.params.autoplay.delay = 4000;
                            entry.target.swiper.update();
                            if (entry.isIntersecting) {
                                entry.target.swiper.autoplay.start();
                            } else {
                                entry.target.swiper.autoplay.stop();
                            }
                        }
                    });
                },
                { threshold: 0.5 }
            );

            insightsSliders.forEach((slider) => {
                if (slider.swiper) {
                    observer.observe(slider);
                }
            });
        }
    }

    window.addEventListener("elementor/frontend/init", function () {
        setTimeout(autoplaySliders, 300);
    });

    var header = document.getElementById('header');
    if (header) {
        var headerHeight = header.offsetHeight;
        var lastScrollTop = 0;

        function toggleHeader() {
            var st = window.pageYOffset || document.documentElement.scrollTop;
            if (window.innerWidth > 1024) {

                // If scrolled to the top of the page
                if (st <= 133) {
                    header.classList.add('top');
                } else {
                    header.classList.remove('top');
                }

                // Scroll down
                if (st > lastScrollTop && st > headerHeight) {
                    // Scroll Down
                    header.classList.add('hidden');
                    header.classList.remove('show');
                } else {
                    // Scroll Up or at the top of the page
                    header.classList.remove('hidden');
                    header.classList.add('show');
                }
                lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling    
            } else {
                header.classList.remove('hidden');
                if (st <= 0) {
                    header.classList.add('top');
                } else {
                    header.classList.remove('top');
                }
            }
        }

        // Event listener for scroll events
        window.addEventListener('scroll', toggleHeader);

        // Event listener for load event (in case the user loads the page already scrolled down)
        window.addEventListener('load', toggleHeader);
    }

    const elementorToggle = document.querySelector('.elementor-menu-toggle');
    const body = document.body;
    elementorToggle.addEventListener('click', function () {
        if (this.classList.contains('elementor-active')) {
            // remove backdrop
            body.classList.remove('menu-open');
            document.querySelector('.menu-backdrop').classList.remove('js-active');
        } else {
            // add backdrop
            document.body.classList.add('menu-open');
            if (!document.querySelector('.menu-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.classList.add('menu-backdrop');
                body.appendChild(backdrop);
                setTimeout(function () {
                    backdrop.classList.add('js-active');
                }, 50);
            } else {
                document.querySelector('.menu-backdrop').classList.add('js-active');
            }
        }
    });

    // Menu Functionality
    const menus = document.querySelectorAll('.elementor-nav-menu--main')

    menus.forEach(function (menu) {
        const liDropdowns = menu.querySelectorAll('li.menu-item-has-children');
        liDropdowns.forEach(function (dropdown) {
            dropdown.addEventListener('mouseenter', function () {
                dropdown.querySelector('ul.sub-menu').classList.add('js-active');
                dropdown.querySelector('ul.sub-menu').setAttribute('aria-expanded', true);
                dropdown.querySelector('ul.sub-menu').setAttribute('aria-hidden', false);
            })
            dropdown.addEventListener('mouseleave', function () {
                dropdown.querySelector('ul.sub-menu').classList.remove('js-active');
                dropdown.querySelector('ul.sub-menu').setAttribute('aria-expanded', false);
                dropdown.querySelector('ul.sub-menu').setAttribute('aria-hidden', true);

            })

        })
    })

    // Set social icons based on text
    document.querySelectorAll('.footer-social ul li').forEach(function (social) {
        var socialLink = social.querySelector('a')
        var socialText = socialLink.textContent;
        if (socialText) {
            socialLink.title = socialText;
            socialLink.classList.add('social-icon', socialText.toLowerCase());
        }
    })

    // Handle Filter Dropdown Clicks (Posts, Companies, Team Members)
    const filterDropdowns = document.querySelectorAll('.filter-group.dropdown');

    if (filterDropdowns.length) {
        filterDropdowns.forEach(function (dropdown) {

            const button = dropdown.querySelector('button');

            button.addEventListener('click', function (e) {
                e.preventDefault();
                button.parentNode.classList.contains('js-active') ? button.parentNode.classList.remove('js-active') : button.parentNode.classList.add('js-active');
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                    dropdown.classList.remove('js-active');
                }
            });

        })
    }

    // NEWS & INSIGHTS
    if (document.body.classList.contains('blog')) {
        const postsContainer = document.getElementById("posts-container");
        const categoryFilter = document.getElementById("category-filter");
        const tagFilter = document.getElementById("tag-filter");
        const categoryFilterButton = document.querySelector("#category-dropdown > button");
        const topicFilterButton = document.querySelector("#tag-dropdown > button");
        const searchInput = document.getElementById("search-input");
        const filterSubmit = document.getElementById("filter-submit");
        const filterReset = document.getElementById("filter-reset");

        const postParams = new URLSearchParams(window.location.search);
        
        let selectedCategory = postParams.get("category") || sessionStorage.getItem("category") || '';
        let selectedTag = postParams.get("tag") || sessionStorage.getItem("tag") || '';
        let searchValue = postParams.get("news-search") || sessionStorage.getItem("news-search");

        updateURL()

        if (selectedCategory && selectedCategory !== null) sessionStorage.setItem("category", selectedCategory);
        console.log(selectedCategory)
        const selectedCategoryButton = categoryFilter.querySelector(`button[data-category="${selectedCategory}"]`);
        if (selectedCategoryButton) {
            selectedCategoryButton.parentElement.parentElement.previousElementSibling.textContent = selectedCategoryButton.textContent;
            updateActiveClass(categoryFilter, selectedCategoryButton);
        }

        if (selectedTag && selectedTag !== null) sessionStorage.setItem("tag", selectedTag);
        const selectedTagButton = tagFilter.querySelector(`button[data-tag="${selectedTag}"]`);
        if (selectedTagButton) {
            selectedTagButton.parentElement.parentElement.previousElementSibling.textContent = selectedTagButton.textContent;
            updateActiveClass(tagFilter, selectedTagButton);
        }

        if (searchValue && searchValue !== null) {
            sessionStorage.setItem("news-search", searchValue);
            searchInput.value = searchValue;
            filterReset.classList.add('show');
        }

        // Handle category selection
        categoryFilter.addEventListener('click', function (e) {
            e.preventDefault();
            const button = e.target.closest("button");

            if (!button) return;
            selectedCategory = button.getAttribute("data-category");

            if (selectedCategory && selectedCategory !== null) {
                postParams.set("category", selectedCategory);
                sessionStorage.setItem("category", selectedCategory);
            } else {
                postParams.delete("category");
                sessionStorage.removeItem("category");
            }

            categoryFilter.previousElementSibling.textContent = button.textContent;
            updateActiveClass(categoryFilter, button);
            button.parentNode.parentNode.parentNode.classList.remove("js-active");

            loadPosts();
        });

        // Handle tag selection
        tagFilter.addEventListener('click', function (e) {
            e.preventDefault();
            const button = e.target.closest("button");

            if (!button) return;
            selectedTag = button.getAttribute("data-tag");

            if (selectedTag && selectedTag !== null) {
                postParams.set("tag", selectedTag);
                sessionStorage.setItem("tag", selectedTag);
            } else {
                postParams.delete("tag");
                sessionStorage.removeItem("tag");
            }

            tagFilter.previousElementSibling.textContent = button.textContent;
            updateActiveClass(tagFilter, button);
            button.parentNode.parentNode.parentNode.classList.remove("js-active");

            loadPosts();
        });

        function loadPosts() {
            // currentPage = 0;
            // totalPages = null;
            postsContainer.innerHTML = "";
            fetchPosts()
        }

        // Function to fetch posts
        function fetchPosts(paged = 1, paginationScroll = false) {
            const formData = new FormData();
            formData.append("action", "fetch_filtered_posts");
            formData.append("category", selectedCategory || "");
            formData.append("tag", selectedTag || "");
            formData.append("search", searchInput.value || "");
            formData.append("paged", paged);

            updateURL()

            postsContainer.style.opacity = '0';

            fetch(ajaxurl.url, {
                method: "POST",
                body: formData,
            })
                .then((response) => response.text())
                .then((html) => {


                    setTimeout(function () {
                        postsContainer.innerHTML = html;
                        postsContainer.style.opacity = '1';
                        attachPaginationListeners();

                        if (paginationScroll) {
                            const headerOffset = 235;
                            const elementPosition = postsContainer.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }


                    }, 300);



                    const articles = postsContainer.querySelectorAll('article');
                    articles.forEach((article) => {
                        setTimeout(() => {
                            article.classList.add('fade-in');
                            article.classList.add('js-visible');
                        }, 500);
                    });
                })
                .catch((error) => {
                    console.error("Error fetching posts:", error);
                    postsContainer.innerHTML = "<p>Error loading posts.</p>";
                });
        }

        function search() {
            let inputValue = searchInput.value.trim();
            if (!inputValue) {
                const errorMessage = document.createElement("span");
                errorMessage.classList.add("js-error");
                errorMessage.textContent = "Please enter a search term";
                filterSubmit.parentNode.appendChild(errorMessage);
                return;
            }

            if (inputValue) {
                sessionStorage.setItem("news-search", inputValue);
                postParams.set("news-search", inputValue);
            } else {
                sessionStorage.removeItem("news-search");
                postParams.delete("news-search");
            }

            // console.log("Search Stored:", inputValue);
            filterReset.classList.add("show");

            if (document.querySelector(".js-error")) {
                document.querySelector(".js-error").remove();
            }

            loadPosts();
        }

        searchInput.addEventListener('keyup', function() {
            if (searchInput.value === "") {
                postParams.delete("news-search");
                sessionStorage.removeItem("news-search");
                loadPosts();
            }
            if (this.value.trim() && document.querySelector(".js-error")) {
                document.querySelector(".js-error").remove();
            }
        })

        function resetSearch() {
            filterReset.classList.remove("show");
            searchInput.value = "";
            postParams.delete("news-search");
            sessionStorage.removeItem("news-search");
            loadPosts();
        }

        filterSubmit.addEventListener("click", function (e) {
            e.preventDefault();
            search();
        });

        filterReset.addEventListener("click", function (e) {
            e.preventDefault();
            resetSearch();
        });

        searchInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                search();
            }
        });

        filterSubmit.addEventListener("click", function (e) {
            e.preventDefault();
            search()
        });

        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                search();
            }
        });

        searchInput.addEventListener('keyup', function (e) {
            if (this.value && document.querySelector('.js-error')) {
                document.querySelector('.js-error').remove();
            }
        })

        function attachPaginationListeners() {
            const paginationBtns = document.querySelectorAll('.page-link');
            paginationBtns.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    fetchPosts(btn.getAttribute('data-page'), true);
                });
            })
        }

        const dropdownButtons = document.querySelectorAll('.dropdown > button');

        dropdownButtons.forEach(function (btn) {
            btn.addEventListener('keydown', function (e) {
                if (btn.parentNode.classList.contains('js-active')) {
                    const key = e.key.toLowerCase();
                    if (key === 'tab' && !e.shiftKey) {
                        e.preventDefault();
                        const firstListItem = btn.nextElementSibling.querySelector('li button');
                        if (firstListItem) {
                            firstListItem.focus();
                        }
                    }
                }

            });
        });

        const dropdownLists = document.querySelectorAll('.dropdown ul');

        dropdownLists.forEach(function (list) {
            const listItems = list.querySelectorAll('li button');
            listItems.forEach(function (item, index) {
                item.setAttribute('tabindex', '0'); // Make list items focusable
                item.addEventListener('keydown', function (e) {
                    const key = e.key.toLowerCase();
                    if (key === 'tab' && !e.shiftKey) {
                        e.preventDefault();
                        if (index === listItems.length - 1) {
                            // If it's the last item, move focus back to the button
                            list.previousElementSibling.focus();
                        } else {
                            // Otherwise, move focus to the next item
                            listItems[index + 1].focus();
                        }
                    } else if (key === 'tab' && e.shiftKey) {
                        e.preventDefault();
                        if (index === 0) {
                            // If it's the first item, move focus back to the button
                            list.previousElementSibling.focus();
                        } else {
                            // Otherwise, move focus to the previous item
                            listItems[index - 1].focus();
                        }
                    }
                });
            });
        });

        function updateActiveClass(parent, target) {
            parent.querySelectorAll('button').forEach((btn) => btn.classList.remove('js-active'));
            target.classList.add('js-active');
        }

        function updateURL() {
            let urlParams = new URLSearchParams();
            let categoryParam = sessionStorage.getItem("category");
            let topicParam = sessionStorage.getItem("tag");
            let searchParam = sessionStorage.getItem("news-search");
            let url;

            if (categoryParam) urlParams.set("category", categoryParam);
            if (topicParam) urlParams.set("tag", topicParam);
            if (searchParam) urlParams.set("news-search", searchParam);


            url = urlParams.toString() ? `${window.location.pathname}?${urlParams.toString()}` : window.location.pathname;

            window.history.pushState({}, "", url);
        }

        // Initial fetch on page load
        fetchPosts();
    } else {
        sessionStorage.removeItem("category")
        sessionStorage.removeItem("tag")
        sessionStorage.removeItem("news-search");
    }

    // OUR COMPANIES
    if (document.body.classList.contains('post-type-archive-company')) {
        const companiesContainer = document.getElementById('companies-container');
        const sectorFilter = document.getElementById('sector-filter');
        const fundFilter = document.getElementById('fund-filter');
        const statusFilter = document.getElementById('status-filter');
        const filterReset = this.document.getElementById('filter-reset');
        const companyParams = new URLSearchParams(window.location.search);
        
        let selectedSector = companyParams.get("sector") || sessionStorage.getItem("sector") || '';
        let selectedFund = companyParams.get("fund") || sessionStorage.getItem("fund") || '';
        let selectedStatus = companyParams.get("status") || sessionStorage.getItem("status") || '';

        updateURL();

        if (selectedSector && selectedSector !== null) {
            const selectedSectorButton = sectorFilter.querySelector(`button[data-sector="${selectedSector}"]`);
            if (selectedSectorButton) {
                sectorFilter.previousElementSibling.textContent = selectedSectorButton.textContent;
                updateActiveClass(sectorFilter, selectedSectorButton);
            }
        }

        if (selectedFund && selectedFund !== null) {
            const selectedFundButton = fundFilter.querySelector(`button[data-fund="${selectedFund}"]`);
            if (selectedFundButton) {
                fundFilter.previousElementSibling.textContent = selectedFundButton.textContent;
                updateActiveClass(sectorFilter, selectedFundButton);
            }
        }

        if (selectedStatus && selectedStatus !== null) {
            const selectedStatusButton = fundFilter.querySelector(`button[data-status="${selectedStatus}"]`);
            if (selectedStatusButton) {
                statusFilter.previousElementSibling.textContent = selectedStatusButton.textContent;
                updateActiveClass(sectorFilter, selectedStatusButton);
            }
        }

        // Handle sector selection
        sectorFilter.addEventListener('click', function (e) {
            e.preventDefault();
            const button = e.target.closest("button");

            if (!button) return;
            selectedSector = button.getAttribute("data-sector");

            if (selectedSector && selectedSector !== null) {
                companyParams.set("sector", selectedSector);
                sessionStorage.setItem("sector", selectedSector);
            } else {
                companyParams.delete("sector");
                sessionStorage.removeItem("sector");
            }

            sectorFilter.previousElementSibling.textContent = button.textContent;
            updateActiveClass(sectorFilter, button);
            button.parentNode.parentNode.parentNode.classList.remove("js-active");

            loadCompanies();
        });

        // Handle fund selection
        fundFilter.addEventListener('click', function (e) {
            e.preventDefault();
            const button = e.target.closest("button");

            if (!button) return;
            selectedFund = button.getAttribute("data-fund");

            if (selectedFund && selectedFund !== null) {
                companyParams.set("fund", selectedFund);
                sessionStorage.setItem("fund", selectedFund);
            } else {
                companyParams.delete("fund");
                sessionStorage.removeItem("fund");
            }

            fundFilter.previousElementSibling.textContent = button.textContent;
            updateActiveClass(fundFilter, button);
            button.parentNode.parentNode.parentNode.classList.remove("js-active");

            loadCompanies();
        });

        // Handle status selection
        statusFilter.addEventListener('click', function (e) {
            e.preventDefault();
            const button = e.target.closest("button");

            if (!button) return;
            selectedStatus = button.getAttribute("data-status");

            if (selectedStatus && selectedStatus !== null) {
                companyParams.set("status", selectedStatus);
                sessionStorage.setItem("status", selectedStatus);
            } else {
                companyParams.delete("status");
                sessionStorage.removeItem("status");
            }

            statusFilter.previousElementSibling.textContent = button.textContent;
            updateActiveClass(statusFilter, button);
            button.parentNode.parentNode.parentNode.classList.remove("js-active");

            loadCompanies();
        });

        function loadCompanies() {
            // currentPage = 0;
            // totalPages = null;
            companiesContainer.innerHTML = "";
            fetchCompanies()
        }

        // Fetch posts
        function fetchCompanies(paged = 1, paginationScroll = false) {
            
            let sectorParam = sessionStorage.getItem("sector") || "";
            let fundParam = sessionStorage.getItem("fund") || "";
            let statusParam = sessionStorage.getItem("status") || "";

            const formData = new FormData();
            formData.append('action', 'fetch_filtered_companies');
            if (sectorParam !== null) formData.append("sector", sectorParam);
            if (fundParam !== null) formData.append("fund", fundParam);
            if (statusParam !== null) formData.append("status", statusParam);
            formData.append('paged', paged);

            updateURL();

            companiesContainer.style.opacity = '0';

            fetch(ajaxurl.url, {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.text())
                .then((html) => {

                    setTimeout(function () {
                        companiesContainer.innerHTML = html;
                        companiesContainer.style.opacity = '1';
                        attachPaginationListeners();

                        if (paginationScroll) {
                            const headerOffset = 235;
                            const elementPosition = companiesContainer.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }

                    }, 300);

                    const articles = companiesContainer.querySelectorAll('article');
                    articles.forEach((article) => {
                        setTimeout(() => {
                            article.classList.add('fade-in');
                            article.classList.add('js-visible');
                        }, 500);
                    });
                })
                .catch((error) => {
                    console.error('Error fetching posts:', error);
                    companiesContainer.innerHTML = '<p>Error loading posts.</p>';
                });
        }

        // Utility function to update active class
        function updateActiveClass(parent, target) {
            parent.querySelectorAll('button').forEach((btn) => btn.classList.remove('js-active'));
            target.classList.add('js-active');
        }

        // Function to add event listeners to pagination links
        function attachPaginationListeners() {
            const paginationBtns = document.querySelectorAll('.page-link');
            paginationBtns.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    // console.log("Clicked");
                    fetchCompanies(btn.getAttribute('data-page'), true);
                });
            })
        }

        function updateURL() {
            let urlParams = new URLSearchParams();
            let sectorParam = sessionStorage.getItem("sector");
            let fundParam = sessionStorage.getItem("fund");
            let statusParam = sessionStorage.getItem("status");
            let url;

            if (sectorParam) urlParams.set("sector", sectorParam);
            if (fundParam) urlParams.set("fund", fundParam);
            if (statusParam) urlParams.set("status", statusParam);

            url = urlParams.toString() ? `${window.location.pathname}?${urlParams.toString()}` : window.location.pathname;

            window.history.pushState({}, "", url);
        }

        setTimeout(function () {
            const companyLogos = companiesContainer.querySelectorAll('article');

            companyLogos.forEach(function (logo) {
                logo.addEventListener('click', function () {
                    window.location = logo.querySelector('a').getAttribute('href');
                })
            });
        }, 500);

        fetchCompanies();
    } else {
        sessionStorage.removeItem("sector")
        sessionStorage.removeItem("fund")
        sessionStorage.removeItem("status");
    }

    // OUR TEAM
    if (document.body.classList.contains('single-team')) {
        // const teamParam = sessionStorage.getItem('employees');
        // const searchParam = sessionStorage.getItem('search');
        // // console.log(teamParam, searchParam);
        // const teamLinks = document.querySelectorAll('.team-link');

        // if (teamParam !== null && searchParam !== null) {
        //     teamLinks.forEach(function (link) {
        //         link.querySelector('a').setAttribute('href', `/our-team?employees=${teamParam}&search=${searchParam}`)
        //     })
        // } else if (searchParam !== null) {
        //     teamLinks.forEach(function (link) {
        //         link.querySelector('a').setAttribute('href', `/our-team?search=${searchParam}`)
        //     })
        // } else if (teamParam !== null) {
        //     teamLinks.forEach(function (link) {
        //         link.querySelector('a').setAttribute('href', `/our-team?employees=${teamParam}`)
        //     })
        // }
    }

    if (document.body.classList.contains('post-type-archive-team')) {
        const teamContainer = document.getElementById("team-container");
        const teamFilter = document.getElementById("team-filter");
        const filterButton = document.querySelector(".filter-group > button");
        const searchInput = document.getElementById("search-input");
        const filterSubmit = document.getElementById("filter-submit");
        const filterReset = document.getElementById("filter-reset");
        const teamParams = new URLSearchParams(window.location.search);

        // if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
        //     sessionStorage.removeItem("teamPageScroll");
        //     sessionStorage.removeItem("teamPageHTML");
        //     loadTeams();
        // }

        updateURL();

        let selectedTeam = teamParams.get("employees") || sessionStorage.getItem("employees");
        let searchValue = teamParams.get("search") || sessionStorage.getItem("search");
        let currentPage = 0;
        let loading = false;
        let totalPages = sessionStorage.getItem("teamTotalPages") || null;

        if (selectedTeam && selectedTeam !== null) { sessionStorage.setItem("employees", selectedTeam) };
        if (searchValue && searchValue !== null) {
            sessionStorage.setItem("search", searchValue);
            searchInput.value = searchValue;
            filterReset.classList.add('show');
        }

        if (selectedTeam && selectedTeam !== null) {
            const selectedTeamButton = teamFilter.querySelector(`button[data-team="${selectedTeam}"]`);
            if (selectedTeamButton) {
                filterButton.textContent = selectedTeamButton.textContent;
                updateActiveClass(teamFilter, selectedTeamButton);
            }
        }

        teamFilter.addEventListener("click", function (e) {
            e.preventDefault();
            sessionStorage.removeItem("teamPageScroll");
            sessionStorage.removeItem("teamPageHTML");
            sessionStorage.removeItem("teamCurrentPage")

            const button = e.target.closest("button");

            if (!button) return;
            selectedTeam = button.getAttribute("data-team");

            if (selectedTeam && selectedTeam !== null) {
                teamParams.set("employees", selectedTeam);
                sessionStorage.setItem("employees", selectedTeam);
            } else {
                teamParams.delete("employees");
                sessionStorage.removeItem("employees");
            }

            filterButton.textContent = button.textContent;
            updateActiveClass(teamFilter, button);
            button.parentNode.parentNode.parentNode.classList.remove("js-active");

            loadTeams();
        });

        // function scrollToTeamContainer() {
        //     const offset = 235;
        //     const elementPosition = teamContainer.getBoundingClientRect().top + window.pageYOffset;
        //     const offsetPosition = elementPosition - offset;

        //     window.scrollTo({
        //         top: offsetPosition,
        //         behavior: 'smooth'
        //     });
        // }

        function loadTeams() {
            currentPage = 0;
            totalPages = null;
            teamContainer.innerHTML = "";
            fetchTeams(currentPage)
        }

        function fetchTeams(paged) {
            if (loading || (totalPages !== null && paged >= totalPages)) return;
            loading = true;
            let teamParam = sessionStorage.getItem("employees") || "";
            let searchParam = sessionStorage.getItem("search") || "";

            updateURL();

            const formData = new FormData();
            formData.append("action", "fetch_filtered_team_members");
            if (teamParam !== null) formData.append("employees", teamParam);
            if (searchParam !== null) formData.append("search", searchParam);
            formData.append("paged", paged);

            let loader = document.createElement('div');
            loader.className = 'loading-spinner';
            loader.innerHTML = `<div class="spinner"></div>`;
            teamContainer.appendChild(loader);

            fetch(ajaxurl.url, {
                method: "POST",
                body: formData,
            })
                .then((response) => response.text())
                .then((html) => {
                    setTimeout(() => {
                        loader.remove();
                        teamContainer.insertAdjacentHTML("beforeend", html);

                        loading = false;
                        currentPage++;

                        let paginationWrapper = teamContainer.querySelector('.pagination');
                        if (paginationWrapper) {
                            totalPages = parseInt(paginationWrapper.dataset.totalPages, 10);
                            paginationWrapper.remove();

                            sessionStorage.setItem("teamTotalPages", totalPages);
                            console.log(totalPages)
                        }

                        // sessionStorage.setItem("teamPageHTML", teamContainer.innerHTML);
                        sessionStorage.setItem("teamCurrentPage", currentPage);
                        // sessionStorage.setItem("teamPageScroll", window.scrollY);
                    }, 300);
                })
                .catch((error) => {
                    console.error("Error fetching team members:", error);
                    loader.remove();
                    loading = false;
                });
        }

        function search() {
            let inputValue = searchInput.value.trim();
            if (!inputValue) {
                const errorMessage = document.createElement("span");
                errorMessage.classList.add("js-error");
                errorMessage.textContent = "Please enter a search term";
                filterSubmit.parentNode.appendChild(errorMessage);
                return;
            }

            if (inputValue) {
                sessionStorage.setItem("search", inputValue);
                teamParams.set("search", inputValue);
            } else {
                teamParams.delete("search");
                sessionStorage.removeItem("search");
            }

            filterReset.classList.add("show");

            if (document.querySelector(".js-error")) {
                document.querySelector(".js-error").remove();
            }

            loadTeams();
        }

        searchInput.addEventListener('keyup', function() {
            if (searchInput.value === "") {
                teamParams.delete("search");
                sessionStorage.removeItem("search");
                loadTeams();
            }
            if (this.value.trim() && document.querySelector(".js-error")) {
                document.querySelector(".js-error").remove();
            }
        })

        function resetSearch() {
            filterReset.classList.remove("show");
            searchInput.value = "";
            teamParams.delete("search");
            sessionStorage.removeItem("search");

            loadTeams();
        }

        filterSubmit.addEventListener("click", function (e) {
            e.preventDefault();
            search();
        });

        filterReset.addEventListener("click", function (e) {
            e.preventDefault();
            sessionStorage.removeItem("teamPageScroll");
            sessionStorage.removeItem("teamPageHTML");
            sessionStorage.removeItem("teamCurrentPage")
            resetSearch();
        });

        searchInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                search();
            }
        });

        function updateActiveClass(parent, target) {
            parent.querySelectorAll("button").forEach((btn) => btn.classList.remove("js-active"));
            target.classList.add("js-active");
        }

        function updateURL() {
            let urlParams = new URLSearchParams();
            let teamParam = sessionStorage.getItem("employees");
            let searchParam = sessionStorage.getItem("search");
            let url;

            if (teamParam) urlParams.set("employees", teamParam);
            if (searchParam) urlParams.set("search", searchParam);

            url = urlParams.toString() ? `${window.location.pathname}?${urlParams.toString()}` : window.location.pathname;

            window.history.pushState({}, "", url);
        }

        // function handleTeamMemberClick() {
        //     document.body.addEventListener("click", function (event) {
        //         let teamMemberLink = event.target.closest(".team-member a");
        //         if (teamMemberLink) {
        //             let teamMember = teamMemberLink.closest(".team-member");
        //             let rect = teamMember.getBoundingClientRect();
        //             let scrollPosition = window.scrollY + rect.top - 60;
        //             sessionStorage.setItem("teamPageScroll", scrollPosition);
        //             sessionStorage.setItem("teamPageHTML", teamContainer.innerHTML);
        //             sessionStorage.setItem("teamCurrentPage", currentPage);
        //         }
        //     });
        // }

        // handleTeamMemberClick()

        function handleScroll() {
            if (loading || totalPages !== null && currentPage >= totalPages) return;

            if (document.getElementById('no-posts')) {
                // totalPages = 0;
                return;
            }

            let lastTeamMember = teamContainer.querySelector('.team-member:last-of-type'); // Adjust class if necessary
            if (!lastTeamMember) return;
        
            let lastMemberOffset = lastTeamMember.getBoundingClientRect().bottom + window.scrollY;
            let triggerOffset = lastMemberOffset + 160; // Load when 300px past last team member
        
            let scrollPosition = window.scrollY + window.innerHeight;
        
            // sessionStorage.setItem("teamPageScroll", scrollPosition); // Store position in sessionStorage
        
            if (scrollPosition >= triggerOffset) {
                fetchTeams(currentPage);
                let storedPage = parseInt(sessionStorage.getItem("teamCurrentPage")) || 1;

                if (storedPage <= currentPage) {
                    fetchTeams(currentPage);
                }
            }
        }

        window.addEventListener('scroll', handleScroll);

        // let savedHTML = sessionStorage.getItem("teamPageHTML");
        // let savedPage = sessionStorage.getItem("teamCurrentPage");
        // let savedScrollPosition = sessionStorage.getItem("teamPageScroll");

        // if (savedHTML && savedPage) {
        //     teamContainer.innerHTML = savedHTML;
        //     currentPage = parseInt(savedPage, 10);

        //     if (savedScrollPosition) {
        //         setTimeout(() => {
        //             window.scrollTo({
        //                 top: parseInt(savedScrollPosition, 10),
        //                 behavior: "smooth"
        //             });
        //         }, 500);
        //     } else {
        //         scrollToTeamContainer()
        //     }
        // } else {
        //     // If no saved data, load fresh team members
        //     loadTeams();
        // }
        loadTeams();

    } else {
        sessionStorage.removeItem("employees")
        sessionStorage.removeItem("search");
    }

    // Sliders
    if (document.querySelector('.company-slider')) {
        var companySlider = new Splide('.company-slider', {
            type: 'fade',
            autoplay: false,
            interval: 8000,
            arrows: false,
            rewind: true,
            perPage: 1,
            perMove: 1,
            grid: {
                rows: 2,
                cols: 6,
                gap: {
                    row: '40px',
                    col: '60px',
                },
            },
            breakpoints: {
                1440: {
                    grid: {
                        rows: 2,
                        cols: 5,
                        gap: {
                            row: '24px',
                            col: '24px',
                        },
                    },
                },
                1200: {
                    grid: {
                        rows: 2,
                        cols: 4,
                        gap: {
                            row: '36px',
                            col: '36px',
                        },
                    },
                },
                992: {
                    grid: {
                        gap: {
                            row: '24px',
                            col: '24px',
                        },
                    },
                },
                767: {
                    grid: {
                        rows: 2,
                        cols: 3,
                    },
                },
                575: {
                    grid: {
                        rows: 2,
                        cols: 2,
                    },
                },
            },
        });

        document.querySelectorAll('.company-slider__slide').length <= 1 ? document.querySelector('.pagination').classList.add('d-none') : '';

        companySlider.mount(window.splide.Extensions);

        let observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                var Autoplay = companySlider.Components.Autoplay;
                if (entry.isIntersecting) {
                    companySlider.options = { autoplay: true }; // Start autoplay when in viewport
                    Autoplay.play();
                } else {
                    companySlider.options = { autoplay: false }; // Stop autoplay when out of viewport
                    Autoplay.pause();
                }
            });
        }, { threshold: 0.5 }); // Adjust threshold (0.5 means half of the slider must be visible)

        observer.observe(document.querySelector('.company-slider'));
    }

    if (document.querySelector('.hover-container') && window.innerWidth > 1025) {
        const hoverContainers = document.querySelectorAll('.hover-container');
        hoverContainers.forEach(function (container) {
            const imgContainer = container.querySelector('.hover-img');
            const videoContainer = container.querySelector('.hover-video');
            const video = videoContainer.querySelector('video')
            container.addEventListener('click', function () {
                const a = container.querySelector('a');
                const aHref = a.getAttribute('href')
                window.location = aHref;
            })
            container.addEventListener('mouseenter', function () {
                imgContainer.classList.remove('js-show');
                videoContainer.classList.add('js-show');
                video.play();
            });

            container.addEventListener('mouseleave', function () {
                imgContainer.classList.add('js-show');
                videoContainer.classList.remove('js-show');
                video.pause();
            });
        })
    }

    const captchaLabels = document.querySelectorAll('.gfield--type-captcha label');
    captchaLabels.forEach(function(label) {
        label.classList.add('sr-only');
        label.setAttribute('aria-label', 'CAPTCHA (Invisible Verification Method) - No Action Required')
    })

});

// Hero video
// if screen size is less than 1024, get size of hero inner
function heroVideoResize() {
    if (document.querySelector('#hero video')) {
        const heroVideo = document.querySelector('.video-wrapper');
        const heroInner = document.querySelector('.hero-inner');
        const heroInnerHeight = heroInner.offsetHeight;
        if (window.innerWidth < 1025) {
            heroVideo.style.height = `${heroInnerHeight}px`;
        } else {
            heroVideo.style.height = '100%';
        }
    }
}

function removeMenuBackdrop() {
    if (document.querySelector('.menu-backdrop')) {
        if (window.innerWidth > 1025) {
            document.body.classList.remove('menu-open');
            document.querySelector('.menu-backdrop').classList.remove('js-active');
        } else {
            if (document.querySelector('.elementor-menu-toggle').classList.contains('elementor-active')) {
                document.body.classList.add('menu-open');
                document.querySelector('.menu-backdrop').classList.add('js-active');
            }
            // if el has active class, re-add js-active class
        }
    }
}

window.addEventListener('DOMContentLoaded', heroVideoResize);

window.addEventListener('resize', function () {
    heroVideoResize();
    removeMenuBackdrop();
});     