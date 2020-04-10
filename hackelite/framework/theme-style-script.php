<?php
/**
 * This file is used to load javascript and stylesheet function
 */
if(!function_exists( 'inwave_fonts_url' )) {
    function inwave_fonts_url()
    {
        $font_families = array();
        $font_group = array();
        $f_body = Inwave_Helper::getThemeOption('f_body');
        $f_body_settings = Inwave_Helper::getThemeOption('f_body_settings');
        if ($f_body) {
            $font_group[] = array($f_body=> $f_body_settings);
        }

        $f_nav = Inwave_Helper::getThemeOption('f_nav');
        $f_nav_settings = Inwave_Helper::getThemeOption('f_nav_settings');
        if ($f_nav) {
            $font_group[] = array($f_nav=> $f_nav_settings);
        }

        $f_headings = Inwave_Helper::getThemeOption('f_headings');
        $f_headings_settings = Inwave_Helper::getThemeOption('f_headings_settings');
        if ($f_headings) {
            $font_group[] = array($f_headings=> $f_headings_settings);
        }

        //theme special fonts
        $font_group[] = array('Playfair Display'=>'400,400i,700,700i,900,900i');

        if($font_group){
            $new_fonts = array();
            foreach ($font_group as $font){
                foreach ($font as $font_name => $font_settings){
                    if(isset($new_fonts[$font_name])){
                        if($new_fonts[$font_name] && $font_settings){
                            $new_font_settings = array_merge(explode(',', $new_fonts[$font_name] ), explode(',', $font_settings));
                            $new_font_settings = array_map('trim', $new_font_settings);
                            $new_font_settings = array_unique($new_font_settings);
                            $new_fonts[$font_name] = implode(',', $new_font_settings);
                        }elseif($font_settings){
                            $new_fonts[$font_name] = $font_settings;
                        }
                    }else{
                        $new_fonts[$font_name] = $font_settings;
                    }
                }
            }

            if($new_fonts){
                foreach ($new_fonts as $font_name => $font_settings){
                    $font_families[] = $font_name . ':' . $font_settings;
                }
            }
        }

        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'subset' => urlencode('latin,latin-ext'),
            'display'=>'swap'
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');

        return $fonts_url;
    }

}

/**
 * Enqueue scripts and styles.
 */
if( !function_exists( 'inwave_scripts' ) ) {
    function inwave_scripts()
    {
        $theme_info = wp_get_theme();
        $template = get_template();

        /* Load css*/
        wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/css/font-awesome.min.css', array());
        wp_enqueue_style('font-ionicons', get_template_directory_uri() . '/assets/fonts/ionicons/ionicons.min.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('font-iwj', get_template_directory_uri() . '/assets/fonts/iwj/css/fontello.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('font-icomoon', get_template_directory_uri() . '/assets/fonts/icomoon/style.css', array(), $theme_info->get('Version'));
        wp_enqueue_style($template.'-fonts', inwave_fonts_url(), array(), $theme_info->get('Version'));
        wp_enqueue_style('select2', get_template_directory_uri() . '/assets/css/select2.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/assets/css/owl.carousel.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('owl-theme', get_template_directory_uri() . '/assets/css/owl.theme.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('owl-transitions', get_template_directory_uri() . '/assets/css/owl.transitions.css', array(), $theme_info->get('Version'));
        wp_register_style('jquery-fancybox', get_template_directory_uri() . '/assets/fancybox/jquery.fancybox.css', array(), $theme_info->get('Version'));

        // Don't load css3 effect in mobile device
        if (!wp_is_mobile()) {
            if (!(isset($_REQUEST['vc_editable']) && $_REQUEST['vc_editable'])) {
                wp_enqueue_style('animation', get_template_directory_uri() . '/assets/css/animation.css', array(), $theme_info->get('Version'));
            }
        }
        /** Theme style */
        if(is_child_theme()){
            wp_enqueue_style( $template.'-parent-style', get_template_directory_uri(). '/style.css' );
            if(is_rtl()){
                wp_enqueue_style( $template.'-parent-rtl-style', get_template_directory_uri(). '/rtl.css' );
            }
        }
        wp_enqueue_style($template.'-style', get_stylesheet_uri());

        /** custom css */
        wp_enqueue_style($template.'-primary-color', Inwave_Customizer::getColorFileUrl(), array(), $theme_info->get('Version'));
	    wp_enqueue_style($template.'-customize', Inwave_Customizer::getCustomizeFileUrl(), array(), $theme_info->get('Version'));

        /* Load js*/
        wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), $theme_info->get('Version'), true);
        wp_register_script('mailchimp', get_template_directory_uri() . '/assets/js/mailchimp.js', array('jquery'), $theme_info->get('Version'), true);
        wp_enqueue_script('select2', get_template_directory_uri() . '/assets/js/select2.full.min.js', array(), $theme_info->get('Version'), true);
        wp_enqueue_script('jquery-fitvids', get_template_directory_uri() . '/assets/js/jquery.fitvids.js', array(), $theme_info->get('Version'), true);
        wp_register_script('jquery-fancybox', get_template_directory_uri() . '/assets/fancybox/jquery.fancybox.js', array(), $theme_info->get('Version'), true);
        wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $theme_info->get('Version'), true);
        wp_enqueue_script('theia-sticky', get_template_directory_uri() . '/assets/js/theia-sticky-sidebar.js', array(), $theme_info->get('Version'), true);

        wp_enqueue_script('jquery-en-scroll', get_template_directory_uri() . '/assets/js/jquery.enscroll.min.js', array(), $theme_info->get('Version'), true);
        wp_register_script('jquery-countTo', get_template_directory_uri() . '/assets/js/jquery.countTo.js', array(), $theme_info->get('Version'), false);
        wp_register_script('jquery-parallax', get_template_directory_uri() . '/assets/js/jquery.parallax-1.1.3.js', array(), $theme_info->get('Version'), true);
        wp_enqueue_script('jquery-single-page-nav', get_template_directory_uri() . '/assets/js/jquery.singlePageNav.js', array(), $theme_info->get('Version'), true);
        wp_enqueue_script($template.'-template', get_template_directory_uri() . '/assets/js/template.js', array(), $theme_info->get('Version'), true);
        wp_localize_script($template.'-template', 'inwaveCfg', array('siteUrl' => admin_url('/'), 'themeUrl' => get_template_directory_uri(), 'baseUrl' => site_url(), 'ajaxUrl' => admin_url('admin-ajax.php')));
        if(Inwave_Helper::getThemeOption('retina_support')) {
            wp_enqueue_script('retina_js', get_template_directory_uri() . '/assets/js/retina.min.js', array(), $theme_info->get('Version'), true);
        }

        /** load panel */
        if (Inwave_Helper::getThemeOption('show_setting_panel')) {
            wp_enqueue_script("jquery-effects-core");
            wp_enqueue_script($template.'-panel-settings', get_template_directory_uri() . '/assets/js/panel-settings.js', array(), $theme_info->get('Version'), true);

            wp_localize_script($template.'-panel-settings', 'inwavePanelSettings' , array(
                'theme' => get_template(),
                'color' => INWAVE_MAIN_COLOR,
                'default_settings' => array(
                    'mainColor' => Inwave_Helper::getThemeOption('primary_color'),
                    'layout' => Inwave_Helper::getThemeOption('body_layout'),
                    'bgColor' => Inwave_Helper::getThemeOption('bg_color'),
                )
            ));
        }

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        if(!inwave_mmmenu_is_active()){
            wp_enqueue_script('jquery-navgoco', get_template_directory_uri() . '/assets/js/jquery.navgoco.js', array(), $theme_info->get('Version'), true);
            wp_enqueue_script($template.'-off-canvas', get_template_directory_uri() . '/assets/js/off-canvas.js', array(), $theme_info->get('Version'), true);
        }
    }

    add_action('wp_enqueue_scripts', 'inwave_scripts');
}


/**
 * Clear WooCommerce styles and scripts at other pages.
 */
function inwave_woocommerce_script_cleaner() {
    if(function_exists('is_woocommerce')){
        if(!Inwave_Helper::getThemeOption('enqueue_woocommerce_all_page')){

            // Unless we're in the store, remove all the cruft!
            if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page()) {
                // Remove the generator tag
                remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

                wp_dequeue_style( 'woocommerce_frontend_styles' );
                wp_dequeue_style( 'woocommerce-general');
                wp_dequeue_style( 'woocommerce-layout' );
                wp_dequeue_style( 'woocommerce-smallscreen' );
                wp_dequeue_style( 'woocommerce_fancybox_styles' );
                wp_dequeue_style( 'woocommerce_chosen_styles' );
                wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
                wp_dequeue_script( 'selectWoo' );
                wp_dequeue_script( 'wc-add-payment-method' );
                wp_dequeue_script( 'wc-lost-password' );
                wp_dequeue_script( 'wc_price_slider' );
                wp_dequeue_script( 'wc-single-product' );
                wp_dequeue_script( 'wc-add-to-cart' );
                wp_dequeue_script( 'wc-cart-fragments' );
                wp_dequeue_script( 'wc-credit-card-form' );
                wp_dequeue_script( 'wc-checkout' );
                wp_dequeue_script( 'wc-add-to-cart-variation' );
                wp_dequeue_script( 'wc-single-product' );
                wp_dequeue_script( 'wc-cart' );
                wp_dequeue_script( 'wc-chosen' );
                wp_dequeue_script( 'woocommerce' );
                wp_dequeue_script( 'prettyPhoto' );
                wp_dequeue_script( 'prettyPhoto-init' );
                wp_dequeue_script( 'jquery-blockui' );
                wp_dequeue_script( 'jquery-placeholder' );
                wp_dequeue_script( 'jquery-payment' );
                wp_dequeue_script( 'fancybox' );
                wp_dequeue_script( 'jqueryui' );
                wp_dequeue_script( 'vc_woocommerce-add-to-cart-js' );
                wp_dequeue_script( 'vc_woocommerce-add-to-cart-js' );

                wp_dequeue_style( 'jquery-selectBox' );
                wp_dequeue_style( 'yith-wcwl-main' );
                wp_dequeue_style( 'yith-wcwl-font-awesome' );
                wp_dequeue_script( 'jquery-selectBox' );
                wp_dequeue_script( 'jquery-yith-wcwl' );


            }else{
                wp_enqueue_style('woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), false);
            }
        }else{
            wp_enqueue_style('woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), false);
        }
    }
}
add_action( 'wp_enqueue_scripts', 'inwave_woocommerce_script_cleaner', 99 );

/**
 * Admin Enqueue scripts and styles.
 */
if( !function_exists( 'inwave_admin_scripts' ) ) {
    function inwave_admin_scripts()
    {
        $theme_info = wp_get_theme();
        $template = get_template();

        /*Load css*/
        wp_enqueue_style($template.'-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), $theme_info->get('Version'));
        wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/css/font-awesome.min.css', array(), $theme_info->get('Version'));

        /*Load js*/
        wp_enqueue_media();
        wp_enqueue_script($template.'-admin', get_template_directory_uri() . '/assets/js/admin-template.js', array(), $theme_info->get('Version'), true);
    }

    add_action('admin_enqueue_scripts', 'inwave_admin_scripts');
}

/**
 * Load theme custom css
 */
if(!function_exists('inwave_custom_css')){

    /*
     * get css custom
    */
    function inwave_custom_css()
    {

        $css = array();

        //content bg
        $bg_color_page = Inwave_Helper::getPostOption('background_color_page', 'content_bg_color');
        if($bg_color_page){
            $css[] = 'body .wrapper{background-color:'.$bg_color_page.'!important}';
        }
        
        //footer bg
        $footer_bg_color = Inwave_Helper::getPostOption('footer_bg_color', 'footer_bg_color');
        if($footer_bg_color){
           $css[] = '.iw-footer.iw-footer-default .iw-footer-middle {background:'.$footer_bg_color.'!important}';
        }

        $bg_page_heading = Inwave_Helper::getPostOption('pageheading_bg', 'page_title_bg');
        if($bg_page_heading){
           $css[] = '.page-heading{background-image:url('.$bg_page_heading.')!important;}';
        }
        $page_title_bg_color = Inwave_Helper::getPostOption('page_title_bg_color', 'page_title_bg_color');
        if($page_title_bg_color) {
            $css[] = '.page-heading{background-color:'.$page_title_bg_color.' !important;}';
        }

	    $search_page_heading_bg_color = Inwave_Helper::getPostOption('search_page_heading_bg_color', 'search_page_heading_bg_color');
	    $search_page_heading_bg_color_opacity = Inwave_Helper::getPostOption('search_page_heading_bg_color_opacity', 'search_page_heading_bg_color_opacity');
	    if($search_page_heading_bg_color) {
	    	$__search_form_heading = inwave_hex2rgba($search_page_heading_bg_color,$search_page_heading_bg_color_opacity);
		    $css[] = '.page-heading-search-2 {background-color:'.$__search_form_heading.' !important;}';
	    }

        $show_page_heading = Inwave_Helper::getPostOption('show_pageheading', 'show_page_heading');

        $bg_header = Inwave_Helper::getThemeOption('bg_header');
        if($bg_header){
	        if(!is_page_template( 'page-templates/home-page.php' ) && !is_singular('post') && $show_page_heading && $show_page_heading == 'no') {
		        $css[] = '.header.header-default{background-image:url('.$bg_header.')!important; background-size: cover; background-repeat: no-repeat;}';
	        }

            $css[] = '.header.header-default .iw-header{background-image:url('.$bg_header.')!important;';
            $css[] = 'background-size: cover;';
            $css[] = 'background-repeat: no-repeat;';
            $css[] = '}';
        }

        $bg_header_color  = Inwave_Helper::getPostOption( 'bg_header_color', 'bg_header_color' );
        if($bg_header_color) {
            $css[] = '.header.header-default .iw-header:after {background-color:'.$bg_header_color.' !important;}';
        }
        $opacity_bg_header_color  = Inwave_Helper::getPostOption( 'opacity_bg_header_color', 'opacity_bg_header_color' );
        if($opacity_bg_header_color) {
            $css[] = '.header.header-default .iw-header:after{opacity:'.$opacity_bg_header_color.' !important;}';
        }

        $bg_top_bar_header_color  = Inwave_Helper::getPostOption( 'bg_top_bar_header_color', 'bg_top_bar_header_color' );
        if($bg_top_bar_header_color) {
            $css[] = '.header.header-default .iw-top-bar-wrapper:after {background-color:'.$bg_top_bar_header_color.' !important;}';
        }
        $opacity_bg_top_bar_header_color  = Inwave_Helper::getPostOption( 'opacity_bg_top_bar_header_color', 'opacity_bg_top_bar_header_color' );
        if($opacity_bg_top_bar_header_color) {
            $css[] = '.header.header-default .iw-top-bar-wrapper:after {opacity:'.$opacity_bg_top_bar_header_color.' !important;}';
        }

        $bg_header_sticky_color  = Inwave_Helper::getPostOption( 'bg_header_sticky_color', 'bg_header_sticky_color' );
        if($bg_header_sticky_color){
            $css[] = '.header.header-default.header-sticky.clone .iw-header{background-color: '.$bg_header_sticky_color.' !important;}';
        }

	    $page_heading_padding_top  = Inwave_Helper::getPostOption( 'page_heading_padding_top', 'page_heading_padding_top' );
	    if($page_heading_padding_top){
		    $css[] ='.page-heading .container-inner{padding-top:'.$page_heading_padding_top.'}';
	    }

	    $page_heading_padding_bottom  = Inwave_Helper::getPostOption( 'page_heading_padding_bottom', 'page_heading_padding_bottom' );
	    if($page_heading_padding_bottom){
		    $css[] ='.page-heading .container-inner{padding-bottom:'.$page_heading_padding_bottom.'}';
	    }

        //footer widget
        $footer_bg_image = Inwave_Helper::getPostOption('footer-background', 'footer_bg_image');
        if($footer_bg_image){
           $css[] = '.iw-footer.iw-footer-default .iw-footer-middle{background-image:url('.$footer_bg_image.')!important;}';
        }

        //copy right
        $copyright_bg_color = Inwave_Helper::getPostOption('copyright_bg_color', 'copyright_bg_color');
        if($copyright_bg_color){
            $css[] = 'body .iw-copy-right{background-color:'.$copyright_bg_color.'!important;}';
        }

        // Background for Body page
        $bgColor = Inwave_Helper::getPanelSetting('bgColor');
        if ($bgColor) {
            if (strpos($bgColor, '#') === 0) {
                $css[] = 'body.page{background:' . $bgColor . '}'."\n";
            } else {
                $css[] = 'body.page{background:url(' . $bgColor . ')}'."\n";
            }
        }

        $template = get_template();
        wp_add_inline_style( $template.'-style', implode('', $css) );
    }

    add_action('wp_enqueue_scripts', 'inwave_custom_css');
}

/**
 * Load theme custom file css
 */
if(!function_exists('inwave_custom_file_css')){

	/*
	 * get css for custom file
	*/
	function inwave_custom_file_css() {
		$css    = array();
		$f_body = Inwave_Helper::getThemeOption( 'f_body' );
		if ( $f_body ) {
			$css[] = 'html body{font-family:' . $f_body . '}';
		}

		$f_nav = Inwave_Helper::getThemeOption( 'f_nav' );
		if ( $f_nav ) {
			$css[] = '.iw-nav-menu, .iwj-candicate-detail-v2 .candidate-detail-menu ul.menu li a{font-family:' . $f_nav . '}';
		}

		$f_headings = Inwave_Helper::getThemeOption( 'f_headings' );
		if ( $f_headings ) {
			$css[] = 'h1,h2,h3,h4,h5,h6{font-family:' . $f_headings . '}';
		}

		$f_size = Inwave_Helper::getThemeOption( 'f_size' );
		if ( $f_size ) {
			$css[] = 'html body{font-size:' . $f_size . '}';
		}

		$f_lineheight = Inwave_Helper::getThemeOption( 'f_lineheight' );
		if ( $f_lineheight ) {
			$css[] = 'html body{line-height:' . $f_lineheight . '}';
		}

		//body background
		if ( Inwave_Helper::getThemeOption( 'body_layout' ) ) {
			if ( Inwave_Helper::getThemeOption( 'bg_color' ) ) {
				if ( Inwave_Helper::getThemeOption( 'bg_image' ) ) {
					$css[] = 'body{background-color:' . Inwave_Helper::getThemeOption( 'bg_color' ) . ' !important; }';
				} else {
					$css[] = 'body{background:' . Inwave_Helper::getThemeOption( 'bg_color' ) . '; !important}';
				}
			}

			if ( Inwave_Helper::getThemeOption( 'bg_image' ) ) {
				$css[] = 'body{background-image:url(' . Inwave_Helper::getThemeOption( 'bg_image' ) . ');';
				if ( Inwave_Helper::getThemeOption( 'bg_size' ) ) {
					$css[] = 'background-size: ' . Inwave_Helper::getThemeOption( 'bg_size' ) . ';';
				}
				if ( Inwave_Helper::getThemeOption( 'bg_repeat' ) ) {
					$css[] = 'background-repeat:' . Inwave_Helper::getThemeOption( 'bg_repeat' ) . ';';
				}
				$css[] = '}';
			}
		}

		//header color
		if(Inwave_Helper::getThemeOption('top_bar_color')){
			$css[] = 'body .top-bar-left, body .top-bar-left .contact > span{color:'.Inwave_Helper::getThemeOption('top_bar_color').'!important}';
		}
		if(Inwave_Helper::getThemeOption('header_link_color')){
			$css[] = '.header-default .iw-main-menu .iw-nav-menu li a, .header .iw-main-menu .iw-nav-menu li a strong, .header-style-v2 .iwj-author-desktop .author-name span,
           .header.header-default.header-style-v2 .iw-header .notification a i {color:'.Inwave_Helper::getThemeOption('header_link_color').'!important}';
		}
		if(Inwave_Helper::getThemeOption('header_link_hover_color')){
			$css[] = '.header-default .iw-main-menu .iw-nav-menu li a:hover,.header-default .iw-main-menu .iw-nav-menu li a:active,.header-default .iw-main-menu .iw-nav-menu li a:focus,
           .header-default .iw-main-menu .iw-nav-menu li.active a,
            .header .iw-main-menu .iw-nav-menu li a:hover strong{color:'.Inwave_Helper::getThemeOption('header_link_hover_color').'!important}';
			$css[] = 'body .header.header-default .navbar-default .navbar-nav > li.selected.active > a:after, body .header.header-default .navbar-default .navbar-nav > li[class*="current-menu"] > a:after, body .header.header-default .navbar-default .navbar-nav > li > a:hover:after{background: '.Inwave_Helper::getThemeOption('header_link_hover_color').'}';
		}
		$header_sticky_link_color = Inwave_Helper::getThemeOption('header_sticky_link_color');
		if ($header_sticky_link_color) {
			$css[] = '.header-default.clone .iw-main-menu .iw-nav-menu li a {color:'.$header_sticky_link_color.'}';
		}
		if(Inwave_Helper::getThemeOption('header_sub_link_color')){
			$css[] = '.header-default .iw-main-menu .iw-nav-menu .sub-menu li a, .header .iw-header .iw-menu-main .navbar-nav .sub-menu li a{color:'.Inwave_Helper::getThemeOption('header_sub_link_color').'!important}';
		}
		if(Inwave_Helper::getThemeOption('header_sub_link_hover_color')){
			$css[] = '.header-default .iw-main-menu .iw-nav-menu .sub-menu li a:hover, .header .iw-header .iw-menu-main .navbar-nav .sub-menu li a:hover{color:'.Inwave_Helper::getThemeOption('header_sub_link_hover_color').'!important}';
		}
		if(Inwave_Helper::getThemeOption('header_sub_bg_color')){
			$css[] = 'body .header .navbar-nav li .sub-menu{background-color:'.Inwave_Helper::getThemeOption('header_sub_bg_color').'}';
		}
		if(Inwave_Helper::getThemeOption('header_sub_bd_color')){
			$css[] = 'body .header .navbar-nav li .sub-menu{boder-color:'.Inwave_Helper::getThemeOption('header_sub_bd_color').'}';
		}

		if(Inwave_Helper::getThemeOption('footer_border_color')){
			$css[] = '.iw-footer-middle, .iw-footer-default .footer-widget-contact p, .iw-footer.iw-footer-default .widget li, .iw-footer.iw-footer-default{border-color:'.Inwave_Helper::getThemeOption('footer_border_color').'!important}';
		}

		//body color
		if(Inwave_Helper::getThemeOption('body_text_color')){
			$css[] = 'html body{color:'.Inwave_Helper::getThemeOption('body_text_color').'}';
		}

		if(Inwave_Helper::getThemeOption('link_color')){
			$css[] = 'body a{color:'.Inwave_Helper::getThemeOption('link_color').'}';
		}
		if(Inwave_Helper::getThemeOption('link_hover_color')){
			$css[] = 'body a:hover{color:'.Inwave_Helper::getThemeOption('link_hover_color').'}';
		}

		if(Inwave_Helper::getThemeOption('page_title_color')){
			$css[] = 'body .page-title, body .page-title h1{color:'.Inwave_Helper::getThemeOption('page_title_color').'}';
		}
		if(Inwave_Helper::getThemeOption('page_title_bg_color')){
			$css[] = 'body .page-heading .container-inner{background-color:'.Inwave_Helper::getThemeOption('page_title_bg_color').'}';
		}
		if(Inwave_Helper::getThemeOption('breadcrumbs_bg_color')){
			$css[] = 'body .page-heading .breadcrumbs-top{background-color:'.Inwave_Helper::getThemeOption('breadcrumbs_bg_color').' !important}';
		}
		if(Inwave_Helper::getThemeOption('breadcrumbs_text_color')){
			$css[] = 'body .breadcrumbs, body .page-heading .breadcrumbs-top .current{color:'.Inwave_Helper::getThemeOption('breadcrumbs_text_color').' !important}';
		}
		if(Inwave_Helper::getThemeOption('breadcrumbs_link_color')){
			$css[] = 'body .breadcrumbs > li > a{color:'.Inwave_Helper::getThemeOption('breadcrumbs_link_color').' !important}';
		}
		if(Inwave_Helper::getThemeOption('breadcrumbs_link_hover_color')){
			$css[] = 'body .breadcrumbs > li > a:hover{color:'.Inwave_Helper::getThemeOption('breadcrumbs_link_hover_color').' !important}';
		}
		if(Inwave_Helper::getThemeOption('blockquote_color')){
			$css[] = '.contents-main blockquote{color:'.Inwave_Helper::getThemeOption('blockquote_color').'}';
		}
		if(Inwave_Helper::getThemeOption('footer_headings_color')){
			$css[] = '.iw-footer-default .iw-footer-middle .widget-title{color:'.Inwave_Helper::getThemeOption('footer_headings_color').' !important}';
		}
		if(Inwave_Helper::getThemeOption('footer_text_color')){
			$css[] = '.iw-footer-default .iw-footer-middle, .iw-footer-middle .widget_nav_menu .menu li a, .iw-footer-default .widget_inwave-subscribe .malchimp-desc, .iw-footer-middle .widget_inwave-subscribe .iw-email-notifications h5, body .iw-copy-right p{color:'.Inwave_Helper::getThemeOption('footer_text_color').'}';
		}
		if(Inwave_Helper::getThemeOption('footer_link_color')){
			$css[] = 'body .iw-footer-middle a, body .iw-footer-middle .widget li a, body .iw-footer-middle .widget_nav_menu .menu li a{color:'.Inwave_Helper::getThemeOption('footer_link_color').'}';
		}
		if(Inwave_Helper::getThemeOption('footer_link_hover_color')){
			$css[] = 'body .iw-footer-middle a:hover, body .iw-footer-middle .widget li a:hover, body .iw-footer-middle .widget_nav_menu .menu li a:hover{color:'.Inwave_Helper::getThemeOption('footer_link_hover_color').' !important; }';
		}
		if(Inwave_Helper::getThemeOption('footer_link_border_color')){
			$css[] = '.iw-footer .widget_nav_menu .menu li a, .iw-footer .widget-info-footer .iw-social-footer-all{border-color:'.Inwave_Helper::getThemeOption('footer_link_border_color').'}';
		}

		if(Inwave_Helper::getThemeOption('page_title_bg_size')){
			$css[] = '.page-heading{ background-size: '.Inwave_Helper::getThemeOption('page_title_bg_size').';}';
		}
		if(Inwave_Helper::getThemeOption('page_title_bg_repeat')){
			$css[] = '.page-heading{ background-repeat:'.Inwave_Helper::getThemeOption('page_title_bg_repeat').';}';
		}

		$bg_header_sticky = Inwave_Helper::getThemeOption('bg_header_sticky');
		if($bg_header_sticky){
			$css[] = '.header.header-default.header-sticky.clone .navbar-default.iw-header, .header.header-default.v3.clone .iw-header{background-image:url('.$bg_header_sticky.')!important;';
			$css[] = 'background-size: cover;';
			$css[] = 'background-repeat: no-repeat;';
			$css[] = '}';
		}
		if(Inwave_Helper::getThemeOption('footer_bg_size')){
			$css[] = '.iw-footer.iw-footer-default .iw-footer-middle{ background-size: '.Inwave_Helper::getThemeOption('footer_bg_size').';}';
		}
		if(Inwave_Helper::getThemeOption('footer_bg_repeat')){
			$css[] = '.iw-footer.iw-footer-default .iw-footer-middle{ background-repeat:'.Inwave_Helper::getThemeOption('page_title_bg_repeat').';}';
		}

		//copyright
		if(Inwave_Helper::getThemeOption('copyright_text_color')){
			$css[] = 'body .iw-footer .iw-copy-right p{color:'.Inwave_Helper::getThemeOption('copyright_text_color').'}';
		}
		if(Inwave_Helper::getThemeOption('copyright_link_color')){
			$css[] = '.iw-footer.iw-footer-default .iw-copy-right a{color:'.Inwave_Helper::getThemeOption('copyright_link_color').'}';
		}
		if(Inwave_Helper::getThemeOption('copyright_link_hover_color')){
			$css[] = '.iw-footer.iw-footer-default .iw-copy-right a:hover{color:'.Inwave_Helper::getThemeOption('copyright_link_hover_color').'}';
		}

		return implode("\n", $css);
	}
}