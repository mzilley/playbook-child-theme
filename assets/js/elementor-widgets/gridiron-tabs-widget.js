function initGridironTabs() {
    const tabsContainer = document.querySelector('.gridiron-tabs');
    if (!tabsContainer) { return; }
    const tabButtons = tabsContainer.querySelectorAll('.gridiron-tabs__nav-link');
    const tabSections = tabsContainer.querySelectorAll('.gridiron-tabs__tab');

    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {

            // If video in current tab is playing, pause it
            const currentVideo = tabsContainer.querySelector('.gridiron-tabs__tab.active iframe');
            if (currentVideo) {
                var player = new Vimeo.Player(currentVideo);
                player.pause();
            }

            const target = btn.dataset.target;
            const targetTab = tabsContainer.querySelector(target);
            if (!targetTab) return;

            tabButtons.forEach(function(btn) {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', false);
            })
            tabSections.forEach(function(tab) {
                tab.style.opacity = '0';
                tab.classList.remove('active');
            })

            btn.classList.add('active');
            btn.setAttribute('aria-selected', true);
            setTimeout(function() {
                updateTabSectionHeight(tabsContainer)
                targetTab.classList.add('active');
            }, 150)
            setTimeout(function() {
                targetTab.style.opacity = '1';
            }, 300)
        })
    })
    updateTabSectionHeight();
}

updateTabSectionHeight();

function updateTabSectionHeight() {
    const tabSections = document.querySelectorAll('.gridiron-tabs');
    // console.log(tabs);
    tabSections.forEach(function(tabs) {
        if (tabs) {
            tabs.querySelector('.gridiron-tabs__tab-content').style.opacity = '0';
            tabs.querySelector('.gridiron-tabs__right').style.opacity = '0';
            setTimeout(function() {
                const tabsNav = tabs.querySelector('.gridiron-tabs__nav');
                const activeTab = tabs.querySelector('.gridiron-tabs__tab.active');
                const activeTabContentHeight = activeTab.querySelector('.gridiron-tabs__tab-content').offsetHeight;
                const tabsNavHeight = tabsNav.offsetHeight;
                const totalHeight = activeTabContentHeight + tabsNavHeight;
                const tabSections = document.querySelectorAll('.gridiron-tabs__tab');
                const tabsRightHeight = activeTab.querySelector('.gridiron-tabs__right').offsetHeight;
                if (window.innerWidth > 1024) {
                    totalHeight > tabsRightHeight ? tabs.style.height = `${totalHeight}px` : tabs.style.height = `${tabsRightHeight}px`;
                    totalHeight > tabsRightHeight ? activeTab.style.height = `${totalHeight}px` : activeTab.style.height = `${tabsRightHeight}px`;
                    activeTab.querySelector('.gridiron-tabs__tab-content').style.top = `${tabsNavHeight}px`;
                    tabSections.forEach(function(tabSection) {
                        tabSection.style.paddingTop = `0`;
                    })
                } else {
                    tabs.style.height = `${totalHeight + tabsRightHeight}px`;
                    activeTab.querySelector('.gridiron-tabs__tab-content').style.top = 0;
                    tabSections.forEach(function(tabSection) {
                        tabSection.style.paddingTop = `${tabsNavHeight}px`;
                        tabSection.style.height = `100%`;
                    })
                }
            }, 150)
            setTimeout(function() {
                tabs.querySelector('.gridiron-tabs__tab-content').style.opacity = '1';
                tabs.querySelector('.gridiron-tabs__right').style.opacity = '1';
            }, 500)
        } 
    })
    
}

window.addEventListener('DOMContentLoaded', function() {
    initGridironTabs()
});

window.addEventListener('resize', function() {
    updateTabSectionHeight();
});

window.addEventListener('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/gridiron-tabs-widget.default', function(scope) {
        initGridironTabs()
    });
});