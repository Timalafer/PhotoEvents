<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package photos-events
 */

get_header();
?>

<?php

//ACF
$photo_url = get_field('photo');
$reference = get_field('reference');
$REFERENCE = strtoupper(get_field('reference'));
$type = get_field('type');

$annees = get_the_terms(get_the_ID(), 'date');
if ($annees && !is_wp_error($annees)) {
	$annee_names = array();
	foreach ($annees as $annee) {
		$annee_names[] = $annee->name;
	}
	$year = implode(', ', $annee_names);
} else {
	$year = 'Aucune date définie';
}

$categories = get_the_terms(get_the_ID(), 'categorie');
$formats = get_the_terms(get_the_ID(), 'format');
$categorie_name = $categories[0]->name;

// Définissez les URLs des vignettes pour le post précédent et suivant
$nextPost = get_next_post();
$previousPost = get_previous_post();
$previousThumbnailURL = $previousPost ? get_the_post_thumbnail_url($previousPost->ID, 'thumbnail') : '';
$nextThumbnailURL = $nextPost ? get_the_post_thumbnail_url($nextPost->ID, 'thumbnail') : '';

?>

<section class="cataloguePhotos">
	<div class="galleryPhotos">
		<div class="detailPhoto">

			<div class="containerPhoto">
				<?php
				$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
				$photo_url = $image ? $image[0] : ''; // Récupération de l'URL de l'image

				if ($photo_url) {
					echo '<img src="' . esc_url($photo_url) . '" alt="' . esc_attr(get_the_title()) . '">';
				} else {
					echo 'Image non disponible';
				}
				?>
				<div class="singlePhotoOverlay">
					<div class="fullscreen-icon" data-reference="<?php echo esc_attr($reference); ?>" data-full="<?php echo esc_url($photo_url); ?>" data-category="<?php echo esc_attr($categorie_name); ?>">
						<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/fullscreen.svg" alt="Icone fullscreen">
					</div>
				</div>
			</div>

			<div class="selecteurK">
				<h2><?php echo get_the_title(); ?></h2>

				<div class="taxonomies">

					<p>RÉFÉRENCE : <span id="single-reference"><?php echo strtoupper($reference) ?></span></p>
					<p>CATÉGORIE : <?php foreach ($categories as $key => $cat) {
										$categoryNameSingle = $cat->name;
										echo strtoupper($categoryNameSingle);
									}  ?></p>
					<p>FORMAT : <?php foreach ($formats as $key => $format) {
									$formatName = $format->name;
									echo strtoupper($formatName);
								} ?></p>
					<p>TYPE : <?php echo strtoupper($type) ?> </p>
					<p>ANNÉE : <?php echo $year ?> </p>
				</div>
			</div>
		</div>
	</div>

	<div class="contenairContact">
		<div class="contact">
			<p class="interesser"> Cette photo vous intéresse ? </p>
			<button id="boutonContact" data-reference="<?php echo $REFERENCE; ?>">Contact</button>
		</div>

		<div class="naviguationPhotos">

			<!-- Conteneur pour la miniature -->
			<div class="miniPicture" id="miniPicture">
				<!-- La miniature sera chargée ici par JavaScript -->
			</div>

			<div class="naviguationArrow">
				<?php if (!empty($previousPost)) : ?>
					<img class="arrow arrow-left" src="<?php echo get_theme_file_uri() . '/assets/images/left.png'; ?>" alt="Photo précédente" data-thumbnail-url="<?php echo $previousThumbnailURL; ?>" data-target-url="<?php echo esc_url(get_permalink($previousPost->ID)); ?>">
				<?php endif; ?>

				<?php if (!empty($nextPost)) : ?>
					<img class="arrow arrow-right" src="<?php echo get_theme_file_uri() . '/assets/images/right.png'; ?>" alt="Photo suivante" data-thumbnail-url="<?php echo $nextThumbnailURL; ?>" data-target-url="<?php echo esc_url(get_permalink($nextPost->ID)); ?>">
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
<section>
	<div class="titreVousAimerezAussi">
		<h3>VOUS AIMEREZ AUSSI</h3>
	</div>

	<div class="PhotoSimilaire">

		<?php
		// Récupération des catégories de la photo actuelle
		$categories = get_the_terms(get_the_ID(), 'categorie');
		if ($categories && !is_wp_error($categories)) {
			// Récupération des ID des catégories
			$category_ids = wp_list_pluck($categories, 'term_id');

			// Construction de la requête pour récupérer 2 photos similaires aléatoires
			$args = array(
				'post_type' => 'photos',
				'posts_per_page' => 2,
				'orderby' => 'rand',
				'post__not_in' => array(get_the_ID()),
				'tax_query' => array(
					array(
						'taxonomy' => 'categorie',
						'field' => 'term_id',
						'terms' => $category_ids,
					),
				),
			);

			$related_block = new WP_Query($args);
			while ($related_block->have_posts()) {
				$related_block->the_post();
				if (has_post_thumbnail()) {
					$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
					$photo_url = $image ? $image[0] : ''; // Vérification de l'existence de l'image
					$reference = get_field('reference');
					$categorie_name = isset($categories[0]) ? $categories[0]->name : '';
		?>
					<div class="blockPhotoRelative">
						<img src="<?php echo esc_url($photo_url); ?>" alt="<?php the_title(); ?>">
						<div class="overlay">
							<h2><?php echo esc_html(get_the_title()); ?></h2>
							<h3><?php echo esc_html($categorie_name); ?></h3>
							<div class="eye-icon">
								<a href="<?php echo esc_url(get_permalink()); ?>">
									<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icon_eye.svg" alt="voir la photo">
								</a>
							</div>
							<div class="fullscreen-icon" data-full="<?php echo esc_url($photo_url); ?>" data-category="<?php echo esc_attr($categorie_name); ?>" data-reference="<?php echo esc_attr($reference); ?>">
								<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/fullscreen.svg" alt="Icone fullscreen">
							</div>
						</div>
					</div>
		<?php
				}
			}
			wp_reset_postdata();

			// Si aucune photo similaire n'est trouvée
			if ($related_block->post_count === 0) {
				echo "<p class='photoNotFound'> Pas de photo similaire trouvée pour la catégorie ''" . $categorie_name . "'' </p>";
			}
		}
		?>

	</div>
	<button id="toutesLesPhotos" class="bouton">
		<a href="<?php echo home_url(); ?>#containerPhoto">Toutes les photos</a>
	</button>
</section>

<style>
	/* CSS pour la page single-photo */
	.galleryPhotos {
		display: flex;
		flex-direction: column;
		max-width: 100%;
	}

	@media screen and (max-width: 820px) {
		.galleryPhotos {
			height: 100%;
		}
	}

	.detailPhoto {
		display: flex;
		flex-direction: row-reverse;
		align-items: flex-end;
		justify-content: space-between;
		width: 100%;
		position: relative;
	}

	@media screen and (max-width: 765px) {
		.detailPhoto {
			flex-direction: column;
		}
	}

	.detailPhoto .containerPhoto {
		object-fit: cover;
		width: 50%;
		height: 100%;
	}

	@media screen and (max-width: 820px) {
		.detailPhoto .containerPhoto {
			width: 100%;
			height: 100%;
		}
	}

	.detailPhoto img {
		width: 100%;
		height: auto;
		display: block;
		object-fit: cover;
	}

	.cataloguePhotos {
		display: flex;
		flex-direction: column;
		padding-top: 76px;
		max-width: 80%;
		margin: auto;
	}

	@media screen and (max-width: 765px) {
		.cataloguePhotos {
			max-width: 375px;
		}
	}

	.singlePhotoOverlay {
		position: absolute;
		top: 0;
		left: 50%;
		width: 50%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 2;
		background-color: rgba(0, 0, 0, 0);
		opacity: 0;
		transition: background-color 0.3s, opacity 0.3s;
		cursor: pointer;
	}

	@media screen and (max-width: 765px) {
		.singlePhotoOverlay {
			left: 0;
			width: 100%;
			position: relative;
		}
	}

	.singlePhotoOverlay:hover {
		opacity: 0.8;
		background-color: rgba(0, 0, 0, 0.8);
	}

	.singlePhotoOverlay:hover .fullscreen-icon {
		display: block;
	}

	.singlePhotoOverlay .fullscreen-icon {
		display: none;
		left: 85%;
		position: absolute;
		top: 2%;
		width: 34px;
		height: 34px;
	}

	.containerPhoto:hover .fullscreen-iconSingle {
		display: block;
	}

	.selecteurK {
		width: 50%;
		text-align: left;
		border-bottom: solid black 1px;
		padding-bottom: 40px;
		font-family: space_monoregular;
		font-size: 14px;
		font-weight: 400;
		line-height: 21px;
		letter-spacing: 0.1em;
	}

	@media screen and (max-width: 820px) {
		.selecteurK {
			width: 100%;
			left: 10px;
			position: relative;
		}

		.selecteurK h2 {
			width: 262px;
			height: 96px;
		}
	}

	.contenairContact {
		width: 100%;
		height: 118px;
		align-items: center;
		border-bottom: solid black 1px;
		display: flex;
		justify-content: center;
	}

	@media screen and (max-width: 820px) {
		.contenairContact {
			justify-content: center;
			flex-direction: column;
		}

		.contenairContact p {
			font-family: PoppinsRegular;
			font-size: 14px;
			font-weight: 300;
			line-height: 21px;
			letter-spacing: 0em;
			width: 263px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
	}

	.contenairContact .contact {
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 50%;
	}

	@media screen and (max-width: 820px) {
		.contenairContact .contact {
			flex-direction: column;
		}
	}

	#boutonContact {
		width: 272px;
		height: 50px;
		line-height: 100%;
		background-color: #D8D8D8;
		font-family: space_monoregular;
		font-weight: 400;
		font-size: 12px;
		border-radius: 2px;
		border: none;
	}

	@media screen and (max-width: 820px) {
		#boutonContact {
			width: 263px;
		}
	}

	#boutonContact:hover {
		cursor: pointer;
		background-color: black;
		color: white;
	}

	.taxonomies {
		display: flex;
		flex-direction: column;
	}

	.naviguationPhotos {
		width: 50%;
		height: 118px;
		display: flex;
		flex-direction: column;
		align-items: flex-end;
	}

	@media screen and (max-width: 820px) {
		.naviguationPhotos {
			display: none;
		}
	}

	.naviguationArrow {
		width: 81px;
		display: flex;
		align-items: flex-end;
		justify-content: space-between;
		position: relative;
		margin-bottom: 10px;
		height: 118px;
	}

	.naviguationArrow .arrow {
		width: 25px;
		height: auto;
		background-size: cover;
		background-position: center;
		display: inline-block;
	}

	.naviguationArrow .arrow:hover+.miniPicture {
		display: flex !important;
	}

	.naviguationArrow .arrow .arrow-left {
		top: 10px;
		position: relative;
	}

	.naviguationArrow .arrow .arrow-right {
		top: 10px;
		position: relative;
	}

	.miniPicture-content {
		visibility: hidden;
		opacity: 0;
		transition: opacity 0.3s, visibility 0.3s;
	}

	.miniPicture,
	.miniPicture-left,
	.miniPicture-right {
		visibility: hidden;
		opacity: 0;
		position: relative;
		height: 71px;
		width: 81px;
		top: 8px;
		transition: visibility 0, 3s, opacity 0.3s linear;
	}

	.miniPicture a,
	.miniPicture-left a,
	.miniPicture-right a {
		display: block;
		width: 100%;
		height: 100%;
	}

	.miniPicture a img,
	.miniPicture-left a img,
	.miniPicture-right a img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.arrow:hover+.miniPicture,
	.arrow:hover+.miniPicture-left,
	.arrow:hover+.miniPicture-right {
		visibility: visible;
		opacity: 1;
	}

	#toutesLesPhotos {
		width: 272px;
		height: 50px;
		line-height: 100%;
		background-color: #D8D8D8;
		font-family: space_monoregular;
		font-weight: 400;
		font-size: 12px;
		border-radius: 2px;
		border: none;
		margin: 0 auto;
		display: block;
		position: relative;
		margin-top: 5%;
		margin-bottom: 5%;
	}

	#toutesLesPhotos:hover {
		background-color: black !important;
		color: white !important;
	}

	#toutesLesPhotos a {
		font-family: space_monoregular;
		font-size: 12px;
		font-weight: 400;
		line-height: 18px;
		letter-spacing: 0em;
		text-align: left;
	}

	#toutesLesPhotos a:hover {
		cursor: pointer;
		background-color: black !important;
		color: white !important;
	}

	.titreVousAimerezAussi {
		width: 80%;
		margin: 0 auto;
		margin-bottom: 5%;
	}

	@media screen and (max-width: 1140px) {
		.titreVousAimerezAussi {
			display: flex;
			margin: 10px 0 0 0;
			text-align: center;
			align-items: center;
			justify-content: center;
			width: 100%;
		}
	}

	.interesser {
		font-family: space_monoregular;
		font-size: 14px;
	}

	.photoNotFound {
		font-family: PoppinsRegular;
		font-size: 20px;
		text-align: center;
	}
</style>
<script>
	console.log("Affichage Miniature : son js est chargé");

	jQuery(document).ready(function($) {
		const miniPicture = $('#miniPicture');

		$('.arrow-left, .arrow-right').hover(
			function() {
				miniPicture.css({
					visibility: 'visible',
					opacity: 1
				}).html(`<a href="${$(this).data('target-url')}">
                        <img src="${$(this).data('thumbnail-url')}" alt="${$(this).hasClass('arrow-left') ? 'Photo précédente' : 'Photo suivante'}">
                    </a>`);
			},
			function() {
				miniPicture.css({
					visibility: 'hidden',
					opacity: 0
				});
			}
		);

		$('.arrow-left, .arrow-right').click(function() {
			window.location.href = $(this).data('target-url');
		});
	});
</script>

<?php
get_footer();
