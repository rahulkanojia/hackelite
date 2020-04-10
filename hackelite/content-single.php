<?php
/**
 * @package injob
 */
$authordata = get_userdata(get_the_author_meta('ID'));
$post_format = get_post_format();
$contents = get_the_content();
$str_regux = '';
$sidebar_position = Inwave_Helper::getPostOption('sidebar_position', 'sidebar_position');
$show_featured_image = Inwave_Helper::getPostOption('show_featured_image');
$img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
$img_src = is_array($img) && !empty($img) ? $img[0] : '';
?>
<article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?> itemprop="blogPost" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
    <div class="post-item fit-video">
        <div class="post-content">
            <h2 class="post-title entry-title" itemprop="name" style="display: none;">
                <span itemprop="headline" content="<?php the_title('', ''); ?>"></span>
            </h3>
            <img itemprop="image" src="<?php echo esc_url($img_src); ?>" alt="" style="display: none;">
            <?php if ($show_featured_image != 'no' && has_post_thumbnail() ) { ?>
                <div class="featured-image">
                    <?php
                    the_post_thumbnail();
                    ?>
                </div>
            <?php } ?>
            <div class="post-main-content">
                <?php if(Inwave_Helper::getThemeOption('single_show_categories') || Inwave_Helper::getThemeOption('single_show_post_date') || is_sticky()){ ?>
                <div class="post-meta">
                    <ul>
                        <?php if(Inwave_Helper::getThemeOption('single_show_categories')){ ?>
                        <li><i class="ion-android-folder-open"></i> <?php the_category(', ') ?></li>
                        <?php } ?>
                        <?php if(Inwave_Helper::getThemeOption('single_show_post_date')){ ?>
                        <li><i class="ion-android-calendar"></i> <?php the_date(); ?>
                            <span itemprop="dateModified" content="<?php echo get_the_date( 'c' );?>"></span>
                            <span itemprop="datePublished" content="<?php echo get_the_date( 'c' );?>"> <?php the_date(); ?></span>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php if (is_sticky()){echo '<span class="feature-post">'.esc_html__('Sticky post', 'injob').'</span>';} ?>
                </div>
                <?php } ?>
                <div class="post-content-desc" itemprop="mainEntityOfPage" itemscope itemtype="http://schema.org/WebPage">
                    <div class="post-text">
                        <?php echo apply_filters('the_content', str_replace($str_regux, '', get_the_content())); ?>
                    </div>
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'injob').'</span>',
                        'after' => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                    ));
                    ?>
                    <div class="clearfix"></div>
                </div>

                <?php
                if(Inwave_Helper::getThemeOption('single_show_tags') && has_tag()){
                    the_tags('<div class="post-tags"><span class="tag-title"><i class="ion-ios-pricetags"></i> '.__('Tags:', 'injob').'</span>', '', '</div>');
                }
                ?>

                <div class="post-bar clearfix">
                    <?php if (get_next_post()) { ?>
                        <div class="post-bar-left">
                            <div class="next-post">
                                <span class="next-post-title"><?php echo esc_html__('Next Article : ', 'injob'); ?></span>
                                <?php next_post_link('%link'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (Inwave_Helper::getThemeOption('single_show_social_sharing')): ?>
                        <div class="post-bar-right">
                            <div class="post-social-share">
                                <span class="share-title"><?php echo esc_html__('Share : ','injob'); ?></span>
                                <?php
                                inwave_social_sharing(get_permalink(), Inwave_Helper::substrword(get_the_excerpt(), 10), get_the_title());
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (Inwave_Helper::getThemeOption('single_show_author_info')): ?>
                <?php if (get_the_author_meta('description')) : ?>
                    <div class="blog-author">
                        <?php if (get_avatar(get_the_author_meta('email'), 90)) { ?>
                            <div class="authorAvt">
                                <div class="authorAvt-inner">
                                    <?php echo get_avatar(get_the_author_meta('email'), 90) ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="authorDetails">
                            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display: none;">
                                <a itemprop="url">
                                    <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                                       <img itemprop="url" src="<?php echo $img_src?>" alt="LOGO">
                                       <meta itemprop="width" content="600">
                                       <meta itemprop="height" content="60">
                                    </span>
                                </a>
                                <span itemprop="name"><?php echo get_the_author_posts_link(); ?></span>
                            </div>
                            <div class="author-name" itemprop="author" itemtype="http://schema.org/Person">
                                <?php echo esc_html__('Author: ', 'injob'); ?> <a class="" href="<?php echo esc_url(get_author_posts_url($authordata->ID, $authordata->user_nicename)); ?>"><?php echo esc_html($authordata->user_nicename); ?></a>
                            </div>
                            <?php if (get_the_author_meta('description')) { ?>
                                <div class="caption-desc">
                                    <?php echo get_the_author_meta('description'); ?>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php endif ?>
            <?php endif ?>

            <?php
            // If comments are open or we have at least one comment, load up the comment template
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
        </div>
    </div>
</article><!-- #post-## -->