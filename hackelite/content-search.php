<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package injob
 */
?>

<article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h3 class="entry-title" itemprop="name"><span itemprop="headline" content="%s"></span><a href="%s" rel="bookmark">', esc_url( get_permalink() ), esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
            <ul>
                <?php if(Inwave_Helper::getThemeOption('blog_show_categories') && get_the_category()){ ?>
                    <li><i class="ion-android-folder-open"></i> <?php the_category(', ') ?></li>
                <?php } ?>
                <?php if(Inwave_Helper::getThemeOption('blog_show_post_date') && get_the_date()){
                    $archive_year  = get_the_time('Y');
                    $archive_month = get_the_time('m');
                    ?>
                    <li><i class="ion-android-calendar"></i> <a href="<?php echo esc_url(get_month_link( $archive_year, $archive_month )); ?>">
                        <span itemprop="publisher" itemtype="https://schema.org/Organization"></span>
                        <span itemprop="dateModified" content="<?php echo get_the_date( 'c' );?>"></span>
                        <span itemprop="datePublished" content="<?php echo get_the_date( 'c' );?>"><?php echo esc_html(get_the_date()); ?></span>
                    </a></li>
                <?php } ?>
                <?php if(Inwave_Helper::getThemeOption('blog_show_post_author') && get_the_author()){ ?>
                    <li><i class="ion-android-person"></i> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span itemprop="author" itemtype="http://schema.org/Person"><?php echo esc_html(get_the_author()); ?></span></a></li>
                <?php } ?>
            </ul>
        </div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<div class="clearfix"></div>
</article><!-- #post-## -->
