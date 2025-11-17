window.addEventListener('load', function() {
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) {
            target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }
})

window.addEventListener('DOMContentLoaded', function () {

    // Header
    function scrollHeader() {
        if (window.innerWidth > 1024) {
            const header = document.querySelector('header');
            const headerHeight = header.offsetHeight;
            const scrollPos = window.scrollY;
            if (scrollPos === 0) {
                header.classList.remove('js-active');
            } else if (scrollPos > headerHeight) {
                header.classList.add('js-active')
            }
        } 
    }
    
    scrollHeader();
    window.addEventListener('scroll', scrollHeader)

    // Menu Functionality
    const menuToggle = document.querySelector('nav.site-nav button');
    menuToggle.addEventListener('click', function() {
        if (!this.classList.contains('js-open')) {
            this.setAttribute('aria-expanded', 'true');
            this.classList.add('js-open');
            this.nextElementSibling.classList.add('js-open');
        } else {
            this.setAttribute('aria-expanded', 'false');
            this.classList.remove('js-open');
            this.nextElementSibling.classList.remove('js-open');
        }
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

    const captchaLabels = document.querySelectorAll('.gfield--type-captcha label');
    captchaLabels.forEach(function(label) {
        label.classList.add('sr-only');
        label.setAttribute('aria-label', 'CAPTCHA (Invisible Verification Method) - No Action Required')
    }) 
      
});