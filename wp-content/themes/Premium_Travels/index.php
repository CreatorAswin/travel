<?php
get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (have_posts()):
                    while (have_posts()):
                        the_post();
                        ?>
                        <div class="blog-post">
                            <h2><a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a></h2>
                            <div class="post-content">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    the_posts_pagination();
                else:
                    echo '<p>No content found.</p>';
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>