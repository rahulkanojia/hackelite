<?php
$show_page_heading = apply_filters('inwave_show_pageheading', Inwave_Helper::getPostOption('show_pageheading', 'show_page_heading'));
$search_form_jobs_style = function_exists('iwj_option') ? iwj_option('search_form_jobs_style') : false;
$show_advanced_search_candidates = function_exists('iwj_option') ? iwj_option('show_advanced_search_candidates') : false;
$show_form_find_jobs_page_heading = Inwave_Helper::getPostOption('show_form_find_jobs_page_heading');
$show_breadcrums = Inwave_Helper::getPostOption('breadcrumbs', 'breadcrumbs');
$show_breadcrumb_page = true;
if (class_exists('IWJ_Class') && (is_page(iwj_get_page_id('login')) || is_page(iwj_get_page_id('register')) || is_page(iwj_get_page_id('lostpass')) || is_page(iwj_get_page_id('dashboard')))) {
    $show_breadcrumb_page = false;
}
ob_start();
?>

<?php if (($show_page_heading && $show_page_heading != 'no')) { ?>
    <?php if ($show_form_find_jobs_page_heading == 'yes' && iwj_get_page_id('candidates') && !(is_page(iwj_get_page_id('candidates')))) { ?>
        <div class="page-heading jobs">
            <div class="container-inner">
                <div class="container">
                    <?php
                    if (!$search_form_jobs_style) {
                        $categories = isset($_GET['iwj_cats']) ? $_GET['iwj_cats'] : '';
                        if ($categories) {
                            $filters_data = get_terms(array('taxonomy' => 'iwj_cat', 'include' => $categories));
                        } else {
                            $filters_data = array();
                        }
                        ?>
                        <h3 class="find-jobs-title"><?php echo esc_html__("Find jobs", "injob"); ?></h3>
                        <?php if (!empty($filters_data)) :
                            ?>
                            <div class="find-jobs-results"><?php echo esc_html(__('You are browsing: ', 'injob')); ?>
                <?php foreach ($filters_data as $filters_data_item) : ?>
                                    <span id="iwj-filter-selected-item-<?php echo $filters_data_item->term_id; ?>"
                                          data-termid="<?php echo $filters_data_item->term_id; ?>"
                                          data-type="job"
                                          class="iwj-filter-selected-item"><label><?php echo $filters_data_item->name ?></label>
                                    </span>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php } ?>
                    <?php
                    if ($search_form_jobs_style == 'style1') {
                        echo do_shortcode('[iwj_advanced_search style="style3"]');
                    } elseif ($search_form_jobs_style == 'style2') {
                        echo do_shortcode('[iwj_advanced_search_with_radius]');
                    } else {
                        echo do_shortcode('[iwj_find_jobs style="style2"]');
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } elseif ($show_advanced_search_candidates && iwj_get_page_id('candidates') && is_page(iwj_get_page_id('candidates'))) { ?>
        <div class="page-heading jobs">
            <div class="container-inner">
                <div class="container">
                    <?php iwj_get_template_part('parts/advanced_search_candidates'); ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="page-heading default">
            <div class="container-inner">
                <?php if (!is_page_template('page-templates/home-page.php') && $show_breadcrums && $show_breadcrums != 'no' && !is_front_page() && $show_breadcrumb_page == true) { ?>
                    <div class="breadcrumbs-top" ><?php get_template_part('blocks/breadcrumbs'); ?></div>
                <?php } ?>
                <div class="container">
                    <div class="page-title">
                        <div class="iw-heading-title">
                            <h1>
        <?php
        $text['home'] = esc_html__('Home', 'injob'); // text for the 'Home' link
        $text['category'] = esc_html__('%s', 'injob'); // text for a category page
        $text['tax'] = esc_html__('Archive for "%s"', 'injob'); // text for a taxonomy page
        $text['search'] = esc_html__('Search Results for "%s" Query', 'injob'); // text for a search results page
        $text['tag'] = esc_html__('Posts Tagged "%s"', 'injob'); // text for a tag page
        $text['author'] = esc_html__('Articles Posted by %s', 'injob'); // text for an author page
        $text['404'] = esc_html__('Oops! That page can&rsquo;t be found', 'injob'); // text for the 404 page

        $page_title = '';
        if (is_home()) {
            $page_id = get_option('page_for_posts', true);
            if ($page_id) {
                $page_title .= get_the_title($page_id);
            } else {
                $page_title .= get_bloginfo('name');
            }
        } elseif (is_category()) {
            $page_title .= sprintf($text['category'], single_cat_title('', false));
        } elseif (is_tax()) {
            if (is_tax('cat')) {
                $page_title .= sprintf($text['tax'], single_cat_title('', false));
            } else {
                $page_title .= sprintf(single_cat_title('', false));
            }
        } elseif (is_search()) {
            $page_title .= sprintf($text['search'], get_search_query());
        } elseif (is_day()) {
            $page_title .= sprintf($text['tax'], get_the_time('F jS, Y'));
        } elseif (is_month()) {
            $page_title .= sprintf($text['tax'], get_the_time('F, Y'));
        } elseif (is_year()) {
            $page_title .= sprintf($text['tax'], get_the_time('Y'));
        } elseif (is_single()) {
            if (function_exists('is_product') && is_product()) {
                $page_title .= get_the_title(wc_get_page_id('shop'));
            } else {
                $page_title .= get_the_title();
            }
        } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_author()) {
            if (function_exists('is_shop') && is_shop()) {
                $page_title .= get_the_title(wc_get_page_id('shop'));
            } else {
                $post_type = get_post_type_object(get_post_type());
                $page_title .= $post_type->labels->all_items;
            }
        } elseif (is_page()) {
            $post = get_post();
            if (function_exists('iwj_get_page_id') && iwj_get_page_id('dashboard') == $post->ID) {
                $tab_title = IWJ_Front::tab_title();
                if ($tab_title) {
                    $page_title .= $tab_title;
                } else {
                    $page_title .= get_the_title();
                }
            } else {
                $page_title .= get_the_title();
            }
        } elseif (is_tag()) {
            $page_title .= single_tag_title('', false);
        } elseif (is_author()) {
            $author = get_the_author_meta('ID');
            $userdata = get_userdata($author);
            $page_title .= sprintf($text['author'], $userdata->display_name);
        } elseif (is_404()) {
            $page_title .= $text['404'];
        }

        echo esc_html($page_title);
        ?>
                            </h1>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php } ?>
<?php } else {
    if (!is_page_template('page-templates/home-page.php') && $show_breadcrums && $show_breadcrums != 'no' && !is_front_page() && $show_breadcrumb_page == true) {
        ?>
        <div class="breadcrumbs-top breadcrumbs__only" ><?php get_template_part('blocks/breadcrumbs'); ?></div>
    <?php
    }
}
$page_heading_html = ob_get_contents();
ob_end_clean();
echo apply_filters('inwave_page_heading', $page_heading_html);
?>