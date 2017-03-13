<?php
/**
 * Template Name: Contact Template
 */
get_header();
while (have_posts()) : the_post();
?>
<div>
    <div class="container-fluid" id="page-header">
        <div class="row">
            <div class="image-container" style="background-image: url('<?= the_post_thumbnail_url(); ?>')">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>
    </div>
    <div id="contact-page-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 inner-content-container">
                    <div class="inner-container">
                        <span class="glyphicon glyphicon-globe"></span>
                        <h3>Location</h3>
                        <p>Franklin Street 567,<br />New York, USA</p>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="inner-container">
                        <span class="glyphicon glyphicon-earphone"></span>
                        <h3>Phone</h3>
                        <p>+88 (0) 101 0000 000<br />+88 (0) 102 0000 000</p>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="inner-container">
                        <span class="glyphicon glyphicon-globe"></span>
                        <h3>Fax</h3>
                        <p>+88 (0) 101 0000 000<br />+88 (0) 102 0000 000</p>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="inner-container">
                        <span class="glyphicon glyphicon-globe"></span>
                        <h3>Email</h3>
                        <p>Sample@site.com<br />Sample@site.com</p>
                    </div>
                </div>
            </div>
            
            <div class="row" id="contact-form-container">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
                    <h3>Contact Form</h3>
                    <form method="post">
                        <div class="form-field text-field">
                            <input type="text" id="name" name="name" placeholder="Name" value="" />
                        </div>
                        <div class="form-field text-field">
                            <input type="email" id="email" name="email" placeholder="Email" value="" required="" />
                        </div>
                        <div class="form-field message-field">
                            <textarea name="message" id="message" placeholder="Message"></textarea>
                        </div>
                        <div class="form-submit">
                            <button type="submit" name="submit">Send</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?php
endwhile;
get_footer();
