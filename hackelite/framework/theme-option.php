<?php

add_action('init', 'inwave_of_options');

if (!function_exists('inwave_of_options')) {
    function inwave_of_options()
    {
        global $wp_registered_sidebars;
        $sidebar_options[] = esc_html__('None','injob');
         $sidebars = $wp_registered_sidebars;
        if (is_array($sidebars) && !empty($sidebars)) {
            foreach ($sidebars as $sidebar) {
                $sidebar_options[] = $sidebar['name'];
            }
        }

        //get slug menu in admin
        $menuArr = array();
        $menus = get_terms('nav_menu');
        foreach ( $menus as $menu ) {
            $menuArr[$menu->slug] = $menu->name;
        }

        //Access the WordPress Categories via an Array
        $of_categories = array();
        $of_categories_obj = get_categories('hide_empty=0');
        foreach ($of_categories_obj as $of_cat) {
            $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;
        }

        //Access the WordPress Pages via an Array
        $of_pages = array();
        $of_pages_obj = get_pages('sort_column=post_parent,menu_order');
        foreach ($of_pages_obj as $of_page) {
            $of_pages[$of_page->ID] = $of_page->post_name;
        }

        /*-----------------------------------------------------------------------------------*/
        /* TO DO: Add options/functions that use these */
        /*-----------------------------------------------------------------------------------*/
        $google_fonts = inwave_get_googlefonts(false);

        /*-----------------------------------------------------------------------------------*/
        /* The Options Array */
        /*-----------------------------------------------------------------------------------*/

        // Set the Options Array
        global $inwave_of_options;
        $inwave_of_options = array();

        $sideBars = array();
        foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
            $sideBars[$sidebar['id']] = ucwords( $sidebar['name'] );
        }

        // GENERAL SETTING
        $inwave_of_options[] = array("name" => esc_html__("General setting", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Show demo setting panel", 'injob'),
            "desc" => esc_html__("Check this box to active the setting panel. This panel should be shown only in demo mode", 'injob'),
            "id" => "show_setting_panel",
            "std" => 0,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show page heading", 'injob'),
            "desc" => esc_html__("Check this box to show or hide page heading", 'injob'),
            "id" => "show_page_heading",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show breadcrumbs", 'injob'),
            "desc" => esc_html__("Check to display the breadcrumbs in general. Uncheck to hide them.", 'injob'),
            "id" => "breadcrumbs",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show preload", 'injob'),
            "desc" => esc_html__("Check to display the preload page.", 'injob'),
            "id" => "show_preload",
            "std" => 0,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Develop mode", 'injob'),
            "desc" => esc_html__("Check this box to active develop theme mode. For example, it will always compile the color file", 'injob'),
            "id" => "develop_mode",
            "std" => 0,
            "type" => "checkbox");
        $inwave_of_options[] = array(
			"name" => esc_html__("Layout", 'injob'),
            "desc" => esc_html__("Select boxed or wide layout.", 'injob'),
            "id" => "body_layout",
            "std" => "wide",
            "type" => "select",
            "options" => array(
                'boxed' => esc_html__('Boxed', 'injob'),
                'wide' => esc_html__('Wide', 'injob'),
            ));
        $inwave_of_options[] = array("name" => esc_html__("Include WooCommerce libraries globally", 'injob'),
            "desc" => esc_html__("Load WooCommerce styles and scripts on all pages", 'injob'),
            "id" => "enqueue_woocommerce_all_page",
            "std" => 0,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => "Background Image",
            "desc" => esc_html__("Please choose an image or insert an image url to use for the background.", 'injob'),
            "id" => "bg_image",
            "std" => "",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array(
			"name" => esc_html__("Background Image Size", 'injob'),
            "desc" => esc_html__("Select background image size.", 'injob'),
            "id" => "bg_size",
            "std" => 'cover',
            "type" => "select",
            "options" => array('auto' => esc_html__('auto', 'injob'), 'cover' => esc_html__('cover', 'injob'), 'contain' => esc_html__('contain', 'injob')));

        $inwave_of_options[] = array(
			"name" => esc_html__("Background Repeat", 'injob'),
            "desc" => esc_html__("Choose how the background image repeats.", 'injob'),
            "id" => "bg_repeat",
            "std" => "repeat",
            "type" => "select",
            "options" => array('repeat' => esc_html__('repeat', 'injob'), 'repeat-x' => esc_html__('repeat-x', 'injob'), 'repeat-y' => esc_html__('repeat-y', 'injob'), 'no-repeat' => esc_html__('no-repeat', 'injob')));

        //TYPO
        $inwave_of_options[] = array(
			"name" => esc_html__("Typography", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array( "name" => esc_html__("Body Font Family", 'injob'),
            "desc" => esc_html__("Select a font family for body text", 'injob'),
            "id" => "f_body",
            "std" => "Roboto",
            "type" => "select",
            "options" => $google_fonts);
        $inwave_of_options[] = array( "name" => esc_html__("Body Font Settings", 'injob'),
            "desc" => esc_html__("Adjust the settings below to load different character sets and types for fonts. More character sets and types equals to slower page load.", 'injob'),
            "id" => "f_body_settings",
            "std" => "300,400,600,700,800",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Headings Font", 'injob'),
            "desc" => esc_html__("Select a font family for headings", 'injob'),
            "id" => "f_headings",
            "std" => "Montserrat",
            "type" => "select",
            "options" => $google_fonts);
        $inwave_of_options[] = array( "name" => esc_html__("Headings Font Settings", 'injob'),
            "desc" => esc_html__("Adjust the settings below to load different character sets and types for fonts. More character sets and types equals to slower page load.", 'injob'),
            "id" => "f_headings_settings",
            "std" => "300,400,500, 600,700,800,900",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Menu Font", 'injob'),
            "desc" => esc_html__("Select a font family for navigation", 'injob'),
            "id" => "f_nav",
            "std" => "Montserrat",
            "type" => "select",
            "options" => $google_fonts);
        $inwave_of_options[] = array( "name" => esc_html__("Menu Font Settings", 'injob'),
            "desc" => esc_html__("Adjust the settings below to load different character sets and types for fonts. More character sets and types equals to slower page load.", 'injob'),
            "id" => "f_nav_settings",
            "std" => "300,400,500, 600,700,800,900",
            "type" => "text");
        $inwave_of_options[] = array( "name" => esc_html__("Default Font Size", 'injob'),
            "desc" => esc_html__("Default is 15px", 'injob'),
            "id" => "f_size",
            "std" => "13px",
            "type" => "text"
        );
        $inwave_of_options[] = array( "name" => esc_html__("Default Font Line Height", 'injob'),
            "desc" => esc_html__("Default is 28px", 'injob'),
            "id" => "f_lineheight",
            "std" => "28px",
            "type" => "text",
        );

        // COLOR PRESETS
        $inwave_of_options[] = array("name" => esc_html__("Color presets", 'injob'),
            "type" => "heading"
        );

        $inwave_of_options[] = array("name" => esc_html__("Primary Color", 'injob'),
            "desc" => esc_html__("Controls several items, ex: link hovers, highlights, and more.", 'injob'),
            "id" => "primary_color",
            "std" => "#2980b9",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Content Background Color", 'injob'),
            "desc" => esc_html__("Controls the background color of the main content area.", 'injob'),
            "id" => "content_bg_color",
            "std" => "#f1f1f1",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Body Background Color", 'injob'),
            "desc" => esc_html__("Select a background color. Only show when you select General setting > layout > box.", 'injob'),
            "id" => "bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Body Text Color", 'injob'),
            "desc" => esc_html__("Controls the text color of body font.", 'injob'),
            "id" => "body_text_color",
            "std" => "#777",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Link Color", 'injob'),
            "desc" => esc_html__("Controls the link color.", 'injob'),
            "id" => "link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Link Hover Color", 'injob'),
            "desc" => esc_html__("Controls the link hover color.", 'injob'),
            "id" => "link_hover_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Blockquote Color", 'injob'),
            "desc" => esc_html__("Controls the color of blockquote.", 'injob'),
            "id" => "blockquote_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array(
			"name" => esc_html__("Color Scheme", 'injob'),
            "desc" => "",
            "id" => "color_pagetitle_breadcrumb",
            "std" => "<h3>".esc_html__('Page Title & Breadcrumb Color', 'injob')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Page Title Color", 'injob'),
            "desc" => esc_html__("Controls the text color of the page title.", 'injob'),
            "id" => "page_title_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Page Title Background Color", 'injob'),
            "desc" => esc_html__("Controls background color of the page title.", 'injob'),
            "id" => "page_title_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Background Color", 'injob'),
            "desc" => esc_html__("Controls the background color of the breadcrumb.", 'injob'),
            "id" => "breadcrumbs_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Text Color", 'injob'),
            "desc" => esc_html__("Controls the text color of the breadcrumb.", 'injob'),
            "id" => "breadcrumbs_text_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Link Color", 'injob'),
            "desc" => esc_html__("Controls the link color of the breadcrumb.", 'injob'),
            "id" => "breadcrumbs_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Breadcrumbs Link Hover Color", 'injob'),
            "desc" => esc_html__("control the color when user hovers on the links on the breadcrumb.", 'injob'),
            "id" => "breadcrumbs_link_hover_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Color Scheme", 'injob'),
            "desc" => "",
            "id" => "color_scheme_header",
            "std" => "<h3>".esc_html__('Header Color', 'injob')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Top bar Color", 'injob'),
            "desc" => esc_html__("Select a color for the top bar text.", 'injob'),
            "id" => "top_bar_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Menu Link Color", 'injob'),
            "desc" => esc_html__("Select a color for the header link.", 'injob'),
            "id" => "header_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Menu Header Sticky Link Color", 'injob'),
            "desc" => esc_html__("Select a color for the menu header sticky link hover.", 'injob'),
            "id" => "header_sticky_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Menu Link Hover Color", 'injob'),
            "desc" => esc_html__("Select a color for the header link hover.", 'injob'),
            "id" => "header_link_hover_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Sub Menu Link Color", 'injob'),
            "desc" => esc_html__("Select a color for the header sub link.", 'injob'),
            "id" => "header_sub_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Sub Menu Link Hover Color", 'injob'),
            "desc" => esc_html__("Select a color for the header sub link hover.", 'injob'),
            "id" => "header_sub_link_hover_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Submenu Background Color", 'injob'),
            "desc" => esc_html__("Select a background color for submenu.", 'injob'),
            "id" => "header_sub_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("SubMenu Border Color", 'injob'),
            "desc" => esc_html__("Select a border color for the submenu.", 'injob'),
            "id" => "header_sub_bd_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Color Scheme", 'injob'),
            "desc" => "",
            "id" => "color_scheme_font",
            "std" => "<h3>".esc_html__('Footer Color', 'injob')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");

        $inwave_of_options[] = array("name" => esc_html__("Footer Background Color", 'injob'),
            "desc" => esc_html__("Select a color for the footer background.", 'injob'),
            "id" => "footer_bg_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Border Color", 'injob'),
            "desc" => esc_html__("Select a color for the footer border.", 'injob'),
            "id" => "footer_border_color",
            "std" => "",
            "type" => "color");


        $inwave_of_options[] = array("name" => esc_html__("Footer Headings Color", 'injob'),
            "desc" => esc_html__("Controls the text color of the footer heading font.", 'injob'),
            "id" => "footer_headings_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Text Color", 'injob'),
            "desc" => esc_html__("Controls the text color of the footer.", 'injob'),
            "id" => "footer_text_color",
            "std" => "#989898",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Link Color", 'injob'),
            "desc" => esc_html__("Controls the text color of the footer link font.", 'injob'),
            "id" => "footer_link_color",
            "std" => "#989898",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Link Hover Color", 'injob'),
            "desc" => esc_html__("Controls the link hover color of the footer link.", 'injob'),
            "id" => "footer_link_hover_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Footer Link Border Color", 'injob'),
            "desc" => esc_html__("Controls the link border color of the footer link.", 'injob'),
            "id" => "footer_link_border_color",
            "std" => "",
            "type" => "color");

        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Color Scheme", 'injob'),
            "desc" => "",
            "id" => "color_copyright",
            "std" => "<h3>".esc_html__('Copyright Color', 'injob')."</h3>",
            "icon" => true,
            "position" => "start",
            "type" => "accordion");
        $inwave_of_options[] = array("name" => esc_html__("Copyright Background Color", 'injob'),
            "desc" => esc_html__("Controls the background color of the copyright section.", 'injob'),
            "id" => "copyright_bg_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Copyright Text Color", 'injob'),
            "desc" => esc_html__("Controls the text color of the breadcrumb font.", 'injob'),
            "id" => "copyright_text_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Copyright Link Color", 'injob'),
            "desc" => esc_html__("Controls the link color of the breadcrumb font.", 'injob'),
            "id" => "copyright_link_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array("name" => esc_html__("Copyright Link Hover Color", 'injob'),
            "desc" => esc_html__("Controls the link hover color of the Copyright.", 'injob'),
            "id" => "copyright_link_hover_color",
            "std" => "",
            "type" => "color");
        $inwave_of_options[] = array(
            "position" => "end",
            "type" => "accordion");

        //HEADER OPTIONS
        $inwave_of_options[] = array("name" => esc_html__("Header Options", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Header Info", 'injob'),
            "desc" => "",
            "id" => "header_info_content_options",
            "std" => "<h3>".esc_html__('Header Content Options', 'injob')."</h3>",
            "type" => "info");

        $inwave_of_options[] = array("name" => esc_html__("Select a Header Layout", 'injob'),
            "desc" => "",
            "id" => "header_layout",
            "std" => "default",
            "type" => "images",
            "options" => array(
                "default" => get_template_directory_uri() . "/assets/images/header/header-default.jpg",
                "v2" => get_template_directory_uri() . "/assets/images/header/header-v2.png",
                "v3" => get_template_directory_uri() . "/assets/images/header/header-v3.png",
                "v4" => get_template_directory_uri() . "/assets/images/header/header-v4.png",
                "v5" => get_template_directory_uri() . "/assets/images/header/header-v5.png",
                "v6" => get_template_directory_uri() . "/assets/images/header/header-v6.png",
                "v7" => get_template_directory_uri() . "/assets/images/header/header-v7.png",
            ));
        $inwave_of_options[] = array("name" => esc_html__("Sticky Header", 'injob'),
            "desc" => esc_html__("Check to enable a fixed header when scrolling, uncheck to disable.", 'injob'),
            "id" => "header_sticky",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Sticky Header On Mobile", 'injob'),
            "desc" => esc_html__("Check to enable a fixed header when scrolling, uncheck to disable on Mobile.", 'injob'),
            "id" => "header_sticky_mobile",
            "std" => '',
            "type" => "checkbox");

        $inwave_of_options[] = array(
			"name" => esc_html__("Logo", 'injob'),
            "desc" => esc_html__("Please choose an image file for your logo.", 'injob'),
            "id" => "logo",
            "std" => get_template_directory_uri() . "/assets/images/logo.png",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array(
            "name" => esc_html__("Logo Sticky", 'injob'),
            "desc" => esc_html__("Please choose an image file for your logo sticky.", 'injob'),
            "id" => "logo_sticky",
            "std" => get_template_directory_uri() . "/assets/images/logo-sticky.png",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array(
            "name" => esc_html__("Logo Mobile", 'injob'),
            "desc" => esc_html__("Please choose an image file for your logo mobile.", 'injob'),
            "id" => "logo_mobile",
            "std" => get_template_directory_uri() . "/assets/images/logo-mobile.png",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array("name" => esc_html__("Link Logo", 'injob'),
            "desc" => esc_html__("Default links point to the home page", 'injob'),
            "id" => "link_logo",
            "std" => "",
            "type" => "text");


        $inwave_of_options[] = array(
            "name" => esc_html__("Background Image Header", 'injob'),
            "desc" => esc_html__("Please choose an image file for your background header.", 'injob'),
            "id" => "bg_header",
            "std" => "",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array("name" => esc_html__("Background Color Header", 'injob'),
            "desc" => esc_html__("Please choose an color for your background header.", 'injob'),
            "id" => "bg_header_color",
            "std" => "#34495E",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Opacity Background Color Header", 'injob'),
            "desc" => esc_html__("Please enter the value from 0 to 1.", 'injob'),
            "id" => "opacity_bg_header_color",
            "std" => "0.1",
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Background Color Top Bar Header", 'injob'),
            "desc" => esc_html__("Please choose an color for your background top b header.", 'injob'),
            "id" => "bg_top_bar_header_color",
            "std" => "#34495E",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Opacity Background Color Top Bar Header", 'injob'),
            "desc" => esc_html__("Please enter the value from 0 to 1.", 'injob'),
            "id" => "opacity_bg_top_bar_header_color",
            "std" => "0.2",
            "type" => "text");

        $inwave_of_options[] = array(
            "name" => esc_html__("Background Image Header Sticky", 'injob'),
            "desc" => esc_html__("Please choose an image file for your background header sticky.", 'injob'),
            "id" => "bg_header_sticky",
            "std" => get_template_directory_uri() . "/assets/images/bg-header-sticky.jpg",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array("name" => esc_html__("Background Color Header Sticky", 'injob'),
            "desc" => esc_html__("Please choose an color for your background header sticky.", 'injob'),
            "id" => "bg_header_sticky_color",
            "std" => "#34495E",
            "type" => "color");

        $inwave_of_options[] = array("name" => esc_html__("Header contact", 'injob'),
            "desc" => esc_html__("Enter information contact for default header.", 'injob'),
            "id" => "header-contact",
            "std" => "",
            "type" => "textarea");

        $inwave_of_options[] = array("name" => esc_html__("Show login/register button", 'injob'),
            "desc" => esc_html__("Show or hidden Button Login and User register in Header.", 'injob'),
            "id" => "show_button_login_user",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show post a job button", 'injob'),
            "desc" => esc_html__("Show or hidden post a job button in Header.", 'injob'),
            "id" => "show_post_a_job",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Buy Package button", 'injob'),
            "desc" => esc_html__("Show or hidden Buy Package button in Header.", 'injob'),
            "id" => "show_buy_service",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Buy Package URL ", 'injob'),
            "desc" => esc_html__("You can leave blank OR set an url OR an ID of the element", 'injob'),
            "id" => "buy_service_url",
            "std" => "",
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Show search form button", 'injob'),
            "desc" => esc_html__("Show or hidden search form button in Header.", 'injob'),
            "id" => "show_search_form",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Cart Button", 'inmedical'),
            "desc" => esc_html__("Show or hidden Cart button in Header.", 'inmedical'),
            "id" => "show_cart_button",
            "std" => '0',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Notification", 'inmedical'),
            "desc" => esc_html__("Show or hidden Notification in Header.", 'inmedical'),
            "id" => "show_notification",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Buy Theme Button", 'inmedical'),
            "desc" => esc_html__("Show or hidden Buy Theme button in Header.", 'inmedical'),
            "id" => "show_buy_theme_button",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Buy Theme Button URL ", 'injob'),
            "id" => "buy_theme_url",
            "std" => "https://themeforest.net/item/injob-job-board-wordpress-theme/20322987?ref=inwavethemes",
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Show Social Header", 'inmedical'),
            "desc" => esc_html__("Show or hidden Social in Header.", 'inmedical'),
            "id" => "show_social_header",
            "std" => '1',
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Header Info", 'injob'),
            "desc" => "",
            "id" => "header_info_page_title_options",
            "std" => "<h3>".esc_html__("Page Heading Options", 'injob')."</h3>",
            "type" => "info");

        $inwave_of_options[] = array("name" => esc_html__("Page Heading Padding Top", 'injob'),
            "desc" => esc_html__("In pixels, ex: 10px", 'injob'),
            "id" => "page_heading_padding_top",
            "std" => "115px",
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Page Heading Padding Bottom", 'injob'),
            "desc" => esc_html__("In pixels, ex: 10px", 'injob'),
            "id" => "page_heading_padding_bottom",
            "std" => "75px",
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Page Heading Background Image", 'injob'),
            "desc" => esc_html__("Please choose an image or insert an image url to use for the page heading background.", 'injob'),
            "id" => "page_title_bg",
            "std" => get_template_directory_uri() . "/assets/images/bg-page-heading.jpg",
            "mod" => "",
            "type" => "media");

        $inwave_of_options[] = array("name" => esc_html__("Page Heading Background Color", 'injob'),
            "desc" => esc_html__("Please choose an color to use for the page heading background.", 'injob'),
            "id" => "page_title_bg_color",
            "std" => "#34495E",
            "type" => "color");

        $inwave_of_options[] = array(
			"name" => esc_html__("Background Image Size", 'injob'),
            "desc" => esc_html__("Select Page Heading background image size.", 'injob'),
            "id" => "page_title_bg_size",
            "std" => 'cover',
            "type" => "select",
            "options" => array(
				'auto' => esc_html__('auto', 'injob'),
				'cover' => esc_html__('cover', 'injob'),
				'contain' => esc_html__('contain', 'injob')
			)
		);

        $inwave_of_options[] = array("name" => esc_html__("Background Repeat", 'injob'),
            "desc" => esc_html__("Choose how the background image repeats.", 'injob'),
            "id" => "page_title_bg_repeat",
            "std" => "no-repeat",
            "type" => "select",
            "options" => array('repeat' => esc_html__('repeat', 'injob'), 'repeat-x' => esc_html__('repeat-x', 'injob'), 'repeat-y' => esc_html__('repeat-y', 'injob'), 'no-repeat' => esc_html__('no-repeat', 'injob')));

	    $inwave_of_options[] = array("name" => esc_html__("Search Form Background Color", 'injob'),
             "desc" => esc_html__("Please choose an color to use for the search form background of style 2.", 'injob'),
             "id" => "search_page_heading_bg_color",
             "std" => "#34495E",
             "type" => "color");
	    $inwave_of_options[] = array("name" => esc_html__("Search Form Background Color Opacity", 'injob'),
             "desc" => esc_html__("Please enter the value from 0 to 1.", 'injob'),
             "id" => "search_page_heading_bg_color_opacity",
             "std" => "1",
             "type" => "text");

        // FOOTER OPTIONS
        $inwave_of_options[] = array("name" => esc_html__("Footer options", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Show Footer", 'injob'),
            "desc" => esc_html__("Show or hidden footer.", 'injob'),
            "id" => "show_footer",
            "std" => '1',
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Footer style", 'injob'),
            "desc" => "",
            "id" => "footer_option",
            "std" => "default",
            "type" => "images",
            "options" => array(
            "default" => get_template_directory_uri() . "/assets/images/footer/footer-default.jpg",
            ));
        $inwave_of_options[] = array("name" => esc_html__("Footer Default columns", 'injob'),
            "id" => "footer_number_widget",
            "std" => "4",
            "type" => "select",
            "options" => array(
                '4' => '4',
                '3' => '3',
                '2' => '2',
                '1' => '1',
            ));

        $inwave_of_options[] = array("name" => esc_html__("Footer copyright", 'injob'),
            "desc" => esc_html__("Please enter text copyright for footer.", 'injob'),
            "id" => "footer-copyright",
            "std" => wp_kses_post(__("© <a href='#'>Inwavethemes</a> All rights reserved.", 'injob')),
            "mod" => "",
            "type" => "textarea");

        //CUSTOM SIDEBAR
        $inwave_of_options[] = array("name" => esc_html__("Custom Sidebar", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Custom Sidebar", 'injob'),
            "desc" => esc_html__("Custom sidebar", 'injob'),
            "id" => "custom_sidebar",
            "type" => "addoption",
            'option_label' => esc_html__('Sidebar', 'injob'),
            'add_btn_text' => esc_html__('Add New Sidebar', 'injob')
        );

        $inwave_of_options[] = array("name" => esc_html__("Blog", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Blog Listing", 'injob'),
            "desc" => "",
            "id" => "blog_single_post",
            "std" => "<h3>".esc_html__("Blog Listing", 'injob')."</h3>",
            "icon" => true,
            "type" => "info");
        $inwave_of_options[] = array(
            "name" => esc_html__("Sidebar Position", 'injob'),
            "desc" => esc_html__("Select slide bar position", 'injob'),
            "id" => "sidebar_position",
            "std" => "right",
            "type" => "select",
            "options" => array(
                'none' => esc_html__('Without Sidebar', 'injob'),
                'right' => esc_html__('Right', 'injob'),
                'left' => esc_html__('Left', 'injob'),
            ));
        $sideBars = array();
        foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
            $sideBars[$sidebar['id']] = ucwords( $sidebar['name'] );
        }
        $inwave_of_options[] = array(
            "name" => esc_html__("Sidebar Name", 'injob'),
            "desc" => esc_html__("Select slidebar", 'injob'),
            "id" => "sidebar_name",
            "std" => "sidebar-default",
            "type" => "select",
            "options" =>$sideBars);
        $inwave_of_options[] = array(
            "name" => esc_html__("Sticky Sidebar", 'injob'),
            "id" => "sticky_sidebar",
            "std" => "1",
            "type" => "select",
            "options" => array(
                '1' => esc_html__('Yes', 'injob'),
                '0' => esc_html__('No', 'injob'),
            ));
        $inwave_of_options[] = array("name" => esc_html__("Show Categories", 'injob'),
            "desc" => esc_html__("Check the box to display the post categories.", 'injob'),
            "id" => "blog_show_categories",
            "std" => 1,
            "type" => "checkbox");

		$inwave_of_options[] = array("name" => esc_html__("Show Post Date", 'injob'),
            "desc" => esc_html__("Check the box to display blog post date", 'injob'),
            "id" => "blog_show_post_date",
            "std" => 1,
            "type" => "checkbox");
		$inwave_of_options[] = array("name" => esc_html__("Show Post Author", 'injob'),
            "desc" => esc_html__("Check the box to display blog post author", 'injob'),
            "id" => "blog_show_post_author",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show Post Comment", 'injob'),
            "desc" => esc_html__("Check the box to display blog post comment", 'injob'),
            "id" => "blog_show_post_comment",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array(
			"name" => esc_html__("Show Social Sharing", 'injob'),
            "desc" => esc_html__("Check the box to display the social sharing", 'injob'),
            "id" => "blog_show_social_sharing",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Blog Single Post", 'injob'),
            "desc" => "",
            "id" => "blog_single_post",
            "std" => "<h3>".esc_html__("Blog Single Post", 'injob')."</h3>",
            "icon" => true,
            "type" => "info");
        $inwave_of_options[] = array(
            "name" => esc_html__("Sidebar Position", 'injob'),
            "desc" => esc_html__("Select slide bar position", 'injob'),
            "id" => "single_sidebar_position",
            "std" => "right",
            "type" => "select",
            "options" => array(
                'none' => esc_html__('Without Sidebar', 'injob'),
                'right' => esc_html__('Right', 'injob'),
                'left' => esc_html__('Left', 'injob'),
            ));
        $inwave_of_options[] = array(
            "name" => esc_html__("Sidebar Name", 'injob'),
            "desc" => esc_html__("Select slidebar", 'injob'),
            "id" => "single_sidebar_name",
            "std" => "sidebar-default",
            "type" => "select",
            "options" =>$sideBars);
        $inwave_of_options[] = array(
            "name" => esc_html__("Sticky Sidebar", 'injob'),
            "id" => "single_sticky_sidebar",
            "std" => "1",
            "type" => "select",
            "options" => array(
                '1' => esc_html__('Yes', 'injob'),
                '0' => esc_html__('No', 'injob'),
            ));

        $inwave_of_options[] = array("name" => esc_html__("Post Categories", 'injob'),
            "desc" => esc_html__("Check the box to display the post categories", 'injob'),
            "id" => "single_show_categories",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show Post Date", 'injob'),
            "desc" => esc_html__("Check the box to display blog post date", 'injob'),
            "id" => "single_show_post_date",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => esc_html__("Show Author Info", 'injob'),
            "desc" => esc_html__("Check the box to display the author info in the post", 'injob'),
            "id" => "single_show_author_info",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Social Sharing", 'injob'),
            "desc" => esc_html__("Check the box to display the social sharing", 'injob'),
            "id" => "single_show_social_sharing",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Show Tags", 'injob'),
            "desc" => esc_html__("Check the box to display the post tags", 'injob'),
            "id" => "single_show_tags",
            "std" => 1,
            "type" => "checkbox");

        //IMAGE SIZES
        /*$inwave_of_options[] = array("name" => esc_html__("Image Sizes", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => 'Image 370x370',
            "desc" => esc_html__("Check the box to add_image_size which will be used in shortcodes.", 'injob'),
            "id" => "inwave_370_370",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => 'Image 370x245',
            "desc" => esc_html__("Check the box to add_image_size which will be used in shortcodes.", 'injob'),
            "id" => "inwave_370_245",
            "std" => 1,
            "type" => "checkbox");
        $inwave_of_options[] = array("name" => 'Image 570x330',
            "desc" => esc_html__("Check the box to add_image_size which will be used in shortcodes.", 'injob'),
            "id" => "inwave_570_330",
            "std" => 1,
            "type" => "checkbox");*/
        //SOCIAL MEDIA
        $inwave_of_options[] = array("name" => esc_html__("Social Media", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Social Sharing", 'injob'),
            "desc" => "",
            "id" => "social_sharing",
            "std" => "<h3>".esc_html__("Social Sharing", 'injob')."</h3>",
            "type" => "info");
        $inwave_of_options[] = array("name" => esc_html__("Facebook", 'injob'),
            "desc" => esc_html__("Check the box to show the facebook sharing icon in blog, woocommerce and portfolio detail page.", 'injob'),
            "id" => "sharing_facebook",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Twitter", 'injob'),
            "desc" => esc_html__("Check the box to show the twitter sharing icon in blog, woocommerce and portfolio detail page.", 'injob'),
            "id" => "sharing_twitter",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("LinkedIn", 'injob'),
            "desc" => esc_html__("Check the box to show the linkedin sharing icon in blog, woocommerce and portfolio detail page.", 'injob'),
            "id" => "sharing_linkedin",
            "std" => 1,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Google Plus", 'injob'),
            "desc" => esc_html__("Check the box to show the g+ sharing icon in blog, woocommerce and portfolio detail page.", 'injob'),
            "id" => "sharing_google",
            "std" => 0,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Tumblr", 'injob'),
            "desc" => esc_html__("Check the box to show the tumblr sharing icon in blog, woocommerce and portfolio detail page.", 'injob'),
            "id" => "sharing_tumblr",
            "std" => 0,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Pinterest", 'injob'),
            "desc" => esc_html__("Check the box to show the pinterest sharing icon in blog, woocommerce and portfolio detail page.", 'injob'),
            "id" => "sharing_pinterest",
            "std" => 0,
            "type" => "checkbox");

        $inwave_of_options[] = array("name" => esc_html__("Email", 'injob'),
            "desc" => esc_html__("Check the box to show the email sharing icon in blog, woocommerce and portfolio detail page.", 'injob'),
            "id" => "sharing_email",
            "std" => 0,
            "type" => "checkbox");

        $inwave_of_options[] = array(
            "name" => esc_html__("Social Link Configs", 'injob'),
            "desc" => "",
            "id" => "social_link_configs",
            "std" => '<h3>'.esc_html__("Social Link Configs", 'injob').'</h3>',
            "type" => "info",
        );
        $inwave_of_options[] = array("name" => esc_html__("Social links", 'injob'),
            "desc" => wp_kses_post(__("Add new social links. Awesome icon You can get at <a target='_blank' href='https://fortawesome.github.io/Font-Awesome/'>here</a>", 'injob')),
            "id" => "social_links",
            "std" => array(
                array(
                    'order' => 0,
                ),
                array(
                    'title' => esc_html__('Facebook', 'injob'),
                    'icon' => 'fa fa-facebook',
                    'link' => 'http://facebook.com'
                ),
                array(
                    'title' => esc_html__('Twitter', 'injob'),
                    'icon' => 'fa fa-twitter',
                    'link' => 'http://twitter.com'
                ),
                array(
                    'title' => esc_html__('Google Plush', 'injob'),
                    'icon' => 'fa fa-google',
                    'link' => 'http://google-plus.com'
                ),
            ),
            "type" => "social_link"
        );

        $inwave_of_options[] = array("name" => esc_html__("Map", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Google Map API", 'injob'),
            "desc" => wp_kses(__('Use for process data from google service. Eg: map, photo, video... To get Google api, you can access via <a href="https://console.developers.google.com/" target="_blank">here</a>.', 'injob'), inwave_allow_tags('a')),
            "id" => "google_api",
            "std" => '',
            "type" => "text");

        $inwave_of_options[] = array("name" => esc_html__("Map Styles", 'injob'),
            "desc" => wp_kses(__('Using for contact map. You can get <a href="https://snazzymaps.com" target="_blank">here</a>.', 'injob'), inwave_allow_tags('a')),
            "id" => "map_styles",
            "std" => '',
            "type" => "textarea");

        // IMPORT EXPORT
        $inwave_of_options[] = array("name" => esc_html__("Import Demo", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Import Demo Content", 'injob'),
            "desc" => wp_kses(__("We recommend you to <a href='https://wordpress.org/plugins/wordpress-reset/' target='_blank'>reset data</a>  & clean wp-content/uploads before import to prevent duplicate content!", 'injob'), inwave_allow_tags('a')),
            "id" => "demo_data",
            "std" => "#",
            "btntext" => esc_html__('Import Demo Content', 'injob'),
            "type" => "import_button");

        // BACKUP OPTIONS
        $inwave_of_options[] = array("name" => esc_html__("Backup Options", 'injob'),
            "type" => "heading"
        );
        $inwave_of_options[] = array("name" => esc_html__("Backup and Restore Options", 'injob'),
            "id" => "of_backup",
            "std" => "",
            "type" => "backup",
            "desc" => esc_html__('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', 'injob'),
        );

        $inwave_of_options[] = array("name" => esc_html__("Transfer Theme Options Data", 'injob'),
            "id" => "of_transfer",
            "std" => "",
            "type" => "transfer",
            "desc" => esc_html__('You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".', 'injob'),
        );

    }//End function: inwave_of_options()
}//End chack if function exists: inwave_of_options()
?>
