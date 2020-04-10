<?php
/**
 * Theme function file.
 */

/**
 * Add class to nav menu
 */

if (!is_admin()) {
    function inwave_nav_class($classes, $item)
    {
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'selected active ';
        }
        return $classes;
    }

    add_filter('nav_menu_css_class', 'inwave_nav_class', 10, 2);
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function inwave_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'inwave_page_menu_args' );

/* add body class: support white color and boxed layout */
if(!function_exists('inwave_add_body_class')){
    function inwave_add_body_class($classes){

        $page_class = Inwave_Helper::getPostOption('page_class');
        if($page_class){
            $classes[] = $page_class;
        }

        $layout = Inwave_Helper::getPanelSetting('layout');
        if($layout=='boxed'){
            $classes[] = 'body-boxed';
        }

        return $classes;
    }

    add_filter( 'body_class', 'inwave_add_body_class');
}

if(!function_exists('inwave_monster_widget')){
    function inwave_monster_widget($widgets){
        if($widgets){
            foreach ($widgets as $key => $widget){
                if($widget[0] == 'WP_Widget_Recent_Comments'){
                    $widgets[$key][0] = 'Inwave_Recent_Comments_Widget';
                }elseif($widget[0] == 'WP_Widget_Recent_Posts'){
                    $widgets[$key][0] = 'Inwave_Recent_Posts_Widget';
                }
            }
        }

        return $widgets;
    }
    add_filter('monster-widget-config', 'inwave_monster_widget');
}

if (!function_exists('inwave_comment')) {
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own inwave_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.

     */
    function inwave_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                <p><?php esc_html_e('Pingback:', 'injob'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(esc_html__('(Edit)', 'injob'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default :
                // Proceed with normal comments.
                ?>
            <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <div id="comment-<?php comment_ID(); ?>" class="comment answer">
                    <div class="commentAvt commentLeft">
                        <?php echo get_avatar(get_comment_author_email() ? get_comment_author_email() : $comment, 91); ?>
                    </div>
                    <!-- .comment-meta -->
                    <div class="commentRight">
                        <div class="content-cmt">
                            <div class="comment-head-info">
								<span class="name-cmt"><?php echo get_comment_author_link() ?></span>
								<span class="date-cmt"><?php printf( _x( ' %s ago', '%s = human-readable time difference', 'injob' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ) ?></span>
                                <div class="content-reply">
                                    <?php comment_text(); ?>
                                    <?php if ('0' == $comment->comment_approved) : ?>
                                        <p class="comment-awaiting-moderation theme-color"><?php esc_html_e('Your comment is awaiting moderation.', 'injob'); ?></p>
                                    <?php endif; ?>

                                </div>
								<div class="comment_reply"><?php comment_reply_link(array_merge($args, array('reply_text' => '<i class="ion-reply"></i>'.' '.esc_html__('Reply', 'injob'), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth']))); edit_comment_link(esc_html__('Edit', 'injob'), '<span class="edit-link">', '</span>'); ?></div>

                            </div>
                        </div>
                    </div>
                    <!-- .comment-content -->
                   
                    <div class="clearfix"></div>
                </div>
                <!-- #comment-## -->
                <?php
                break;
        endswitch; // end comment_type check
    }

}

if (!function_exists('inwave_getElementsByTag')) {

    /**
     * Function to get element by tag
     * @param string $tag tag name. Eg: embed, iframe...
     * @param string $content content to find
     * @param int $type type of tag. <br/> 1. [tag_name settings]content[/tag_name]. <br/>2. [tag_name settings]. <br/>3. HTML tags.
     * @return type
     */
    function inwave_getElementsByTag($tag, $content, $type = 1)
    {
        if ($type == 1) {
            $pattern = "/\[$tag(.*)\](.*)\[\/$tag\]/Uis";
        } elseif ($type == 2) {
            $pattern = "/\[$tag(.*)\]/Uis";
        } elseif ($type == 3) {
            $pattern = "/\<$tag(.*)\>(.*)\<\/$tag\>/Uis";
        } else {
            $pattern = null;
        }
        $find = null;
        if ($pattern) {
            preg_match($pattern, $content, $matches);
            if ($matches) {
                $find = $matches;
            }
        }
        return $find;
    }

}


if (!function_exists('inwave_social_sharing')) {

    /**
     *
     * @global type $inwave_theme_option
     * @param String $link Link to share
     * @param String $text the text content to share
     * @param String $title the title to share
     * @param String $tag the wrap html tag
     */
    function inwave_social_sharing($link, $text, $title, $tag = '')
    {
        $newWindow = 'onclick="return InwaveOpenWindow(this.href);"';
        $title = urlencode($title);
        $text = '';
        if(is_single() && has_excerpt()){
            $text = wp_trim_words(get_the_excerpt(), 15);
        }
        $text = urlencode($text);
        $link = urlencode($link);
        $html = '';
        if (Inwave_Helper::getThemeOption('sharing_facebook')) {
            $shareLink = 'https://www.facebook.com/sharer.php?s=100&amp;t=' . $title . '&amp;u='. $link;
            if(is_single() && has_post_thumbnail()){
                $thumb_id = get_post_thumbnail_id();
                $thumb_url = wp_get_attachment_image_src($thumb_id,'large', true);
                $shareLink .= '&amp;picture='.$thumb_url[0];
            }
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="social-share-item share-buttons-fb" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Facebook','title','injob') . '" ' . $newWindow . '><i class="fa fa-facebook"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if (Inwave_Helper::getThemeOption('sharing_twitter')) {
            $shareLink = 'https://twitter.com/share?url=' . $link . '&amp;text=' . $text;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="social-share-item share-buttons-tt" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Twitter','title','injob') . '" ' . $newWindow . '><i class="fa fa-twitter"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if (Inwave_Helper::getThemeOption('sharing_linkedin')) {
            $shareLink = 'https://www.linkedin.com/shareArticle?mini=true&amp;url=' . $link . '&amp;title=' . $title . '&amp;summary=' . $text;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="social-share-item share-buttons-linkedin" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Linkedin','title','injob') . '" ' . $newWindow . '><i class="fa fa-linkedin"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if (Inwave_Helper::getThemeOption('sharing_google')) {
            $shareLink = 'https://plus.google.com/share?url=' . $link . '&amp;title=' . $title;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="social-share-item share-buttons-gg" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Google Plus','title','injob') . '" ' . $newWindow . '><i class="fa fa-google-plus"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if (Inwave_Helper::getThemeOption('sharing_tumblr')) {
            $shareLink = 'http://www.tumblr.com/share/link?url=' . $link . '&amp;description=' . $text . '&amp;name=' . $title;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="social-share-item share-buttons-tumblr" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Tumblr','title','injob') . '" ' . $newWindow . '><i class="fa fa-tumblr-square"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if (Inwave_Helper::getThemeOption('sharing_pinterest')) {
            $shareLink = 'http://pinterest.com/pin/create/button/?url=' . $link . '&amp;description=' . $text . '&amp;media=' . $link;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="social-share-item share-buttons-pinterest" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Pinterest','title', 'injob') . '" ' . $newWindow . '><i class="fa fa-pinterest"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if (Inwave_Helper::getThemeOption('sharing_email')) {
            $shareLink = 'mailto:?subject=' . esc_attr_x('I wanted you to see this site','title', 'injob') . '&amp;body=' . $link . '&amp;title=' . $title;
            $html .= ($tag ? '<' . $tag . '>' : '') . '<a class="social-share-item share-buttons-email" href="' . esc_attr($shareLink) . '" title="' . esc_attr_x('Email','title', 'injob') . '"><i class="fa fa-envelope"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }

        echo $html;
    }
}

if (!function_exists('inwave_social_sharing_category_listing')) {

    /**
     *
     * @global type $inwave_theme_option
     * @param String $link Link to share
     * @param String $text the text content to share
     * @param String $title the title to share
     * @param String $tag the wrap html tag
     */
    function inwave_social_sharing_category_listing($link, $text, $title, $tag = '')
    {
        $newWindow = 'onclick="return InwaveOpenWindow(this.href);"';
        $title = urlencode($title);
        $text = urlencode($text);
        $link = urlencode($link);
        if (Inwave_Helper::getThemeOption('sharing_facebook')) {
            $shareLink = 'https://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $title . '&amp;p[url]=' . $link . '&amp;p[summary]=' . $text;
            echo ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-fb" target="_blank" href="#" title="' . esc_attr_x('Share on Facebook','title','injob') . '" onclick="return InwaveOpenWindow(\'' . esc_js($shareLink) . '\')"><i class="fa fa-facebook"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
        if (Inwave_Helper::getThemeOption('sharing_google')) {
            $shareLink = 'https://plus.google.com/share?url=' . $link . '&amp;title=' . $title;
            echo ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-gg" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Google Plus','title','injob') . '" ' . $newWindow . '><i class="fa fa-google-plus"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
		if (Inwave_Helper::getThemeOption('sharing_twitter')) {
            $shareLink = 'https://twitter.com/share?url=' . $link . '&amp;text=' . $text;
            echo ($tag ? '<' . $tag . '>' : '') . '<a class="share-buttons-tt" target="_blank" href="' . esc_url($shareLink) . '" title="' . esc_attr_x('Share on Twitter','title','injob') . '" ' . $newWindow . '><i class="fa fa-twitter"></i></a>' . ($tag ? '</' . $tag . '>' : '');
        }
    }
}

if (!function_exists('inwave_get_social_link')) {
    function inwave_get_social_link()
    {
        $html = '';

        if(count(Inwave_Helper::getThemeOption('social_links')) > 1){
            $social_links = Inwave_Helper::getThemeOption('social_links');
            unset($social_links[0]);
            $html = '<ul class="iw-social-all">';
            foreach ($social_links as $social_link) {
                $class = explode(".com", $social_link['link']);
                $class = str_replace(array('https://', 'http://', 'www.'), '', $class[0]);
                if ($class == 'plus.google') {
                    $class = 'google-plus';
                }
                $html .='<li><a target="_blank" class="'.$class.'" href="' . esc_url($social_link['link']) . '" title="' . esc_attr($social_link['title']) . '"><i class="fa ' . esc_attr($social_link['icon']) . '"></i></a></li>';
            }
            $html .= '</ul>';
        }

        return $html;
    }
}

if (!function_exists('inwave_get_class')) {
    function inwave_get_classes($type,$sidebar, $sidebar_size = 'large')
    {
        $classes = '';
        switch ($type) {
            case 'container':
                $classes = 'col-sm-12 col-xs-12';
                if ($sidebar == 'left' || $sidebar == 'right') {
                    if($sidebar_size == 'large'){
                        $classes .= ' col-lg-8 col-md-8';
                    }else{
                        $classes .= ' col-lg-9 col-md-9';
                    }
                    if ($sidebar == 'left') {
                        $classes .= ' pull-right';
                    }
                }
                break;
            case 'sidebar':
                $classes = 'col-sm-12 col-xs-12';
                if ($sidebar == 'left' || $sidebar == 'right') {
                    if($sidebar_size == 'large'){
                        $classes .= ' col-lg-4 col-md-4';
                    }else{
                        $classes .= ' col-lg-3 col-md-3';
                    }
                }
                if ($sidebar == 'bottom') {
                    $classes .= ' pull-' . $sidebar;
                }
                break;
        }
        return $classes;
    }
}

if (!function_exists('inwave_get_class_job')) {
    function inwave_get_class_job($type_job,$sidebar_job)
    {
        $classes_job = '';
        switch ($type_job) {
            case 'container':
                $classes_job = 'col-sm-12 col-xs-12';
                if ($sidebar_job == 'left' || $sidebar_job == 'right') {
                    $classes_job .= ' col-lg-9 col-md-9';
                    if ($sidebar_job == 'left') {
                        $classes_job .= ' pull-right';
                    }
                }
                break;
            case 'sidebar':
                $classes_job = 'col-sm-12 col-xs-12';
                if ($sidebar_job == 'left' || $sidebar_job == 'right') {
                    $classes_job .= ' col-lg-3 col-md-3';
                }
                break;
        }
        return $classes_job;
    }
}

if (!function_exists('inwave_allow_tags')) {

    function inwave_allow_tags($tag = null)
    {
        $inwave_tag_allowed = wp_kses_allowed_html('post');

        $inwave_tag_allowed['input'] = array(
            'class' => array(),
            'id' => array(),
            'name' => array(),
            'value' => array(),
            'checked' => array(),
            'type' => array()
        );
        $inwave_tag_allowed['select'] = array(
            'class' => array(),
            'id' => array(),
            'name' => array(),
            'value' => array(),
            'multiple' => array(),
            'type' => array()
        );
        $inwave_tag_allowed['option'] = array(
            'value' => array(),
            'selected' => array()
        );

        if($tag == null){
            return $inwave_tag_allowed;
        }
        elseif(is_array($tag)){
            $new_tag_allow = array();
            foreach ($tag as $_tag){
                $new_tag_allow[$_tag] = $inwave_tag_allowed[$_tag];
            }

            return $new_tag_allow;
        }
        else{
            return isset($inwave_tag_allowed[$tag]) ? array($tag=>$inwave_tag_allowed[$tag]) : array();
        }
    }
}

if (!function_exists('inwave_get_post_views')) {

    function inwave_get_post_views($postID){
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
        }
        return $count;
    }
}

if (!function_exists('inwave_social_sharing_fb')) {
    /**
     *
     * @global type $inwave_theme_option
     * @param String $link Link to share
     * @param String $text the text content to share
     * @param String $title the title to share
     * @param String $tag the wrap html tag
     */
    function inwave_social_sharing_fb($link, $text, $title)
    {
        $title = urlencode($title);
        $text = urlencode($text);
        $link = urlencode($link);
        $shareLink = 'https://www.facebook.com/sharer.php?s=100&amp;p[title]=' . $title . '&amp;p[url]=' . $link . '&amp;p[summary]=' . $text;
        echo '<a class="share-buttons-fb" target="_blank" href="#" title="' . esc_attr_x('Share on Facebook','title', 'injob') . '" onclick="return InwaveOpenWindow(\'' . esc_js($shareLink) . '\')"><i class="fa fa-share"></i><span>share</span></a>';
    }
}

if(!function_exists('inwave_check_cart_url')){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    function inwave_check_cart_url(){
        if(!isset($cartUrl)){
            $cartUrl = '';
        }
        if(function_exists('WC')) {
            $cartUrl = wc_get_cart_url();
        }
        echo esc_url($cartUrl);
    }
}

if(!function_exists('inwave_count_product')){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    function inwave_count_product(){
        $count = 0;
        if(function_exists('WC')) {
            $count = WC()->cart->cart_contents_count;
        }
        return $count;
    }
}

if(!function_exists('inwave_get_googlefonts')){
    function inwave_get_googlefonts($flip = true){
        $fonts = array(
            "" => "Select Font",
            "ABeeZee" => "ABeeZee",
            "Abel" => "Abel",
            "Abril Fatface" => "Abril Fatface",
            "Aclonica" => "Aclonica",
            "Acme" => "Acme",
            "Actor" => "Actor",
            "Adamina" => "Adamina",
            "Advent Pro" => "Advent Pro",
            "Aguafina Script" => "Aguafina Script",
            "Akronim" => "Akronim",
            "Aladin" => "Aladin",
            "Aldrich" => "Aldrich",
            "Alef" => "Alef",
            "Alegreya" => "Alegreya",
            "Alegreya SC" => "Alegreya SC",
            "Alegreya Sans" => "Alegreya Sans",
            "Alegreya Sans SC" => "Alegreya Sans SC",
            "Alex Brush" => "Alex Brush",
            "Alfa Slab One" => "Alfa Slab One",
            "Alice" => "Alice",
            "Alike" => "Alike",
            "Alike Angular" => "Alike Angular",
            "Allan" => "Allan",
            "Allerta" => "Allerta",
            "Allerta Stencil" => "Allerta Stencil",
            "Allura" => "Allura",
            "Almendra" => "Almendra",
            "Almendra Display" => "Almendra Display",
            "Almendra SC" => "Almendra SC",
            "Amarante" => "Amarante",
            "Amaranth" => "Amaranth",
            "Amatic SC" => "Amatic SC",
            "Amethysta" => "Amethysta",
            "Anaheim" => "Anaheim",
            "Andada" => "Andada",
            "Andika" => "Andika",
            "Angkor" => "Angkor",
            "Annie Use Your Telescope" => "Annie Use Your Telescope",
            "Anonymous Pro" => "Anonymous Pro",
            "Antic" => "Antic",
            "Antic Didone" => "Antic Didone",
            "Antic Slab" => "Antic Slab",
            "Anton" => "Anton",
            "Arapey" => "Arapey",
            "Arbutus" => "Arbutus",
            "Arbutus Slab" => "Arbutus Slab",
            "Architects Daughter" => "Architects Daughter",
            "Archivo Black" => "Archivo Black",
            "Archivo Narrow" => "Archivo Narrow",
            "Arimo" => "Arimo",
            "Arizonia" => "Arizonia",
            "Armata" => "Armata",
            "Artifika" => "Artifika",
            "Arvo" => "Arvo",
            "Asap" => "Asap",
            "Asset" => "Asset",
            "Astloch" => "Astloch",
            "Asul" => "Asul",
            "Atomic Age" => "Atomic Age",
            "Aubrey" => "Aubrey",
            "Audiowide" => "Audiowide",
            "Autour One" => "Autour One",
            "Average" => "Average",
            "Average Sans" => "Average Sans",
            "Averia Gruesa Libre" => "Averia Gruesa Libre",
            "Averia Libre" => "Averia Libre",
            "Averia Sans Libre" => "Averia Sans Libre",
            "Averia Serif Libre" => "Averia Serif Libre",
            "Bad Script" => "Bad Script",
            "Balthazar" => "Balthazar",
            "Bangers" => "Bangers",
            "Basic" => "Basic",
            "Battambang" => "Battambang",
            "Baumans" => "Baumans",
            "Bayon" => "Bayon",
            "Belgrano" => "Belgrano",
            "Belleza" => "Belleza",
            "BenchNine" => "BenchNine",
            "Bentham" => "Bentham",
            "Berkshire Swash" => "Berkshire Swash",
            "Bevan" => "Bevan",
            "Bigelow Rules" => "Bigelow Rules",
            "Bigshot One" => "Bigshot One",
            "Bilbo" => "Bilbo",
            "Bilbo Swash Caps" => "Bilbo Swash Caps",
            "Bitter" => "Bitter",
            "Black Ops One" => "Black Ops One",
            "Bokor" => "Bokor",
            "Bonbon" => "Bonbon",
            "Boogaloo" => "Boogaloo",
            "Bowlby One" => "Bowlby One",
            "Bowlby One SC" => "Bowlby One SC",
            "Brawler" => "Brawler",
            "Bree Serif" => "Bree Serif",
            "Bubblegum Sans" => "Bubblegum Sans",
            "Bubbler One" => "Bubbler One",
            "Buda" => "Buda",
            "Buenard" => "Buenard",
            "Butcherman" => "Butcherman",
            "Butterfly Kids" => "Butterfly Kids",
            "Cabin" => "Cabin",
            "Cabin Condensed" => "Cabin Condensed",
            "Cabin Sketch" => "Cabin Sketch",
            "Caesar Dressing" => "Caesar Dressing",
            "Cagliostro" => "Cagliostro",
            "Calligraffitti" => "Calligraffitti",
            "Cambo" => "Cambo",
            "Candal" => "Candal",
            "Cantarell" => "Cantarell",
            "Cantata One" => "Cantata One",
            "Cantora One" => "Cantora One",
            "Capriola" => "Capriola",
            "Cardo" => "Cardo",
            "Carme" => "Carme",
            "Carrois Gothic" => "Carrois Gothic",
            "Carrois Gothic SC" => "Carrois Gothic SC",
            "Carter One" => "Carter One",
            "Caudex" => "Caudex",
            "Cedarville Cursive" => "Cedarville Cursive",
            "Ceviche One" => "Ceviche One",
            "Changa One" => "Changa One",
            "Chango" => "Chango",
            "Chau Philomene One" => "Chau Philomene One",
            "Chela One" => "Chela One",
            "Chelsea Market" => "Chelsea Market",
            "Chenla" => "Chenla",
            "Cherry Cream Soda" => "Cherry Cream Soda",
            "Cherry Swash" => "Cherry Swash",
            "Chewy" => "Chewy",
            "Chicle" => "Chicle",
            "Chivo" => "Chivo",
            "Cinzel" => "Cinzel",
            "Cinzel Decorative" => "Cinzel Decorative",
            "Clicker Script" => "Clicker Script",
            "Coda" => "Coda",
            "Coda Caption" => "Coda Caption",
            "Codystar" => "Codystar",
            "Combo" => "Combo",
            "Comfortaa" => "Comfortaa",
            "Coming Soon" => "Coming Soon",
            "Concert One" => "Concert One",
            "Condiment" => "Condiment",
            "Content" => "Content",
            "Contrail One" => "Contrail One",
            "Convergence" => "Convergence",
            "Cookie" => "Cookie",
            "Copse" => "Copse",
            "Corben" => "Corben",
            "Courgette" => "Courgette",
            "Cousine" => "Cousine",
            "Coustard" => "Coustard",
            "Covered By Your Grace" => "Covered By Your Grace",
            "Crafty Girls" => "Crafty Girls",
            "Creepster" => "Creepster",
            "Crete Round" => "Crete Round",
            "Crimson Text" => "Crimson Text",
            "Croissant One" => "Croissant One",
            "Crushed" => "Crushed",
            "Cuprum" => "Cuprum",
            "Cutive" => "Cutive",
            "Cutive Mono" => "Cutive Mono",
            "Damion" => "Damion",
            "Dancing Script" => "Dancing Script",
            "Dangrek" => "Dangrek",
            "Dawning of a New Day" => "Dawning of a New Day",
            "Days One" => "Days One",
            "Delius" => "Delius",
            "Delius Swash Caps" => "Delius Swash Caps",
            "Delius Unicase" => "Delius Unicase",
            "Della Respira" => "Della Respira",
            "Denk One" => "Denk One",
            "Devonshire" => "Devonshire",
            "Didact Gothic" => "Didact Gothic",
            "Diplomata" => "Diplomata",
            "Diplomata SC" => "Diplomata SC",
            "Domine" => "Domine",
            "Donegal One" => "Donegal One",
            "Doppio One" => "Doppio One",
            "Dorsa" => "Dorsa",
            "Dosis" => "Dosis",
            "Dr Sugiyama" => "Dr Sugiyama",
            "Droid Sans" => "Droid Sans",
            "Droid Sans Mono" => "Droid Sans Mono",
            "Droid Serif" => "Droid Serif",
            "Duru Sans" => "Duru Sans",
            "Dynalight" => "Dynalight",
            "EB Garamond" => "EB Garamond",
            "Eagle Lake" => "Eagle Lake",
            "Eater" => "Eater",
            "Economica" => "Economica",
            "Electrolize" => "Electrolize",
            "Elsie" => "Elsie",
            "Elsie Swash Caps" => "Elsie Swash Caps",
            "Emblema One" => "Emblema One",
            "Emilys Candy" => "Emilys Candy",
            "Engagement" => "Engagement",
            "Englebert" => "Englebert",
            "Enriqueta" => "Enriqueta",
            "Erica One" => "Erica One",
            "Esteban" => "Esteban",
            "Euphoria Script" => "Euphoria Script",
            "Ewert" => "Ewert",
            "Exo" => "Exo",
            "Exo 2" => "Exo 2",
            "Expletus Sans" => "Expletus Sans",
            "Fanwood Text" => "Fanwood Text",
            "Fascinate" => "Fascinate",
            "Fascinate Inline" => "Fascinate Inline",
            "Faster One" => "Faster One",
            "Fasthand" => "Fasthand",
            "Fauna One" => "Fauna One",
            "Federant" => "Federant",
            "Federo" => "Federo",
            "Felipa" => "Felipa",
            "Fenix" => "Fenix",
            "Finger Paint" => "Finger Paint",
            "Fjalla One" => "Fjalla One",
            "Fjord One" => "Fjord One",
            "Flamenco" => "Flamenco",
            "Flavors" => "Flavors",
            "Fondamento" => "Fondamento",
            "Fontdiner Swanky" => "Fontdiner Swanky",
            "Forum" => "Forum",
            "Francois One" => "Francois One",
            "Freckle Face" => "Freckle Face",
            "Fredericka the Great" => "Fredericka the Great",
            "Fredoka One" => "Fredoka One",
            "Freehand" => "Freehand",
            "Fresca" => "Fresca",
            "Frijole" => "Frijole",
            "Fruktur" => "Fruktur",
            "Fugaz One" => "Fugaz One",
            "GFS Didot" => "GFS Didot",
            "GFS Neohellenic" => "GFS Neohellenic",
            "Gabriela" => "Gabriela",
            "Gafata" => "Gafata",
            "Galdeano" => "Galdeano",
            "Galindo" => "Galindo",
            "Gentium Basic" => "Gentium Basic",
            "Gentium Book Basic" => "Gentium Book Basic",
            "Geo" => "Geo",
            "Geostar" => "Geostar",
            "Geostar Fill" => "Geostar Fill",
            "Germania One" => "Germania One",
            "Gilda Display" => "Gilda Display",
            "Give You Glory" => "Give You Glory",
            "Glass Antiqua" => "Glass Antiqua",
            "Glegoo" => "Glegoo",
            "Gloria Hallelujah" => "Gloria Hallelujah",
            "Goblin One" => "Goblin One",
            "Gochi Hand" => "Gochi Hand",
            "Gorditas" => "Gorditas",
            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
            "Graduate" => "Graduate",
            "Grand Hotel" => "Grand Hotel",
            "Gravitas One" => "Gravitas One",
            "Great Vibes" => "Great Vibes",
            "Griffy" => "Griffy",
            "Gruppo" => "Gruppo",
            "Gudea" => "Gudea",
            "Habibi" => "Habibi",
			"Hind"=>"Hind",
            "Hammersmith One" => "Hammersmith One",
            "Hanalei" => "Hanalei",
            "Hanalei Fill" => "Hanalei Fill",
            "Handlee" => "Handlee",
            "Hanuman" => "Hanuman",
            "Happy Monkey" => "Happy Monkey",
            "Headland One" => "Headland One",
            "Henny Penny" => "Henny Penny",
            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
            "Holtwood One SC" => "Holtwood One SC",
            "Homemade Apple" => "Homemade Apple",
            "Homenaje" => "Homenaje",
            "IM Fell DW Pica" => "IM Fell DW Pica",
            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
            "IM Fell Double Pica" => "IM Fell Double Pica",
            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
            "IM Fell English" => "IM Fell English",
            "IM Fell English SC" => "IM Fell English SC",
            "IM Fell French Canon" => "IM Fell French Canon",
            "IM Fell French Canon SC" => "IM Fell French Canon SC",
            "IM Fell Great Primer" => "IM Fell Great Primer",
            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
            "Iceberg" => "Iceberg",
            "Iceland" => "Iceland",
            "Imprima" => "Imprima",
            "Inconsolata" => "Inconsolata",
            "Inder" => "Inder",
            "Indie Flower" => "Indie Flower",
            "Inika" => "Inika",
            "Irish Grover" => "Irish Grover",
            "Istok Web" => "Istok Web",
            "Italiana" => "Italiana",
            "Italianno" => "Italianno",
            "Jacques Francois" => "Jacques Francois",
            "Jacques Francois Shadow" => "Jacques Francois Shadow",
            "Jim Nightshade" => "Jim Nightshade",
            "Jockey One" => "Jockey One",
            "Jolly Lodger" => "Jolly Lodger",
            "Josefin Sans" => "Josefin Sans",
            "Josefin Slab" => "Josefin Slab",
            "Joti One" => "Joti One",
            "Judson" => "Judson",
            "Julee" => "Julee",
            "Julius Sans One" => "Julius Sans One",
            "Junge" => "Junge",
            "Jura" => "Jura",
            "Just Another Hand" => "Just Another Hand",
            "Just Me Again Down Here" => "Just Me Again Down Here",
            "Kameron" => "Kameron",
            "Kantumruy" => "Kantumruy",
            "Karla" => "Karla",
            "Kaushan Script" => "Kaushan Script",
            "Kavoon" => "Kavoon",
            "Kdam Thmor" => "Kdam Thmor",
            "Keania One" => "Keania One",
            "Kelly Slab" => "Kelly Slab",
            "Kenia" => "Kenia",
            "Khmer" => "Khmer",
            "Kite One" => "Kite One",
            "Knewave" => "Knewave",
            "Kotta One" => "Kotta One",
            "Koulen" => "Koulen",
            "Kranky" => "Kranky",
            "Kreon" => "Kreon",
            "Kristi" => "Kristi",
            "Krona One" => "Krona One",
            "La Belle Aurore" => "La Belle Aurore",
            "Lancelot" => "Lancelot",
            "Lato" => "Lato",
            "League Script" => "League Script",
            "Leckerli One" => "Leckerli One",
            "Ledger" => "Ledger",
            "Lekton" => "Lekton",
            "Lemon" => "Lemon",
            "Libre Baskerville" => "Libre Baskerville",
            "Life Savers" => "Life Savers",
            "Lilita One" => "Lilita One",
            "Lily Script One" => "Lily Script One",
            "Limelight" => "Limelight",
            "Linden Hill" => "Linden Hill",
            "Lobster" => "Lobster",
            "Lobster Two" => "Lobster Two",
            "Londrina Outline" => "Londrina Outline",
            "Londrina Shadow" => "Londrina Shadow",
            "Londrina Sketch" => "Londrina Sketch",
            "Londrina Solid" => "Londrina Solid",
            "Lora" => "Lora",
            "Love Ya Like A Sister" => "Love Ya Like A Sister",
            "Loved by the King" => "Loved by the King",
            "Lovers Quarrel" => "Lovers Quarrel",
            "Luckiest Guy" => "Luckiest Guy",
            "Lusitana" => "Lusitana",
            "Lustria" => "Lustria",
            "Macondo" => "Macondo",
            "Macondo Swash Caps" => "Macondo Swash Caps",
            "Magra" => "Magra",
            "Maiden Orange" => "Maiden Orange",
            "Mako" => "Mako",
            "Marcellus" => "Marcellus",
            "Marcellus SC" => "Marcellus SC",
            "Marck Script" => "Marck Script",
            "Margarine" => "Margarine",
            "Marko One" => "Marko One",
            "Marmelad" => "Marmelad",
            "Marvel" => "Marvel",
            "Mate" => "Mate",
            "Mate SC" => "Mate SC",
            "Maven Pro" => "Maven Pro",
            "McLaren" => "McLaren",
            "Meddon" => "Meddon",
            "MedievalSharp" => "MedievalSharp",
            "Medula One" => "Medula One",
            "Megrim" => "Megrim",
            "Meie Script" => "Meie Script",
            "Merienda" => "Merienda",
            "Merienda One" => "Merienda One",
            "Merriweather" => "Merriweather",
            "Merriweather Sans" => "Merriweather Sans",
            "Metal" => "Metal",
            "Metal Mania" => "Metal Mania",
            "Metamorphous" => "Metamorphous",
            "Metrophobic" => "Metrophobic",
            "Michroma" => "Michroma",
            "Milonga" => "Milonga",
            "Miltonian" => "Miltonian",
            "Miltonian Tattoo" => "Miltonian Tattoo",
            "Miniver" => "Miniver",
            "Miss Fajardose" => "Miss Fajardose",
            "Modern Antiqua" => "Modern Antiqua",
            "Molengo" => "Molengo",
            "Molle" => "Molle",
            "Monda" => "Monda",
            "Monofett" => "Monofett",
            "Monoton" => "Monoton",
            "Monsieur La Doulaise" => "Monsieur La Doulaise",
            "Montaga" => "Montaga",
            "Montez" => "Montez",
            "Montserrat" => "Montserrat",
            "Montserrat Alternates" => "Montserrat Alternates",
            "Montserrat Subrayada" => "Montserrat Subrayada",
            "Moul" => "Moul",
            "Moulpali" => "Moulpali",
            "Mountains of Christmas" => "Mountains of Christmas",
            "Mouse Memoirs" => "Mouse Memoirs",
            "Mr Bedfort" => "Mr Bedfort",
            "Mr Dafoe" => "Mr Dafoe",
            "Mr De Haviland" => "Mr De Haviland",
            "Mrs Saint Delafield" => "Mrs Saint Delafield",
            "Mrs Sheppards" => "Mrs Sheppards",
            "Muli" => "Muli",
            "Mystery Quest" => "Mystery Quest",
            "Neucha" => "Neucha",
            "Neuton" => "Neuton",
            "New Rocker" => "New Rocker",
            "News Cycle" => "News Cycle",
            "Niconne" => "Niconne",
            "Nixie One" => "Nixie One",
            "Nobile" => "Nobile",
            "Nokora" => "Nokora",
            "Norican" => "Norican",
            "Nosifer" => "Nosifer",
            "Nothing You Could Do" => "Nothing You Could Do",
            "Noticia Text" => "Noticia Text",
            "Noto Sans" => "Noto Sans",
            "Noto Serif" => "Noto Serif",
            "Nova Cut" => "Nova Cut",
            "Nova Flat" => "Nova Flat",
            "Nova Mono" => "Nova Mono",
            "Nova Oval" => "Nova Oval",
            "Nova Round" => "Nova Round",
            "Nova Script" => "Nova Script",
            "Nova Slim" => "Nova Slim",
            "Nova Square" => "Nova Square",
            "Numans" => "Numans",
            "Nunito" => "Nunito",
            "Odor Mean Chey" => "Odor Mean Chey",
            "Offside" => "Offside",
            "Old Standard TT" => "Old Standard TT",
            "Oldenburg" => "Oldenburg",
            "Oleo Script" => "Oleo Script",
            "Oleo Script Swash Caps" => "Oleo Script Swash Caps",
            "Open Sans" => "Open Sans",
            "Open Sans Condensed" => "Open Sans Condensed",
            "Oranienbaum" => "Oranienbaum",
            "Orbitron" => "Orbitron",
            "Oregano" => "Oregano",
            "Orienta" => "Orienta",
            "Original Surfer" => "Original Surfer",
            "Oswald" => "Oswald",
            "Over the Rainbow" => "Over the Rainbow",
            "Overlock" => "Overlock",
            "Overlock SC" => "Overlock SC",
            "Ovo" => "Ovo",
            "Oxygen" => "Oxygen",
            "Oxygen Mono" => "Oxygen Mono",
            "PT Mono" => "PT Mono",
            "PT Sans" => "PT Sans",
            "PT Sans Caption" => "PT Sans Caption",
            "PT Sans Narrow" => "PT Sans Narrow",
            "PT Serif" => "PT Serif",
            "PT Serif Caption" => "PT Serif Caption",
            "Pacifico" => "Pacifico",
            "Paprika" => "Paprika",
            "Parisienne" => "Parisienne",
            "Passero One" => "Passero One",
            "Passion One" => "Passion One",
            "Pathway Gothic One" => "Pathway Gothic One",
            "Patrick Hand" => "Patrick Hand",
            "Patrick Hand SC" => "Patrick Hand SC",
            "Patua One" => "Patua One",
            "Paytone One" => "Paytone One",
            "Peralta" => "Peralta",
            "Permanent Marker" => "Permanent Marker",
            "Petit Formal Script" => "Petit Formal Script",
            "Petrona" => "Petrona",
            "Philosopher" => "Philosopher",
            "Piedra" => "Piedra",
            "Pinyon Script" => "Pinyon Script",
            "Pirata One" => "Pirata One",
            "Plaster" => "Plaster",
            "Play" => "Play",
            "Playball" => "Playball",
            "Playfair Display" => "Playfair Display",
            "Playfair Display SC" => "Playfair Display SC",
            "Podkova" => "Podkova",
            "Poiret One" => "Poiret One",
            "Poller One" => "Poller One",
            "Poly" => "Poly",
            "Pompiere" => "Pompiere",
			"Poppins" => "Poppins",
            "Pontano Sans" => "Pontano Sans",
            "Port Lligat Sans" => "Port Lligat Sans",
            "Port Lligat Slab" => "Port Lligat Slab",
            "Prata" => "Prata",
            "Preahvihear" => "Preahvihear",
            "Press Start 2P" => "Press Start 2P",
            "Princess Sofia" => "Princess Sofia",
            "Prociono" => "Prociono",
            "Prosto One" => "Prosto One",
            "Puritan" => "Puritan",
            "Purple Purse" => "Purple Purse",
            "Quando" => "Quando",
            "Quantico" => "Quantico",
            "Quattrocento" => "Quattrocento",
            "Quattrocento Sans" => "Quattrocento Sans",
            "Questrial" => "Questrial",
            "Quicksand" => "Quicksand",
            "Quintessential" => "Quintessential",
            "Qwigley" => "Qwigley",
            "Racing Sans One" => "Racing Sans One",
            "Radley" => "Radley",
            "Raleway" => "Raleway",
            "Raleway Dots" => "Raleway Dots",
            "Rambla" => "Rambla",
            "Rammetto One" => "Rammetto One",
            "Ranchers" => "Ranchers",
            "Rancho" => "Rancho",
            "Rationale" => "Rationale",
            "Redressed" => "Redressed",
            "Reenie Beanie" => "Reenie Beanie",
            "Revalia" => "Revalia",
            "Ribeye" => "Ribeye",
            "Ribeye Marrow" => "Ribeye Marrow",
            "Righteous" => "Righteous",
            "Risque" => "Risque",
            "Roboto" => "Roboto",
            "Roboto Condensed" => "Roboto Condensed",
            "Roboto Slab" => "Roboto Slab",
            "Rochester" => "Rochester",
            "Rock Salt" => "Rock Salt",
            "Rokkitt" => "Rokkitt",
            "Romanesco" => "Romanesco",
            "Ropa Sans" => "Ropa Sans",
            "Rosario" => "Rosario",
            "Rosarivo" => "Rosarivo",
            "Rouge Script" => "Rouge Script",
            "Rubik Mono One" => "Rubik Mono One",
            "Rubik One" => "Rubik One",
            "Ruda" => "Ruda",
            "Rufina" => "Rufina",
            "Ruge Boogie" => "Ruge Boogie",
            "Ruluko" => "Ruluko",
            "Rum Raisin" => "Rum Raisin",
            "Ruslan Display" => "Ruslan Display",
            "Russo One" => "Russo One",
            "Ruthie" => "Ruthie",
            "Rye" => "Rye",
            "Sacramento" => "Sacramento",
            "Sail" => "Sail",
            "Salsa" => "Salsa",
            "Sanchez" => "Sanchez",
            "Sancreek" => "Sancreek",
            "Sansita One" => "Sansita One",
            "Sarina" => "Sarina",
            "Satisfy" => "Satisfy",
            "Scada" => "Scada",
            "Schoolbell" => "Schoolbell",
            "Seaweed Script" => "Seaweed Script",
            "Sevillana" => "Sevillana",
            "Seymour One" => "Seymour One",
            "Shadows Into Light" => "Shadows Into Light",
            "Shadows Into Light Two" => "Shadows Into Light Two",
            "Shanti" => "Shanti",
            "Share" => "Share",
            "Share Tech" => "Share Tech",
            "Share Tech Mono" => "Share Tech Mono",
            "Shojumaru" => "Shojumaru",
            "Short Stack" => "Short Stack",
            "Siemreap" => "Siemreap",
            "Sigmar One" => "Sigmar One",
            "Signika" => "Signika",
            "Signika Negative" => "Signika Negative",
            "Simonetta" => "Simonetta",
            "Sintony" => "Sintony",
            "Sirin Stencil" => "Sirin Stencil",
            "Six Caps" => "Six Caps",
            "Skranji" => "Skranji",
            "Slackey" => "Slackey",
            "Smokum" => "Smokum",
            "Smythe" => "Smythe",
            "Sniglet" => "Sniglet",
            "Snippet" => "Snippet",
            "Snowburst One" => "Snowburst One",
            "Sofadi One" => "Sofadi One",
            "Sofia" => "Sofia",
            "Sonsie One" => "Sonsie One",
            "Sorts Mill Goudy" => "Sorts Mill Goudy",
            "Source Code Pro" => "Source Code Pro",
            "Source Sans Pro" => "Source Sans Pro",
            "Special Elite" => "Special Elite",
            "Spicy Rice" => "Spicy Rice",
            "Spinnaker" => "Spinnaker",
            "Spirax" => "Spirax",
            "Squada One" => "Squada One",
            "Stalemate" => "Stalemate",
            "Stalinist One" => "Stalinist One",
            "Stardos Stencil" => "Stardos Stencil",
            "Stint Ultra Condensed" => "Stint Ultra Condensed",
            "Stint Ultra Expanded" => "Stint Ultra Expanded",
            "Stoke" => "Stoke",
            "Strait" => "Strait",
            "Sue Ellen Francisco" => "Sue Ellen Francisco",
            "Sunshiney" => "Sunshiney",
            "Supermercado One" => "Supermercado One",
            "Suwannaphum" => "Suwannaphum",
            "Swanky and Moo Moo" => "Swanky and Moo Moo",
            "Syncopate" => "Syncopate",
            "Tangerine" => "Tangerine",
            "Taprom" => "Taprom",
            "Tauri" => "Tauri",
            "Telex" => "Telex",
            "Tenor Sans" => "Tenor Sans",
            "Text Me One" => "Text Me One",
            "The Girl Next Door" => "The Girl Next Door",
            "Tienne" => "Tienne",
            "Tinos" => "Tinos",
            "Titan One" => "Titan One",
            "Titillium Web" => "Titillium Web",
            "Trade Winds" => "Trade Winds",
            "Trocchi" => "Trocchi",
            "Trochut" => "Trochut",
            "Trykker" => "Trykker",
            "Tulpen One" => "Tulpen One",
            "Ubuntu" => "Ubuntu",
            "Ubuntu Condensed" => "Ubuntu Condensed",
            "Ubuntu Mono" => "Ubuntu Mono",
            "Ultra" => "Ultra",
            "Uncial Antiqua" => "Uncial Antiqua",
            "Underdog" => "Underdog",
            "Unica One" => "Unica One",
            "UnifrakturCook" => "UnifrakturCook",
            "UnifrakturMaguntia" => "UnifrakturMaguntia",
            "Unkempt" => "Unkempt",
            "Unlock" => "Unlock",
            "Unna" => "Unna",
            "VT323" => "VT323",
            "Vampiro One" => "Vampiro One",
            "Varela" => "Varela",
            "Varela Round" => "Varela Round",
            "Vast Shadow" => "Vast Shadow",
            "Vibur" => "Vibur",
            "Vidaloka" => "Vidaloka",
            "Viga" => "Viga",
            "Voces" => "Voces",
            "Volkhov" => "Volkhov",
            "Vollkorn" => "Vollkorn",
            "Voltaire" => "Voltaire",
            "Waiting for the Sunrise" => "Waiting for the Sunrise",
            "Wallpoet" => "Wallpoet",
            "Walter Turncoat" => "Walter Turncoat",
            "Warnes" => "Warnes",
            "Wellfleet" => "Wellfleet",
            "Wendy One" => "Wendy One",
            "Wire One" => "Wire One",
            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
            "Yellowtail" => "Yellowtail",
            "Yeseva One" => "Yeseva One",
            "Yesteryear" => "Yesteryear",
            "Zeyada" => "Zeyada",
        );

        if($flip){
            return array_flip($fonts);
        }
        else{
            return $fonts;
        }
    }
}

if(!function_exists('inwave_get_fonts_weight')){
    function inwave_get_fonts_weight(){
        return array(
            "Default" => "",
            "Extra Bold" => "900",
            "Bold" => "700",
            "Semi-Bold" => "600",
            "Medium" => "500",
            "Normal" => "400",
            "Light" => "300",
            "Thin" => "100"
        );
    }
}

if(!function_exists('inwave_get_text_transform')){
    function inwave_get_text_transform(){
        return array(
            "Default" => "",
            "Capitalize" => "capitalize",
            "Uppercase" => "uppercase",
            "None" => "none"
        );
    }
}

if(!function_exists('inwave_get_font_style')){
    function inwave_get_font_style(){
        return array(
            "Default" => "normal",
            "Italic" => "italic",
            "Oblique" => "oblique",
            "Initial" => "initial",
            "Inherit" => "inherit"
        );
    }
}


if(!function_exists('inwave_start_session')){
    add_action('init', 'inwave_start_session', 1);
    /**  Init session for theme  */
    function inwave_start_session()
    {
        global $smof_data;
        if (!session_id()  && !headers_sent()) {
            session_start();
        }
        if (!isset($_SESSION['product-category-layout']) || !$_SESSION['product-category-layout']) {
            $_SESSION['product-category-layout'] =  isset($smof_data['product_listing_layout'])?$smof_data['product_listing_layout']:'';
            if($_SESSION['product-category-layout']==''){
                $_SESSION['product-category-layout'] == 'col';
            }
        }
        if (isset($_REQUEST['category-layout']) && $_REQUEST['category-layout']) {
            $_SESSION['product-category-layout'] = $_REQUEST['category-layout'];
        }
    }
}

if(!function_exists('inwave_add_editor_styles')){
    function inwave_add_editor_styles() {
        add_editor_style();
    }
    add_action( 'admin_init', 'inwave_add_editor_styles' );
}

if(!function_exists('inwave_resize')) {
    function inwave_resize($url, $width, $height = null, $crop = null, $single = true)
    {
        //validate inputs
        if (!$url OR !$width) return false;

        //define upload path & dir
        $upload_info = wp_upload_dir();
        $upload_dir = $upload_info['basedir'];
        $upload_url = $upload_info['baseurl'];
        //check if $img_url is local
        if (strpos($url, $upload_url) === false){
            //define path of image
            $rel_path = str_replace(content_url(), '', $url);
            $img_path = WP_CONTENT_DIR  . $rel_path;
        }
        else
        {
            $rel_path = str_replace($upload_url, '', $url);
            $img_path = $upload_dir . $rel_path;
        }

        //check if img path exists, and is an image indeed
        if (!file_exists($img_path) OR !getimagesize($img_path)) return $url;

        //get image info
        $info = pathinfo($img_path);
        $ext = $info['extension'];
        list($orig_w, $orig_h) = getimagesize($img_path);

        //get image size after cropping
        $dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
        $dst_w = $dims[4];
        $dst_h = $dims[5];

        //use this to check if cropped image already exists, so we can return that instead
        $suffix = "{$dst_w}x{$dst_h}";
        $dst_rel_url = str_replace('.' . $ext, '', $url);
        $destfilename = "{$img_path}-{$suffix}.{$ext}";
        if (!$dst_h) {
            //can't resize, so return original url
            $img_url = $url;
            $dst_w = $orig_w;
            $dst_h = $orig_h;
        } //else check if cache exists
        elseif (file_exists($destfilename) && getimagesize($destfilename)) {
            $img_url = "{$dst_rel_url}-{$suffix}.{$ext}";
        } //else, we resize the image and return the new resized image url
        else {
            // Note: This pre-3.5 fallback check will edited out in subsequent version
            if (function_exists('wp_get_image_editor')) {

                $editor = wp_get_image_editor($img_path);

                if (is_wp_error($editor) || is_wp_error($editor->resize($width, $height, $crop)))
                    return false;

                $resized_file = $editor->save();

                if (!is_wp_error($resized_file)) {
                    $resized_rel_path = str_replace($upload_dir, '', $resized_file['path']);
                    $img_url = "{$dst_rel_url}-{$suffix}.{$ext}";
                } else {
                    return false;
                }

            }
        }

        //return the output
        if ($single) {
            //str return
            $image = $img_url;
        } else {
            //array return
            $image = array(
                0 => $img_url,
                1 => $dst_w,
                2 => $dst_h
            );
        }

        return $image;
    }
}

if(!function_exists('inwave_get_title_string')){
    function  inwave_get_title_string($title){
        $output = '';
        $title = explode('.', $title);
        $new_title = '';
        if(count($title) > 1){
            $new_title .= '<span>'.$title[0].'.</span>';
            unset($title[0]);
        }
        $new_title .= implode('.', $title);
        $output .= '<h3>'.$new_title.'</h3>';

        return $output;
    }
}

if(!function_exists('inwave_display_pagination_none')){
    function inwave_display_pagination_none($query = '') {
        $rs = array('success'=>false, 'data'=>'');
        if (!$query) {
            global $wp_query;
            $query = $wp_query;
        }

        $paginate_links = paginate_links(array(
            'format' => '?page=%#%',
            'prev_next' => false,
            'current' => max(1, get_query_var('paged')),
            'show_all'=>true,
            'total' => $query->max_num_pages
        ));
        // Display the pagination if more than one page is found
        if ($paginate_links) :
            $html = array();
            $html[] = '<div class="post-pagination clearfix hide">';
            $html[] = $paginate_links;
            $html[] = '</div>';
            $rs['success'] = true;
            $rs['data'] = implode($html);
        endif;

        return $rs;
    }
}

/* Convert hexdec color string to rgb(a) string */
if ( ! function_exists( 'inwave_hex2rgba' ) ) {
	function inwave_hex2rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if ( empty( $color ) ) {
			return $default;
		}

		//Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb = array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		//Return rgb(a) color string
		return $output;
	}
}

if(!function_exists('inwave_get_placeholder_image')){
    function inwave_get_placeholder_image(){
        return get_template_directory_uri().'/assets/images/default-placeholder.png';
    }
}

if(!function_exists('inwave_mmmenu_is_active')){
    function inwave_mmmenu_is_active(){
        return class_exists('MmenuAdminPage');
    }
}
add_action( 'delete_user', 'reload_count' );
function reload_count(){
    delete_transient('iwj_count_candidates');
    delete_transient('iwj_count_employers');
}