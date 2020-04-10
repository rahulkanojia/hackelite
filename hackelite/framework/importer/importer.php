<?php

/**
 * theme import demo data content
 *
 * @package injob
 */
defined('ABSPATH') or die('You cannot access this script directly');
// Sample Data Importer
// Hook importer into admin init
add_action('wp_ajax_inwave_importer', 'inwave_importer');

function inwave_importer() {
    WP_Filesystem();
    global $wp_filesystem;

    if (!defined('WP_LOAD_IMPORTERS'))
        define('WP_LOAD_IMPORTERS', true); // we are loading importers

    if (!class_exists('WP_Importer')) { // if main importer class doesn't exist
        include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
    }

    include WP_PLUGIN_DIR . '/inwave-common/inc/importer-post-id-preservation.php';

    include WP_PLUGIN_DIR . '/inwave-common/inc/wordpress-importer.php';

    if (!current_user_can('manage_options')) {
        inwave_import_response('error', 'Error: Permission denied');
    }

    if (!headers_sent()) {
        if (!session_id()) {
            session_start();
        }
    } else {
        inwave_import_response('error', 'Error: Could not start session! Please try to turn off debug mode and error reporting');
    }

    $steps = array(
        'prepare' => esc_html__('Preparing', 'injob'),
        'media'=> esc_html__('Importing media', 'injob'),
        'posts'=> esc_html__('Importing posts', 'injob'),
        'sliders'=> esc_html__('Importing sliders', 'injob'),
        'widget'=> esc_html__('Importing widgets', 'injob'),
        'theme_options'=> esc_html__('Importing theme options', 'injob'),
        'finish'=> esc_html__('Finish', 'injob'),
    );

    if (isset($_REQUEST['iw_stage']) && $_REQUEST['iw_stage'] == 'init') {
        $importer = array();
        $importer['steps'] = array_keys($steps);
        $importer['step_done'] = 0;
        $importer['media'] = 0;
    } else {
        $importer = unserialize($_SESSION['inwave_importer']);
        $importer['base']->message = '';
    }

    ob_start();
    $step = $importer['steps'][0];

    $code = 'continue';
    $percent = 0;
    switch ($step) {
        case 'prepare':
            $importer['base'] = new WP_Import_Extend();
            $importer['base']->fetch_attachments = true;
            $importer['base']->allow_post_types = array(
                'post',
                'page',
                'wpcf7_contact_form',
                'attachment',
                'iwj_job',
                'iwj_employer',
                'iwj_candidate',
                'iwj_application',
                'iwj_package',
                'iwj_resum_package',
                'iwj_u_package',
                'iwj_order',
                'product_variation',
                'product',
                'faq',
                'nav_menu_item'
            );
            $importer['base']->total_post_types = count($importer['base']->allow_post_types);
            $importer['base']->allow_term_types = array(
                'nav_menu',
                'iwj_cat',
                'iwj_type',
                'iwj_salary',
                'iwj_skill',
                'iwj_level',
                'iwj_keyword',
                'iwj_location',
                'product_tag',
                'product_type',
                'product_cat',
            );
            /** get data from xml */
            $theme_xml = get_template_directory() . '/framework/importer/data/main_data.xml';
            $importer['base']->import_start($theme_xml);
            $importer['base']->total_posts = count($importer['base']->posts);

            //import users
            inwave_import_users();

            array_shift($importer['steps']);
            $importer['step_done']++;
            $percent = 5;
            break;
        case 'media':
            @set_time_limit( 0 );
            $media_sources = array('media_source_33.zip', 'media_source_34.zip');
            /*$media_source = 'http://inwavethemes.com/wordpress/injob/media_source.zip';
            $upload = wp_upload_dir();
            $media_des = download_url($media_source);
            if($media_des && is_string($media_des)){
                if(true === unzip_file($media_des, $upload['basedir'])){
                    @unlink($media_des);
                }
            }*/
            $media_name = $media_sources[$importer['media']];
            $media_source = 'http://data.inwavethemes.com/injob/'.$media_name;
            $upload = wp_upload_dir();
            $media_des = $upload['basedir'].'/'.$media_name;
            if(@copy($media_source, $media_des)){
                if(true === unzip_file($media_des, $upload['basedir'])){
                    @unlink($media_des);
                }
            }

            $importer['media']++;
            if($importer['media'] == count($media_sources)){
                array_shift($importer['steps']);
                $importer['step_done']++;
            }

            $percent = 5 + round(($importer['media']/count($media_sources))*20, 0);

            break;
        case 'posts':
            if($importer['base']->importing()){
                $percent = 25 + round(($importer['base']->total_posts - count($importer['base']->posts))/ $importer['base']->total_posts * 45, 0);
            }else{
                array_shift($importer['steps']);
                $importer['step_done']++;
                $percent = 70;
            }
            $message = $importer['base']->message;
            break;
        case 'sliders':
            inwave_import_sliders();
            array_shift($importer['steps']);
            $importer['step_done']++;
            $percent = 80;
            break;
        case 'widget':
            inwave_import_widgets();
            array_shift($importer['steps']);
            $importer['step_done']++;
            $percent = 85;
            break;
        case 'theme_options':
            /** Import Theme Options */
            $inwave_theme_options_txt = get_template_directory() . '/framework/importer/data/theme_options.txt'; // theme options data file
            $inwave_theme_options_txt = $wp_filesystem->get_contents($inwave_theme_options_txt);
            $data = json_decode($inwave_theme_options_txt, true);  /* decode theme options data */
            inwave_of_save_options($data); // update theme options
            array_shift($importer['steps']);
            $importer['step_done']++;
            $percent = 90;
            break;
        case 'finish':
            global $wpdb;
            //update wrong url in metadata:
            $wpdb->query($wpdb->prepare("update {$wpdb->postmeta} set meta_value = replace(meta_value,'http://jobboard.inwavethemes.com/', %s)", home_url('/')));
            $wpdb->query($wpdb->prepare("update {$wpdb->termmeta} set meta_value = replace(meta_value,'http://jobboard.inwavethemes.com/', %s)", home_url('/')));

            $importer['base']->import_end();
            inwave_import_pages();
            inwave_import_woocommerce();
            inwave_import_menu();
            //inport jobs settings
            if(class_exists('IWJ_Install')){
                IWJ_Install::import_options();
            }

            Inwave_Customizer::store_customize_file();

            // Flush rules after install
            flush_rewrite_rules();

            $importer['step_done']++;

            $message = '<b style="color:#444">'.esc_html__('Cheers! The demo data has been imported successfully! Please reload this page to finish!', 'injob').'</b>';
            $code = 'completed';
            $percent = 100;
            break;
    }

    ob_end_clean();
    /** store state to session */
    $_SESSION['inwave_importer'] = serialize($importer);

    if(!isset($message)){
        $message = $steps[$importer['steps'][0]];
    }

    /** response to client */
    inwave_import_response($code, $message, $percent);
}

function inwave_get_import_data_types() {
    $data_types = array();
    $data_types['post-type'] = array();
    $data_types['data-type'] = array();

    $data_types['post-type']['page'] = 'Pages';
    $data_types['post-type']['post'] = 'Posts';
    if (defined('WPCF7_PLUGIN') && WPCF7_PLUGIN) {
        $data_types['post-type']['contact'] = 'Contacts';
    }
    if (class_exists('IWJ_Class')) {
        $data_types['post-type']['injob'] = 'Jobs';
    }
    $data_types['post-type']['media'] = 'Media';
    $data_types['post-type']['menu'] = 'Menus';
    $data_types['data-type']['widget'] = 'Widgets';

    return $data_types;
}

function inwave_import_pages() {

    /** update option whmcs */
    // Set reading options
    $homepage = get_page_by_title('Home Default');
    $blogpage = get_page_by_title('Blog');
    if ($homepage->ID) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $homepage->ID); // Front Page
    }
    if ($blogpage->ID) {
        update_option('page_for_posts', $blogpage->ID);
    }
}

function inwave_import_menu() {
    // Set imported menus to registered theme locations
    $locations = get_theme_mod('nav_menu_locations'); // registered menu locations in theme
    $menus = wp_get_nav_menus(); // registered menus

    if ($menus) {
        foreach ($menus as $menu) { // assign menus to theme locations
            if (strtolower($menu->name) == 'main menu') {
                $locations['primary'] = $menu->term_id;
            }
        }
    }
    set_theme_mod('nav_menu_locations', $locations); // set menus to locations
}

function inwave_import_woocommerce() {
    if (class_exists('woocommerce')) {
        //update option before importing
        update_option('yith_wcmg_zoom_position', 'inside');
        // Set pages
        $woopages = array(
            'woocommerce_shop_page_id' => 'Shop',
            'woocommerce_cart_page_id' => 'Cart',
            'woocommerce_checkout_page_id' => 'Checkout',
            'woocommerce_pay_page_id' => 'Checkout &#8594; Pay',
            'woocommerce_thanks_page_id' => 'Order Received',
            'woocommerce_myaccount_page_id' => 'My Account',
            'woocommerce_edit_address_page_id' => 'Edit My Address',
            'woocommerce_view_order_page_id' => 'View Order',
            'woocommerce_change_password_page_id' => 'Change Password',
            'woocommerce_logout_page_id' => 'Logout',
            //'woocommerce_lost_password_page_id' => 'Lost Password'
        );
        foreach ($woopages as $woo_page_name => $woo_page_title) {
            $woopage = get_page_by_title($woo_page_title);
            if (isset($woopage->ID) && $woopage->ID) {
                update_option($woo_page_name, $woopage->ID); // Front Page
            }
        }
        // We no longer need to install pages
        delete_option('_wc_needs_pages');
        delete_transient('_wc_activation_redirect');
    }
}

function inwave_import_sliders() {
    // Import revolution sliders
    if (class_exists('RevSlider')) {
        $revapi = new RevSlider();

        // import slider 1
        $_FILES["import_file"]["tmp_name"] = get_template_directory() . '/framework/importer/data/slider-1.zip';
        ob_start();
        $revapi->importSliderFromPost();
        ob_end_clean();

        // import slider 2
        $_FILES["import_file"]["tmp_name"] = get_template_directory() . '/framework/importer/data/slider-2.zip';
        ob_start();
        $revapi->importSliderFromPost();
        ob_end_clean();

        // import slider 3.0
        $_FILES["import_file"]["tmp_name"] = get_template_directory() . '/framework/importer/data/new-30.zip';
        ob_start();
        $revapi->importSliderFromPost();
        ob_end_clean();

        // import slider 3
        $_FILES["import_file"]["tmp_name"] = get_template_directory() . '/framework/importer/data/slider-3.zip';
        ob_start();
        $revapi->importSliderFromPost();
        ob_end_clean();

        // import slider 4
        $_FILES["import_file"]["tmp_name"] = get_template_directory() . '/framework/importer/data/landing-page.zip';
        ob_start();
        $revapi->importSliderFromPost();
        ob_end_clean();
    }
}

function inwave_import_widgets() {
    global $wp_filesystem;
    // Add sidebar widget areas
    $sidebars = array(
        'sidebar-default' => 'Sidebar Default',
        'sidebar-job' => 'Sidebar Job Details',
        'sidebar-candidate' => 'Sidebar Candidate Details',
        'sidebar-candidate-v2' => 'Sidebar Candidate Details V2',
        'sidebar-employer' => 'Sidebar Employer Details',
        'sidebar-jobs-1' => 'Sidebar Jobs 1',
        'sidebar-jobs-2' => 'Sidebar Jobs 2',
        'sidebar-candidates' => 'Sidebar Candidates 1',
        'sidebar-candidates-2' => 'Sidebar Candidates 2',
        'sidebar-employers' => 'Sidebar Employers 1',
        'sidebar-employers-2' => 'Sidebar Employers 2',
        'footer-widget-1' => 'Footer widget 1',
        'footer-widget-2' => 'Footer widget 2',
        'footer-widget-3' => 'Footer widget 3',
        'footer-widget-4' => 'Footer widget 4',
    );
    update_option('sbg_sidebars', $sidebars);

    // Add data to widgets
    $widgets_json = get_template_directory() . '/framework/importer/data/widget_data.json'; // widgets data file
    $widget_data = $wp_filesystem->get_contents($widgets_json);
    inwave_import_widget_data($widget_data);
}

function inwave_import_response($code, $message, $percent = 0) {
    $response = array();
    $response['code'] = $code;
    $response['msg'] = $message;
    $response['percent'] = $percent . '%';
    echo json_encode($response);
    exit;
}

// Parsing Widgets Function
// Thanks to http://wordpress.org/plugins/widget-settings-importexport/
function inwave_import_widget_data($widget_data) {
    $json_data = $widget_data;
    $json_data = json_decode($json_data, true);

    $sidebar_data = $json_data[0];
    $widget_data = $json_data[1];

    // binding menu id again for custom menu widget
    $menus = wp_get_nav_menus();

    $menu_names = array( 0 => "For Candidates", 1=> "For Employers");
    $new_wg = array();
    foreach ($widget_data as $key => $tp_widgets) {
        if ($key == 'nav_menu') {
            $j = 0;
            foreach ($tp_widgets as $key => $tp_widget) {
                if(isset($menu_names[$j])){
                    foreach ($menus as $menu) {
                        if ($menu_names[$j] == $menu->name) {
                            $tp_widget['nav_menu'] = $menu->term_id;
                            break;
                        }
                    }
                }

                $j++;
                $new_wg[$key] = $tp_widget;
            }
            $widget_data['nav_menu'] = $new_wg;
        }
    }

    foreach ($widget_data as $widget_data_title => $widget_data_value) {
        $widgets[$widget_data_title] = '';
        foreach ($widget_data_value as $widget_data_key => $widget_data_array) {
            if (is_int($widget_data_key)) {
                $widgets[$widget_data_title][$widget_data_key] = 'on';
            }
        }
    }
    unset($widgets[""]);

    foreach ($sidebar_data as $title => $sidebar) {
        $count = count($sidebar);
        for ($i = 0; $i < $count; $i++) {
            $widget = array();
            $widget['type'] = trim(substr($sidebar[$i], 0, strrpos($sidebar[$i], '-')));
            $widget['type-index'] = trim(substr($sidebar[$i], strrpos($sidebar[$i], '-') + 1));
            if (!isset($widgets[$widget['type']][$widget['type-index']])) {
                unset($sidebar_data[$title][$i]);
            }
        }
        $sidebar_data[$title] = array_values($sidebar_data[$title]);
    }

    foreach ($widgets as $widget_title => $widget_value) {
        foreach ($widget_value as $widget_key => $widget_value) {
            $widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
        }
    }

    $sidebar_data = array(array_filter($sidebar_data), $widgets);

    inwave_parse_import_data($sidebar_data);
}

function inwave_parse_import_data($import_array) {
    global $wp_registered_sidebars;
    $sidebars_data = $import_array[0];
    $widget_data = $import_array[1];
    $current_sidebars = get_option('sidebars_widgets');
    $new_widgets = array();

    foreach ($sidebars_data as $import_sidebar => $import_widgets) :

        foreach ($import_widgets as $import_widget) :
            //if the sidebar exists
            if (isset($wp_registered_sidebars[$import_sidebar])) :
                $title = trim(substr($import_widget, 0, strrpos($import_widget, '-')));
                $index = trim(substr($import_widget, strrpos($import_widget, '-') + 1));
                $current_widget_data = get_option('widget_' . $title);
                $new_widget_name = inwave_get_new_widget_name($title, $index);
                $new_index = trim(substr($new_widget_name, strrpos($new_widget_name, '-') + 1));

                if (!empty($new_widgets[$title]) && is_array($new_widgets[$title])) {
                    while (array_key_exists($new_index, $new_widgets[$title])) {
                        $new_index++;
                    }
                }
                $current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
                if (array_key_exists($title, $new_widgets)) {
                    $new_widgets[$title][$new_index] = $widget_data[$title][$index];
                    $multiwidget = $new_widgets[$title]['_multiwidget'];
                    unset($new_widgets[$title]['_multiwidget']);
                    $new_widgets[$title]['_multiwidget'] = $multiwidget;
                } else {
                    $current_widget_data[$new_index] = $widget_data[$title][$index];

                    $current_multiwidget = isset($current_widget_data['_multiwidget']) ? $current_widget_data['_multiwidget'] : '';
                    $new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
                    $multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
                    unset($current_widget_data['_multiwidget']);
                    $current_widget_data['_multiwidget'] = $multiwidget;
                    $new_widgets[$title] = $current_widget_data;
                }

            endif;
        endforeach;
    endforeach;

    if (isset($new_widgets) && isset($current_sidebars)) {
        update_option('sidebars_widgets', $current_sidebars);

        foreach ($new_widgets as $title => $content)
            update_option('widget_' . $title, $content);

        return true;
    }

    return false;
}

function inwave_get_new_widget_name($widget_name, $widget_index) {
    $current_sidebars = get_option('sidebars_widgets');
    $all_widget_array = array();
    foreach ($current_sidebars as $sidebar => $widgets) {
        if (!empty($widgets) && is_array($widgets) && $sidebar != 'wp_inactive_widgets') {
            foreach ($widgets as $widget) {
                $all_widget_array[] = $widget;
            }
        }
    }
    while (in_array($widget_name . '-' . $widget_index, $all_widget_array)) {
        $widget_index++;
    }
    $new_widget_name = $widget_name . '-' . $widget_index;
    return $new_widget_name;
}

// Rename sidebar
function inwave_name_to_class($name) {
    $class = str_replace(array(' ', ',', '.', '"', "'", '/', "\\", '+', '=', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '<', '>', '?', '[', ']', '{', '}', '|', ':',), '', $name);
    return $class;
}

function inwave_export_users(){
    $users = get_users();
    if($users){
        if(!function_exists('WP_Filesystem')){
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        WP_Filesystem();
        $user_data = array();
        $i = 0;
        foreach ($users as $user){
            $user_data[$i] = array();
            $user_data[$i][] = $user->ID;
            $user_data[$i][] = $user->user_login;
            $user_data[$i][] = $user->user_email;
            $user_data[$i][] = $user->display_name;
            $user_data[$i][] = $user->first_name;
            $user_data[$i][] = $user->last_name;
            $user_data[$i][] = $user->roles;
            $user_data[$i][] = $user->user_url;
            $user_data[$i][] = $user->user_description;
            $employer_post = get_user_meta($user->ID, '_iwj_employer_post', true);
            $candidate_post = get_user_meta($user->ID, '_iwj_candidate_post', true);
            $avatar = get_user_meta($user->ID, '_iwj_avatar', true);
            $meta = array();
            if($employer_post){
                $meta['_iwj_employer_post'] = $employer_post;
            }
            if($candidate_post){
                $meta['_iwj_candidate_post'] = $candidate_post;
            }
            if($avatar){
                $meta['_iwj_avatar'] = $avatar;
            }
            $user_data[$i][] = $meta;
            $i++;
        }

        global $wp_filesystem;
        $wp_filesystem->put_contents(
            get_template_directory() . '/framework/importer/data/users.txt',
            json_encode($user_data)
        );
    }
}

function inwave_import_users(){
    if(!function_exists('WP_Filesystem')){
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    WP_Filesystem();
    global $wp_filesystem;
    $users = $wp_filesystem->get_contents(get_template_directory() . '/framework/importer/data/users.txt');
    $users = json_decode($users, true);
    $author_mapping = array();
    foreach ($users as $user){
        if(!username_exists($user[1])){
            $user_data = array(
                //'ID' => $user[0],
                'user_login' => $user[1],
                'user_email' => $user[2],
                'display_name' => $user[3],
                'first_name' => $user[4],
                'last_name' => $user[5],
                'role' => $user[6][0],
                'user_url' => $user[7],
                'description' => $user[8],
                'user_pass' => wp_generate_password(),
            );
            $user_id = wp_insert_user( $user_data );

            if($user_id){
                foreach ($user[9] as $meta_key => $meta_value){
                    update_user_meta($user_id, $meta_key, $meta_value);
                }
            }

            $author_mapping[$user[1]] = $user_id;
        }elseif($user[1] != 'admin'){
           $_user = get_user_by('login', $user[1]);
           if($_user && !is_wp_error($_user)){
               $author_mapping[$user[1]] = $_user->ID;
           }
        }
    }

    if($author_mapping){
        $wp_filesystem->put_contents(
            get_template_directory() . '/framework/importer/data/users_mapping.txt',
            json_encode($author_mapping)
        );
    }
}