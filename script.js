jQuery(document).ready(function ($) {
    var page = 1;
    var canLoadMore = true;

    function attachLightboxEvents() {
        $(document).off('mouseover', '.fullscreen-icon', imageMouseOverHandler);
        $(document).on('mouseover', '.fullscreen-icon', imageMouseOverHandler);
        console.log('Lightbox events attached to new images');
    }

    function imageMouseOverHandler() {
        var $images = $('.fullscreen-icon');
        var index = $images.index($(this).closest('.fullscreen-icon'));
        openLightbox(index);
        console.log('Survolez l\'image :', index);
    }

    // Chargement de plus de photos
    $('#load-more').on('click', function (e) {
        e.preventDefault();
        if (!canLoadMore) {
            return;
        }

        page++;
        var data = {
            'action': 'load_more',
            'page': page
        };

        $.ajax({
            url: ajaxurl,
            data: data,
            type: 'POST',
            success: function (response) {
                $('.gallery').append(response);
                attachEventsToImages(); // Attache les événements à toutes les images existantes et nouvelles



                if (response === 'no_posts') {
                    canLoadMore = false;
                    $('#load-more').hide();
                }
            }
        });


    });

    /* Lightbox Ouverture et Fermeture */
    console.log("Lightbox Ouverture et Fermeture : son js est chargé");

    $(document).ready(function () {
        var $lightbox = $('#lightbox');
        var $lightboxImage = $('.lightboxImage');
        var $lightboxCategory = $('.lightboxCategorie');
        var $lightboxReference = $('.lightboxReference');
        var currentIndex = 0;

        function updateLightbox(index) {
            var $images = $('.fullscreen-icon');
            var $image = $images.eq(index);

            var categoryText = $image.data('category').toUpperCase();
            var referenceText = $image.data('reference').toUpperCase();

            $lightboxImage.attr('src', $image.data('full'));
            $lightboxCategory.text(categoryText);
            $lightboxReference.text(referenceText);
            currentIndex = index;
        }

        function openLightbox(index) {
            updateLightbox(index);
            $lightbox.show();

        }

        function fermetureLightbox() {
            $lightbox.hide();
        }

        window.attachEventsToImages = function () {
            var $images = $('.fullscreen-icon');
            $images.off('click', imageClickHandler);
            $images.on('click', imageClickHandler);
        };

        function imageClickHandler() {
            var $images = $('.fullscreen-icon');
            var index = $images.index($(this).closest('.fullscreen-icon'));
            openLightbox(index);
        }

        attachEventsToImages();

        $('.fermelightbox').on('click', fermetureLightbox);

        $('.lightboxPrecedent').on('click', function () {
            var $images = $('.fullscreen-icon');
            if (currentIndex > 0) {
                updateLightbox(currentIndex - 1);
            } else {
                updateLightbox($images.length - 1);
            }
        });

        $('.lightboxSuivant').on('click', function () {
            var $images = $('.fullscreen-icon');
            if (currentIndex < $images.length - 1) {
                updateLightbox(currentIndex + 1);
            } else {
                updateLightbox(0);
            }
        });

    });

});


/* les Filtres */
console.log("les Filtres : son js est chargé");

(function ($) {

    function fetchFilteredPhotos() {
        var filter = {
            'categorie': $('#categorie').val(),
            'format': $('#format').val(),
            'date': $('#date').val(),
        };

        $.ajax({
            url: ajaxurl,
            data: {
                'action': 'filter_photos',
                'filter': filter
            },
            type: 'POST',
            beforeSend: function () {
                $('#gallery').html('<div class="loading">Chargement...</div>');
            },
            success: function (data) {
                $('#gallery').html(data);
                attachEventsToImages();
                setTimeout(function () {
                    document.getElementById('gallery').scrollIntoView();
                }, 0);
            }
        })
    }

    $('#gallery-filters select').on('change', function (event) {
        event.preventDefault();
        fetchFilteredPhotos();
    });
})(jQuery);