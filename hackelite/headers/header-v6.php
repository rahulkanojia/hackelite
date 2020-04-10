<?php
$inwave_theme_option = Inwave_Helper::getConfig();
$header_sticky       = Inwave_Helper::getPostOption( 'header_sticky', 'header_sticky' );
$header_sticky_mobile       = Inwave_Helper::getPostOption( 'header_sticky_mobile', 'header_sticky_mobile' );
if ( $header_sticky && function_exists( 'iwj_get_page_id' ) ) {
    $post = get_post();
    if ( $post && iwj_get_page_id( 'dashboard' ) == $post->ID ) {
        $header_sticky = false;
    }
}

$show_button_login_user  = Inwave_Helper::getPostOption( 'show_button_login_user', 'show_button_login_user' );
$show_buy_service  = Inwave_Helper::getPostOption( 'show_buy_service', 'show_buy_service' );
$buy_service_url   = Inwave_Helper::getPostOption( 'buy_service_url', 'buy_service_url' );
$show_post_a_job   = Inwave_Helper::getPostOption( 'show_post_a_job', 'show_post_a_job' );
$show_search_form  = Inwave_Helper::getPostOption( 'show_search_form', 'show_search_form' );
$show_cart_button  = Inwave_Helper::getThemeOption( 'show_cart_button', 'show_cart_button' );
$show_notification  = Inwave_Helper::getThemeOption( 'show_notification', 'show_notification' );
$show_social_header  = Inwave_Helper::getThemeOption( 'show_social_header', 'show_social_header' );
$show_buy_theme_button  = Inwave_Helper::getThemeOption( 'show_buy_theme_button', 'show_buy_theme_button' );

$logo              = Inwave_Helper::getPostOption( 'logo', 'logo' );
$logo_sticky       = Inwave_Helper::getPostOption( 'logo_sticky', 'logo_sticky' );
$logo_mobile       = Inwave_Helper::getPostOption( 'logo_mobile', 'logo_mobile' );
$link_logo = Inwave_Helper::getThemeOption('link_logo');
$show_page_heading = Inwave_Helper::getPostOption( 'show_pageheading', 'show_page_heading' );
$disable_candidate_register = function_exists('iwj_option') ? iwj_option( 'disable_candidate_register' ):'';
$disable_employer_register  = function_exists('iwj_option') ? iwj_option( 'disable_employer_register' ):'';

$show_form_find_jobs = Inwave_Helper::getPostOption('show_form_find_jobs');

if(function_exists('iwj_option')){
    $candidate = IWJ_Candidate::get_candidate(get_the_ID());
    $candidate_details_version      = iwj_option( 'candidate_details_version', 'v1' );
    $template_c_detail_version = '';
    if ($candidate) {
        $template_c_detail_version           = $candidate->get_template_detail_version();
    }
    $details_versions = $template_c_detail_version ? $template_c_detail_version : $candidate_details_version;

    $employer = IWJ_Employer::get_employer( get_the_ID() );
    $employer_details_version  = iwj_option( 'employer_details_version', 'v1' );
    $template_e_detail_version = '';
    if ($employer) {
        $template_e_detail_version = $employer->get_template_detail_version();
    }
    $employer_details_versions = $template_e_detail_version ? $template_e_detail_version : $employer_details_version;

    $job = IWJ_Job::get_job( get_the_ID() );
    $job_details_version  = iwj_option( 'job_details_version', 'v1' );
    $template_j_detail_version = '';
    if ($job) {
        $template_j_detail_version = $job->get_template_detail_version();
    }
    $job_details_versions = $template_j_detail_version ? $template_j_detail_version : $job_details_version;

    $show_search_form_jobs  = iwj_option( 'show_search_form_jobs');
    $search_form_jobs_style  = iwj_option( 'search_form_jobs_style');
    $find_jobs_style  = iwj_option( 'find_jobs_style');
    $advanced_search_jobs_style  = iwj_option( 'advanced_search_jobs_style');
}

if ( function_exists( 'WC' ) ) {
    $cartUrl   = wc_get_cart_url();
    $cartTotal = WC()->cart->cart_contents_count;
}
$header_v3    = '';
$header_class = array();
if (( ! is_page_template( 'page-templates/home-page.php' ) && ($show_page_heading == '0' || $show_page_heading == 'no' || $show_page_heading == '' ) && !is_singular( 'iwj_job' ) && !is_singular( 'iwj_candidate' ) && !is_singular( 'iwj_employer' )) || (is_singular( 'iwj_job' ) && $job_details_versions == 'v2') || (is_singular( 'iwj_candidate' ) && $details_versions == 'v2') || (is_singular( 'iwj_employer' ) && $employer_details_versions == 'v2')) {
    $header_v3      = 'no-padding';
    $header_class[] = ' no-page-heading';
}
if ( ($header_sticky && $header_sticky != 'no' && !is_singular( 'iwj_job' ) && !is_singular( 'iwj_candidate' ) && !is_singular( 'iwj_employer' )) || (is_singular( 'iwj_job' ) && $job_details_versions == 'v1') || (is_singular( 'iwj_candidate' ) && $details_versions == 'v1') || (is_singular( 'iwj_employer' ) && $employer_details_versions == 'v1')) {
    $header_class[] = 'header-sticky';
} else {
    $header_class[] = 'no-header-sticky';
}
if ( $header_sticky_mobile) {
    $header_class[] = 'header-sticky-mobile';
} else {
    $header_class[] = 'no-header-sticky-mobile';
}
if ( class_exists( 'IWJ_Class' ) && $show_form_find_jobs == 'yes' && ! ( is_page( iwj_get_page_id( 'candidates' ) ) ) ) {
	$header_class[] = 'has-search-form';
}
?>
<div class="iw-header-version3 iw-header-version6 <?php echo esc_attr( $header_v3 ); ?>">
    <div class="header header-default header-style-default v3 v6 <?php echo esc_attr( implode( ' ', $header_class ) ); ?> ">
        <div class="iw-top-bar-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-sm-8 col-xs-9">
                        <div class="top-bar-left">
                            <?php if ( Inwave_Helper::getThemeOption( 'header-contact' ) ) {
                                ;
                            } ?>
                            <div class="contact"><?php echo( Inwave_Helper::getThemeOption( 'header-contact' ) ); ?></div>
                            <?php ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-4 col-xs-3">
                        <div class="top-bar-right">

                            <?php echo function_exists('iwj_get_languages_flag_html') ? iwj_get_languages_flag_html() : ''; ?>

                            <?php if ( $show_social_header && $show_social_header != 'no' ) { ?>
                                <div class="social-header">
                                    <div><?php echo inwave_get_social_link(); ?></div>
                                </div>
                            <?php  } ?>
                            <?php if ( class_exists( 'IWJ_Class' ) ) {
                                $disable_notification = iwj_option( 'disable_notification', array() );
                                $user = IWJ_User::get_user();
                                if ($show_notification && $show_notification != 'no') {
                                    if ( ( $user && ( ( $user->is_candidate() && ! in_array( 'candidate', $disable_notification ) ) || ( $user->is_employer() && ! in_array( 'employer', $disable_notification ) ) ) ) || ( ! is_user_logged_in() && ! in_array( 'guest', $disable_notification ) ) ) { ?>
                                        <div class="notification">
                                            <?php iwj_get_template_part( 'notification-menu', array() ); ?>
                                        </div>
                                    <?php
                                    }
                                }
                                if ($show_button_login_user && $show_button_login_user != 'no') {
                                    if ( $user ) {
                                        $dashboard_url = iwj_get_page_permalink( 'dashboard' );
                                        $tab           = isset( $_GET['iwj_tab'] ) ? $_GET['iwj_tab'] : '';
                                        if ( ! $tab ) {
                                            $tab = 'profile';
                                        }
                                        ?>
                                        <div class="author-login">
                                            <a href="<?php echo esc_url( $dashboard_url ); ?>">
                                                <span class="author-avatar"><?php echo get_avatar( $user->get_id(), 90 ); ?></span>
                                                <span class="author-name"><?php echo (string) $user->get_display_name(); ?></span>
                                            </a>
                                            <div class="iwj-dashboard-menu">
                                                <?php iwj_get_template_part( 'dashboard-menu', array( 'tab' => $tab ) ) ?>
                                            </div>
                                        </div>
                                    <?php } else {

                                        $class = ! $disable_candidate_register || ! $disable_employer_register ? "" : "only-login";
                                        echo '<span class="register-login ' . $class . '">';
                                        $active_class = "active";
                                        if ( ! $disable_candidate_register || ! $disable_employer_register ) {
                                            $active_class = "";
                                            echo '<a class="register active" href="' . esc_url( iwj_get_page_permalink( 'register' ) ) . '" onclick="return InwaveRegisterBtn();">' . __( 'register', 'injob' ) . '</a>';
                                        }
                                        echo '<a class="login ' . $active_class . '" href="' . esc_url( iwj_get_page_permalink( 'login' ) ) . '" onclick="return InwaveLoginBtn();">' . __( 'login', 'injob' ) . '</a>';
                                        echo '</span>';
                                    }
                                } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-default iw-header">
            <div class="container">
                <div class="navbar-default-inner">
                    <h1 class="iw-logo float-left">
                        <a href="<?php echo $link_logo ? esc_url($link_logo) : esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr( bloginfo( 'name' ) ); ?>">
                            <img class="main-logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php esc_attr( bloginfo( 'name' ) ); ?>">
                            <img class="sticky-logo" src="<?php echo esc_url( $logo_sticky ); ?>" alt="<?php esc_attr( bloginfo( 'name' ) ); ?>">
                            <img class="logo-mobile" src="<?php echo esc_url( $logo_mobile ); ?>" alt="<?php esc_attr( bloginfo( 'name' ) ); ?>">
                        </a>
                    </h1>
                    <div class="header-btn-action">

                        <?php echo function_exists('iwj_get_languages_flag_html') ? iwj_get_languages_flag_html() : ''; ?>

                        <div class="btn-action-wrap">
                                <span class="off-canvas-btn">
                                        <i class="fa fa-bars"></i>
                                </span>
                            <?php if ( ( is_user_logged_in() && current_user_can( 'create_iwj_jobs' ) ) || ( ! is_user_logged_in() && ! iwj_option( 'disable_employer_register' ) ) ) { ?>
                                <div class="iwj-action-button float-right">
                                    <?php if ( $show_post_a_job && $show_post_a_job != 'no' && function_exists( 'iwj_get_page_permalink' ) ) { ?>
                                        <?php
                                        $new_job_url = esc_url( add_query_arg( array( 'iwj_tab' => 'new-job' ), iwj_get_page_permalink( 'dashboard' ) ) );
                                        ?>
                                        <div class="iw-post-a-job">
                                            <a class="action-button" href="<?php echo (string) $new_job_url; ?>">
                                                <i class="ion-android-add-circle"></i>
                                            <span data-hover="<?php echo esc_html( __( 'Post a job', 'injob' ) ); ?>">
                                        <?php echo esc_html( __( 'Post a job', 'injob' ) ); ?>
                                    </span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if ( $show_buy_service && $show_buy_service != 'no' && function_exists( 'iwj_get_page_permalink' ) && ! current_user_can( 'administrator' ) ): ?>
                                        <?php
                                        if ( ! $buy_service_url ) {
                                            $buy_service_url = esc_url( add_query_arg( array( 'iwj_tab' => 'new-package' ), iwj_get_page_permalink( 'dashboard' ) ) );
                                        }
                                        ?>
                                        <div class="buy-service-button">
                                            <a class="action-button <?php echo( strpos( $buy_service_url, '#' ) === 0 ? 'has-scroll' : '' ); ?>" href="<?php echo (string) $buy_service_url; ?>">
                                                <i class="ion-android-cloud"></i>
                                                <span data-hover="<?php echo esc_html( __( 'Buy A Package', 'injob' ) ); ?>">
                                                    <?php echo esc_html( __( 'Buy A Package', 'injob' ) ); ?>
                                                </span>
                                            </a>
                                        </div>
                                    <?php
                                    endif; ?>
                                </div>
                            <?php } elseif ( is_user_logged_in() && current_user_can( 'apply_job' ) ) { ?>
                                <div class="iwj-action-button float-right">
                                    <?php if ( $show_post_a_job && $show_post_a_job != 'no' && function_exists( 'iwj_get_page_permalink' ) ) { ?>
                                        <?php
                                        $new_resume_url = esc_url( add_query_arg( array( 'iwj_tab' => 'profile' ), iwj_get_page_permalink( 'dashboard' ) ) );
                                        ?>
                                        <div class="iw-post-a-job">
                                            <a class="action-button" href="<?php echo (string) $new_resume_url; ?>">
                                                <i class="ion-android-add-circle"></i>
                                            <span data-hover="<?php echo esc_html( __( 'Update a resume', 'injob' ) ); ?>">
                                        <?php echo esc_html( __( 'Update a resume', 'injob' ) ); ?>
                                    </span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php
                            } ?>
                            <?php if ( class_exists( 'IWJ_Class' ) && $show_button_login_user && $show_button_login_user != 'no' ) {
                                echo '<div class="iwj-author-mobile float-right">';
                                $disable_notification = iwj_option( 'disable_notification', array() );
                                $user = IWJ_User::get_user();
                                if ( $user ) {
                                    $dashboard_url = iwj_get_page_permalink( 'dashboard' );
                                    $tab           = isset( $_GET['iwj_tab'] ) ? $_GET['iwj_tab'] : '';
                                    if ( ! $tab ) {
                                        $tab = 'profile';
                                    }
                                    ?>
                                    <div class="author-login">
                                        <a href="<?php echo esc_url( $dashboard_url ); ?>">
                                            <span class="action-button author-avatar"><?php echo get_avatar( $user->get_id(), 32 ); ?></span>
                                        </a>
                                    </div>
                                <?php } else {
                                    echo '<span class="login-mobile">';
                                    echo '<a class="login action-button" href="' . esc_url( iwj_get_page_permalink( 'login' ) ) . '"><i class="fa fa-user"></i></a>';
                                    echo '</span>';
                                    if ( ! $disable_candidate_register || ! $disable_employer_register ) {
                                        echo '<span class="register-mobile">';
                                        echo '<a class="login action-button" href="' . esc_url( iwj_get_page_permalink( 'register' ) ) . '"><i class="fa fa-user-plus"></i></a>';
                                        echo '</span>';
                                    }
                                }
                                echo '</div>';
                            } ?>
                            <?php if ( class_exists( 'IWJ_Class' ) && $show_notification && $show_notification != 'no' ) {
                                $disable_notification = iwj_option( 'disable_notification', array() );
                                $user                 = IWJ_User::get_user();

                                if ( ( $user && ( ( $user->is_candidate() && ! in_array( 'candidate', $disable_notification ) ) || ( $user->is_employer() && ! in_array( 'employer', $disable_notification ) ) ) ) || ( ! is_user_logged_in() && ! in_array( 'guest', $disable_notification ) ) ) {
                                    ?>
                                    <div class="notification">
                                        <?php iwj_get_template_part( 'notification-menu', array() ); ?>
                                    </div>
                                <?php
                                }
                                ?>
                            <?php } ?>
                            <?php if ( ($cartTotal > 0) && $show_cart_button && $show_cart_button != 'no' ) { ?>
                                <div class="btn-search-cart mobile float-right">
                                    <div class="btn-wrap">
                                        <?php if ( function_exists( 'WC' ) && Inwave_Helper::getThemeOption( 'show_cart_button' ) ) { ?>
                                            <div class="cart-quickaccess">
                                                <a href="<?php echo esc_url( $cartUrl ); ?>" class="cart-icon">
                                                    <i class="ion-android-cart"></i>
                                                    <span class="cart-product-number"><?php echo (int) $cartTotal; ?></span>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="iw-menu-header-default float-right">
                        <nav class="main-menu iw-menu-main nav-collapse">
                            <?php get_template_part( 'blocks/menu' ); ?>
                        </nav>
                        <?php if ( ($show_search_form && $show_search_form != 'no') || ( function_exists( 'WC' ) && $show_cart_button && $show_cart_button != 'no' ) ) { ?>
                            <div class="btn-search-cart desktop float-right">
                                <div class="btn-wrap">
                                    <?php if ( $show_search_form && $show_search_form != 'no' ) { ?>
                                        <div class="search-form">
                                            <form class="search-form-header" method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>">
                                        <span class="search-wrap">
                                            <input type="search" title="<?php echo esc_attr_x( 'Search for:', 'label', 'injob' ) ?>" value="<?php echo get_search_query() ?>" name="s" placeholder="<?php echo esc_attr_x( 'Enter  key words...', 'placeholder', 'injob' ); ?>" class="top-search">
                                            <span class="icon"><i class="fa fa-search"></i></span>
                                        </span>
                                            </form>
                                        </div>
                                    <?php } ?>
                                    <?php if ( function_exists( 'WC' ) && $show_cart_button && $show_cart_button != 'no' ) { ?>
                                        <?php if ( $cartTotal > 0 ) { ?>
                                            <div class="cart-quickaccess">
                                                <a href="<?php echo esc_url( $cartUrl ); ?>" class="cart-icon">
                                                    <i class="ion-android-cart"></i>
                                                    <span class="cart-product-number"><?php echo (int) $cartTotal; ?></span>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ( class_exists( 'IWJ_Class' ) && $show_form_find_jobs == 'yes' && ! ( is_page( iwj_get_page_id( 'candidates' ) ) ) ) { ?>
            <div class="page-heading-search-2">
                <div class="container">
                    <?php iwj_get_template_part( 'parts/advanced_search', array( 'style' => 'style2' ) ); ?>
                </div>
            </div>
        <?php
        } elseif (class_exists( 'IWJ_Class' ) && $show_search_form_jobs && (is_tax(get_object_taxonomies( 'iwj_job' )) || is_singular( 'iwj_job' ))) { ?>
            <div class="page-heading-search-2 job-taxonomies <?php echo $search_form_jobs_style ? $search_form_jobs_style : '' ?>">
                <div class="container">
                    <?php if ($search_form_jobs_style == 'style1') {
                        echo do_shortcode( '[iwj_advanced_search style="'.$advanced_search_jobs_style.'"]' );
                    } elseif ($search_form_jobs_style == 'style2') {
                        echo do_shortcode( '[iwj_advanced_search_with_radius]' );
                    } else {
                        echo do_shortcode( '[iwj_find_jobs style="'.$find_jobs_style.'"]' );
                    } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<!--End Header-->