<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 23/02/2016
 * Time: 5:02 CH
 */

register_sidebar(array(
    'name' => esc_html__('Sidebar Default', 'injob'),
    'id' => 'sidebar-default',
    'description' => '',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title"><span>',
    'after_title' => '</span></h3>',
));
if(class_exists('IWJ_Class')){

    register_sidebar(array(
        'name' => esc_html__('Sidebar Job Details', 'injob'),
        'id' => 'sidebar-job',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Sidebar Job Details V2', 'injob'),
        'id' => 'sidebar-job-v2',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Sidebar Candidate Details', 'injob'),
        'id' => 'sidebar-candidate',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Sidebar Candidate Details V2', 'injob'),
        'id' => 'sidebar-candidate-v2',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Sidebar Employer Details', 'injob'),
        'id' => 'sidebar-employer',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Sidebar Employer Details V2', 'injob'),
        'id' => 'sidebar-employer-v2',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Sidebar Jobs 1', 'injob'),
        'id' => 'sidebar-jobs-1',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget sidebar-jobs %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Sidebar Jobs 2', 'injob'),
        'id' => 'sidebar-jobs-2',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget sidebar-jobs %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Sidebar Candidates 1', 'injob'),
        'id' => 'sidebar-candidates',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget sidebar-jobs %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Sidebar Candidates 2', 'injob'),
        'id' => 'sidebar-candidates-2',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget sidebar-jobs %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Sidebar Employers 1', 'injob'),
        'id' => 'sidebar-employers',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget sidebar-jobs %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Sidebar Employers 2', 'injob'),
        'id' => 'sidebar-employers-2',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget sidebar-jobs %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</span></h3>',
    ));

}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar Product', 'injob'),
            'id' => 'sidebar-woocommerce',
            'description' => '',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
}

if(Inwave_Helper::getThemeOption("footer_number_widget")){
    $footer_number_widget = Inwave_Helper::getThemeOption("footer_number_widget");
    $register_sidebar_arr = array();
    switch($footer_number_widget)
    {
        case '4':
            $register_sidebar_arr[] = array(
                'name' => esc_html__( 'Footer widget 4', 'injob' ),
                'id'            => 'footer-widget-4',
                'description' => esc_html__( 'This is footer widget location','injob' ),
                'before_title' =>'<h3 class="widget-title">',
                'after_title' =>'</h3><div class="subtitle">
							<div class="line1"></div>
							<div class="line2"></div>
							<div class="clearfix"></div>
						</div>',
                'before_widget' => '<div class="%1$s widget %2$s">',
                'after_widget' => '</div>',
            );
        case '3':
            $register_sidebar_arr[] = array(
                'name' => esc_html__( 'Footer widget 3', 'injob' ),
                'id'            => 'footer-widget-3',
                'description' => esc_html__( 'This is footer widget location','injob' ),
                'before_title' =>'<h3 class="widget-title">',
                'after_title' =>'</h3><div class="subtitle">
							<div class="line1"></div>
							<div class="line2"></div>
							<div class="clearfix"></div>
						</div>',
                'before_widget' => '<div class="%1$s widget %2$s">',
                'after_widget' => '</div>',
            );
        case '2':
            $register_sidebar_arr[] = array(
                'name' => esc_html__( 'Footer widget 2', 'injob' ),
                'id'            => 'footer-widget-2',
                'description' => esc_html__( 'This is footer widget location','injob' ),
                'before_title' =>'<h3 class="widget-title">',
                'after_title' =>'</h3><div class="subtitle">
							<div class="line1"></div>
							<div class="line2"></div>
							<div class="clearfix"></div>
						</div>',
                'before_widget' => '<div class="%1$s widget %2$s">',
                'after_widget' => '</div>',
            );
        case '1':
            $register_sidebar_arr[] = array(
                'name' => esc_html__( 'Footer widget 1', 'injob' ),
                'id'            => 'footer-widget-1',
                'description' => esc_html__( 'This is footer widget location','injob' ),
                'before_title' =>'<h3 class="widget-title">',
                'after_title' =>'</h3><div class="subtitle">
							<div class="line1"></div>
							<div class="line2"></div>
							<div class="clearfix"></div>
						</div>',
                'before_widget' => '<div class="%1$s widget %2$s">',
                'after_widget' => '</div>',
            );
    }

    $register_sidebar_arr = array_reverse($register_sidebar_arr);
    foreach($register_sidebar_arr as $register_sidebar){
        register_sidebar($register_sidebar);
    }
}


if(is_array(Inwave_Helper::getThemeOption('custom_sidebar')) && count(Inwave_Helper::getThemeOption('custom_sidebar')) > 1){
    $sidebars = Inwave_Helper::getThemeOption('custom_sidebar');
    unset($sidebars[0]);
    foreach($sidebars as $sidebar){
        if($sidebar['option']){
            register_sidebar(array(
                'name' => $sidebar['option'],
                'id' => strtolower (str_replace(" ","-",trim($sidebar['option']))),
                'description' => '',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget' => '</aside>',
                'before_title' => '<h3 class="widget-title"><span>',
                'after_title' => '</span></h3>',
            ));
        }
    }
}

register_nav_menus(array(
    'primary' => esc_html__('Primary Menu', 'injob'),
));
