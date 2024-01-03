<?php

/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package photos-events
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses photos_events_header_style()
 */
function photos_events_custom_header_setup()
{
	add_theme_support(
		'custom-header',
		apply_filters(
			'photos_events_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1000,
				'height'             => 250,
				'flex-height'        => true,
				'wp-head-callback'   => 'photos_events_header_style',
			)
		)
	);
}
add_action('after_setup_theme', 'photos_events_custom_header_setup');

if (!function_exists('photos_events_header_style')) :

	function photos_events_header_style()
	{
		$header_text_color = get_header_textcolor();

		// Si aucune couleur personnalisée pour le texte n'est définie, arrêtez la fonction.
		if (get_theme_support('custom-header', 'default-text-color') === $header_text_color) {
			return;
		}

		// Génération des styles CSS pour le texte de l'en-tête.
		$style = '';

		if (!display_header_text()) {
			// Si le texte est masqué, positionnez-le hors de la vue.
			$style .= '.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }';
		} else {
			// Si une couleur personnalisée pour le texte est définie, utilisez-la.
			$style .= '.site-title a, .site-description { color: #' . esc_attr($header_text_color) . '; }';
		}

		// Ajout des styles au document.
		if ($style) {
			echo '<style type="text/css">' . $style . '</style>';
		}
	}

endif;
