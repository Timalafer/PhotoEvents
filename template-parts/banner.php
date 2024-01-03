<h1>Photographe event</h1>
<?php
$photo_args = array(
    'post_type' => 'photos',
    'posts_per_page' => 1,
    'orderby' => 'rand',
);

$photo_query = new WP_Query($photo_args);

if ($photo_query->have_posts()) {
    while ($photo_query->have_posts()) {
        $photo_query->the_post();
        echo get_the_post_thumbnail(get_the_ID(), 'full');
    }
    wp_reset_postdata();
}
?>