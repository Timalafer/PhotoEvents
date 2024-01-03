jQuery(document).ready(function ($) {

    const header = $('header');
    const menuBurger = $('.burgerMenu');
    const nav = $('.navigation');

    menuBurger.on('click', function () {
        const isOpen = header.hasClass('open');

        header.toggleClass('open', !isOpen);
        menuBurger.toggleClass('open', !isOpen);
        nav.toggleClass('open', !isOpen);
    });
});