jQuery(document).ready(function ($) {
    function openModal() {
        $('.popup-overlay').css('display', 'flex');

        const boutonContact = $('#boutonContact');
        const referencePhoto = $('#reference');

        if (boutonContact.attr('data-reference') && boutonContact.attr('data-reference').trim() !== "") {
            referencePhoto.val(boutonContact.attr('data-reference'));
        }
    }

    function closeModal() {
        $('.popup-overlay').css('display', 'none');
    }

    $('#menu-item-121').on('click', function (event) {
        event.preventDefault();
        console.log('Clic détecté sur le menu CONTACT');
        openModal();
    });


    $('#boutonContact ').on('click', function (event) {
        event.preventDefault();
        openModal();
    });

    $(window).on('click', function (event) {
        if ($(event.target).hasClass('popup-overlay')) {
            closeModal();
        }
    });
});