<?php
/**
 * Template Name: Homepage Template
 */
get_header();
while (have_posts()) : the_post();
?>

<?php
endwhile;
get_footer();
