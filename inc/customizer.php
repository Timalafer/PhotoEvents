<?php

/**
 * photos-events Theme Customizer
 *
 * @package photos-events
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
/**
 * photos-events Theme Customizer
 *
 * @package photos-events
 */

function photos_events_customize_register($wp_customize)
{
	// PostMessage support for site title and description.
	$transport = 'postMessage';
	$wp_customize->get_setting('blogname')->transport = $transport;
	$wp_customize->get_setting('blogdescription')->transport = $transport;
	$wp_customize->get_setting('header_textcolor')->transport = $transport;

	// Selective refresh.
	if (isset($wp_customize->selective_refresh)) {
		$partial_args = array(
			'selector' => '.site-title a, .site-description',
			'render_callback' => 'photos_events_customize_partial_site_identity',
		);
		$wp_customize->selective_refresh->add_partial('site_identity', $partial_args);
	}
}
add_action('customize_register', 'photos_events_customize_register');

/**
 * Render the site identity (title and description) for the selective refresh partial.
 */
function photos_events_customize_partial_site_identity()
{
	bloginfo('name');
	bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function photos_events_customize_preview_js()
{
	wp_enqueue_script(
		'photos-events-customizer',
		get_template_directory_uri() . '/js/customizer.js',
		array('customize-preview'),
		_S_VERSION,
		true
	);
}
add_action('customize_preview_init', 'photos_events_customize_preview_js');
