<?php

/**
 * photos-events Fonctions et définitions du thème
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package photos-events
 */

if (!defined('_S_VERSION')) {
	// Remplacez le numéro de version du thème à chaque mise à jour.
	define('_S_VERSION', '1.0.0');
}

/**
 * Paramètre les configurations du thème et enregistre le support de diverses fonctionnalités WordPress.
 *
 * Remarque : Cette fonction est accrochée à l'action 'after_setup_theme', qui s'exécute avant l'action 'init'.
 */
function photos_events_setup()
{
	load_theme_textdomain('photos-events', get_template_directory() . '/languages');

	add_theme_support('automatic-feed-links');

	add_theme_support('title-tag');

	add_theme_support('post-thumbnails');
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primaire', 'motanathalie'),
			'menu-2' => esc_html__('Secondaire', 'motanathalie'),
		)
	);
	function register_my_footer_menu()
	{
		register_nav_menu('footer', __('footer'));
	}

	add_action('after_setup_theme', 'register_my_footer_menu');
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'photos_events_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	add_theme_support('customize-selective-refresh-widgets');

	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'photos_events_setup');

/**
 * Définit la largeur du contenu en pixels, en fonction de la conception et de la feuille de style du thème.
 *
 * Priorité 0 pour le rendre disponible pour les rappels de priorité inférieure.
 *
 * @global int $content_width
 */
function photos_events_content_width()
{
	$GLOBALS['content_width'] = apply_filters('photos_events_content_width', 640);
}
add_action('after_setup_theme', 'photos_events_content_width', 0);



/**
 * Ajoute des scripts et des styles.
 */
function photos_events_scripts()
{
	wp_enqueue_style('photos-events-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('photos-events-style', 'rtl', 'replace');

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'photos_events_scripts');

/***ajouter le js script  */

function enqueue_custom_script()
{
	wp_enqueue_script('custom-script', get_template_directory_uri() . '/script.js', array('jquery'), '1.0', true);
	wp_localize_script('custom-script', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_enqueue_script('menuburgerJS', get_stylesheet_directory_uri() . '/js/menuburger.js', array('jquery'), '1.0.0', true);
	wp_enqueue_script('modaleJS', get_stylesheet_directory_uri() . '/js/modale.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');




/**
 * Implémente la fonctionnalité d'en-tête personnalisé.
 */
require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';





/* Chargement photos Ajax load more */
function load_more_photos()
{
	$paged = $_POST['page'];
	$args = array(
		'post_type' => 'photos',
		'posts_per_page' => 12,
		'paged' => $paged,
		'orderby' => 'date',
	);

	$photos_query = new WP_Query($args);
	if ($photos_query->have_posts()) {
		ob_start();
		while ($photos_query->have_posts()) {
			$photos_query->the_post();
			if (has_post_thumbnail()) {
				$image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
?>
				<div class="gallery-item ajax-loaded">
					<div class="blockPhotoRelative">
						<img src="<?php echo esc_url($image_url[0]); ?>" alt="<?php the_title(); ?>">
						<div class="overlay">
							<h2><?php echo esc_html(get_the_title()); ?></h2>

							<?php
							// Récupération des termes de la taxonomie 'categorie' pour la photo en cours
							$categories = get_the_terms(get_the_ID(), 'categorie');

							// Vérification s'il y a des catégories associées à la photo
							if ($categories && !is_wp_error($categories)) {
								// Pour afficher seulement la première catégorie associée à la photo
								$categorie_name = $categories[0]->name;
								echo '<h3>' . esc_html($categorie_name) . '</h3>';
							} else {
								// Si aucune catégorie n'est associée à la photo
								echo '<h3>Aucune catégorie</h3>';
							}
							?>
							<div class="eye-icon">
								<a href="<?php echo esc_url(get_permalink()); ?>">
									<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icon_eye.svg" alt="voir la photo">
								</a>
							</div>
							<div class="fullscreen-icon" data-full="<?php echo esc_url($image_url[0]); ?>" data-category="<?php echo esc_attr($categorie_name); ?>" data-reference="<?php echo esc_attr(get_field('reference')); ?>">
								<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/fullscreen.svg" alt="Icone fullscreen">
							</div>
						</div>
					</div>
				</div>




			<?php
			}
		}
		wp_reset_postdata();

		$output = ob_get_clean();
		echo $output;
	} else {
	}
	die();
}
add_action('wp_ajax_nopriv_load_more', 'load_more_photos');
add_action('wp_ajax_load_more', 'load_more_photos');



// Astuce pour �viter d'avoir des <p> partout dans CF7
add_filter('wpcf7_autop_or_not', '__return_false');

/*filtres*/


function filter_photos_function()
{
	$filter = $_POST['filter'];

	$args = array(
		'post_type' => 'photos',
		'posts_per_page' => -1,
		'tax_query' => array(
			'relation' => 'AND',
		)
	);

	if (!empty($filter['categorie'])) {
		$args['tax_query'][] = array(
			'taxonomy' => 'categorie',
			'field'    => 'slug',
			'terms'    => $filter['categorie'],
		);
	}

	if (!empty($filter['format'])) {
		$args['tax_query'][] = array(
			'taxonomy' => 'format',
			'field'    => 'slug',
			'terms'    => $filter['format'],
		);
	}

	if (!empty($filter['date'])) {
		$args['tax_query'][] = array(
			'taxonomy' => 'date',
			'field'    => 'slug',
			'terms'    => $filter['date'],
		);
	}

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			// Générez ici le HTML pour chaque photo
			?>
			<div class="blockPhotoRelative">
				<img src="<?php echo esc_url(wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')[0]); ?>" alt="<?php the_title(); ?>">
				<!-- Ajoutez ici le reste de votre structure HTML pour chaque photo -->
				<!-- Par exemple, titre, catégorie, icônes, etc. -->
				<div class="overlay">
					<h2><?php echo esc_html(get_the_title()); ?></h2>
					<!-- ... Autres éléments HTML ... -->
				</div>
			</div>
<?php
		}
		wp_reset_postdata();
	} else {
		echo '<p class="critereFiltrage">Aucune photo ne correspond aux critères de filtrage</p>';
	}

	$output = ob_get_clean();
	echo $output;
	wp_die();
}

add_action('wp_ajax_filter_photos', 'filter_photos_function');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_function');
