<footer class="site-footer">
    <div class="footer-content">


        <!-- Afficher le menu du footer -->
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer_menu',
            'container' => 'nav',
            'container_class' => 'footer-menu'
        ));
        ?>
        <?php get_template_part('template-parts/modale'); ?>
        <?php get_template_part('template-parts/lightbox'); ?>
    </div>
    <div class="footer-line"></div>
</footer>



<?php wp_footer(); ?>
</body>

</html>