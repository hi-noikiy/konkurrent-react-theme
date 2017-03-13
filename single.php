<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Konkurrent
 */

get_header(); ?>

	<div class="container" id="page-container">
            <div class="row">

                <div class="col-sm-8 col-xs-12">
                    <?php
                    if ( have_posts() ) : while ( have_posts() ) : the_post();
                    ?>

                    <div class="row inner-post-container">
                        <div class="col-xs-12">
                            <div class="image-container">
                                <a href="<?php the_permalink(); ?>" class="overlay"></a>
                                <?php the_post_thumbnail(); ?>
                            </div>
                            <div class="post-content-container">
                                <h4><?php the_title(); ?></h4>
                                <p><?php the_content(); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php endwhile;
                    endif; ?>
                </div>

                <div class="col-sm-4 col-xs-12">
                    <?php get_sidebar(); ?>
                </div>

            </div>
        </div>

<?php

get_footer();
