<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Konkurrent
 */

get_header(); 
?>
<div>
    <div class="container-fluid" id="page-header">
        <div class="row">
            <div class="image-container" style="background-image: url('<?= the_post_thumbnail_url(); ?>')">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
    <div class="container" id="page-container">
        <div class="row">

            <div class="col-xs-12">
                <?php
                query_posts(array('post_type' => 'post'));
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                ?>

                <div class="row inner-posts-container">
                    <div class="entry-info">
                        <div class="post-icon"><i class="glyphicon glyphicon-align-left"></i></div>
                        <div class="date" title="April 30, 2016">
                            <div class="day">30</div>
                            <div class="month">Apr</div>
                        </div>
                    </div>
                    
                    <div class="col-xs-12">
                        <div class="image-container">
                            <a href="<?php the_permalink(); ?>" class="overlay"></a>
                            <?php the_post_thumbnail(); ?>
                        </div>
                        <div class="post-content-container">
                            <h4><?php the_title(); ?></h4>
                            <!--<div class="entry-meta"><span class="updated"><i class="fa fa-clock-o"></i> <a href="http://react.themecatcher.net/berri/2016/04/30/chilled-out-photogrpahy/">April 30, 2016</a></span> <span class="vcard"><i class="fa fa-user"></i> <a class="url fn" href="http://react.themecatcher.net/berri/author/themecatcher/" data-hasqtip="0" oldtitle="View all posts by ThemeCatcher" title="" aria-describedby="qtip-0">ThemeCatcher</a></span><span class="entry-meta-cats-wrap"><i class="fa fa-folder-open"></i> <a href="http://react.themecatcher.net/berri/category/resources/" rel="tag">Resources</a> / <a href="http://react.themecatcher.net/berri/category/tips-and-guides/" rel="tag">Tips and Guides</a></span><span class="entry-meta-tags-wrap"><i class="fa fa-tags"></i> <a href="http://react.themecatcher.net/berri/tag/cat/" rel="tag">cat</a> / <a href="http://react.themecatcher.net/berri/tag/cute/" rel="tag">cute</a></span></div>-->
                            <p><?php the_content(); ?></p>
                            <a href="<?php the_permalink(); ?>">Read More</a>
                        </div>
                    </div>
                </div>

                <?php endwhile; else : ?>
                        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
