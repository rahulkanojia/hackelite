<?php
/**
 * The default template for displaying content video
 * @package injob
 */

$gallery = get_post_gallery_images( get_the_ID());

$show_comment = (Inwave_Helper::getThemeOption('blog_show_post_comment') && comments_open() && get_comments_number());
$img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
$img_src = is_array($img) && !empty($img) ? $img[0] : '';
?>

<article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?> itemprop="blogPost" itemscope="itemscope" itemtype="http://schema.org/BlogPosting">
    <div class="post-item fit-video <?php echo (!$gallery && !$img_src ? 'no-featured-image' : ''); ?>">
        <?php if ($gallery) { ?>
            <div class="post-gallery">
                <ul class="gallery-carousel">
                    <?php
                    foreach($gallery as $gallery_url) {
                        echo '<li>
                            <a href="'.esc_url(get_the_permalink()).'"><img src="'.esc_url($gallery_url).'" alt=""></a>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        <?php }elseif($img_src){ ?>
            <div class="post-image">
                <img itemprop="image" src="<?php echo esc_url($img_src); ?>" alt="">
            </div>
        <?php } ?>

        <div class="post-content">
            <div class="post-main-info">
                <?php if(Inwave_Helper::getThemeOption('blog_show_categories') || Inwave_Helper::getThemeOption('blog_show_post_date') || is_sticky()){ ?>
                    <div class="post-meta">
                        <ul>
                            <?php if(Inwave_Helper::getThemeOption('blog_show_categories')){ ?>
                                <li><i class="ion-android-folder-open"></i> <?php the_category(', ') ?></li>
                            <?php } ?>
                            <?php if(Inwave_Helper::getThemeOption('blog_show_post_date')){ ?>
                                <li><i class="ion-android-calendar"></i>
                                    <span itemprop="dateModified" content="<?php echo get_the_date( 'c' );?>"></span>
                                    <span itemprop="datePublished" content="<?php echo get_the_date( 'c' );?>"> <?php the_date(); ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php if (is_sticky()){echo '<span class="feature-post">'.esc_html__('Sticky post', 'injob').'</span>';} ?>
                    </div>
                <?php } ?>
                <h3 class="post-title entry-title" itemprop="name">
                    <span itemprop="headline" content="<?php the_title('', ''); ?>"></span>
                    <a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title('', ''); ?></a>
                </h3>
                <div class="post-desc" itemprop="mainEntityOfPage" itemscope itemtype="http://schema.org/WebPage">
                    <?php /* translators: %s: Name of current post */
                    echo get_the_excerpt();
                    wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'injob') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>'));
                    ?>
                </div>

                <?php if($show_comment || Inwave_Helper::getThemeOption('blog_show_social_sharing') || Inwave_Helper::getThemeOption('blog_show_post_author')){ ?>
                    <div class="post-bar clearfix">
                        <?php if(Inwave_Helper::getThemeOption('blog_show_post_author') || $show_comment){ ?>
                            <div class="post-bar-left">
                                <?php if(Inwave_Helper::getThemeOption('blog_show_post_author')){ ?>
                                    <div class="post-author <?php echo ($show_comment ? 'has-post-comment' : ''); ?>">
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
                                        <?php echo esc_html__('by: ', 'injob'); ?> <span itemprop="author" itemtype="http://schema.org/Person"><?php echo get_the_author_posts_link(); ?></span>
                                    </div>
                                <?php } ?>
                                <?php if($show_comment){ ?>
                                    <div class="post-comment-count">
                                        <?php comments_popup_link(esc_html__('0 comment', 'injob'), esc_html__('1 Comment', 'injob'), esc_html__('% Comments', 'injob')); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if(Inwave_Helper::getThemeOption('single_show_social_sharing')){ ?>
                            <div class="post-bar-right">
                                <div class="post-social-share">
                                    <span class="share-title"><?php echo esc_html__('Share : ','injob'); ?></span>
                                    <?php
                                    inwave_social_sharing(get_permalink(), Inwave_Helper::substrword(get_the_excerpt(), 10), get_the_title());
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</article><!-- #post-## -->

