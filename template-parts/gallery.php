<?php
// Récupération de 12 photos aléatoires pour le bloc initial
$args = array(
    'post_type' => 'photos',
    'posts_per_page' => 12,
);

$photos_query = new WP_Query($args);

if ($photos_query->have_posts()) {
    while ($photos_query->have_posts()) {
        $photos_query->the_post();
        if (has_post_thumbnail()) {
            $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            $categories = get_the_terms(get_the_ID(), 'categorie');
            if ($categories && !is_wp_error($categories)) {
                $category_names = array();
                foreach ($categories as $category) {
                    $category_names[] = $category->name;
                }
                $categorie_name = $categories[0]->name;
            }
?>

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




<?php
        }
    }
    wp_reset_postdata();
} else {
    echo 'Aucune photo trouvée.';
}
?>
<?php get_template_part('template-parts/lightbox'); ?>