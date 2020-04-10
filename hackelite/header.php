<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package injob
 */
$header_layout = Inwave_Helper::getPostOption('header_option' , 'header_layout');
$favicon = Inwave_Helper::getPostOption('favicon', 'favicon');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php echo esc_url(get_bloginfo('pingback_url')); ?>">
    <?php wp_head(); ?>
</head>
<body id="page-top" <?php body_class(); ?>>

<?php
get_template_part('blocks/canvas', 'menu');
?>

<?php
$show_preload = Inwave_Helper::getPostOption('show_preload' , 'show_preload');
if($show_preload && $show_preload != 'no'){
    echo '<div id="preview-area">
        <div id="preview-spinners">
            <div class="sk-chasing-dots">
                <div class="sk-child sk-dot1"></div>
                <div class="sk-child sk-dot2"></div>
              </div>
        </div>
    </div>';
}
?>

<div class="wrapper">
    <div class="iw-overlay"></div>
    <?php
        $header_layout = Inwave_Helper::getPostOption('header_option' , 'header_layout');
        if(!$header_layout){
            $header_layout = 'default';
        }

        if($header_layout != 'none'){
            get_template_part('headers/header', $header_layout);
        }
    ?>
    <?php
    if(function_exists('putRevSlider') && (is_single() || is_singular() || is_page())){
        $slider = Inwave_Helper::getPostOption('slider');
        if($slider){
            ?>
            <div class="slide-container <?php echo esc_attr($slider)?>">
                <?php putRevSlider($slider); ?>
            </div>
            <?php
        }
    }
    ?>
    <?php

    $show_page_heading = Inwave_Helper::getPostOption('show_pageheading', 'show_page_heading');
    $show = false;
    if (!is_page_template( 'page-templates/home-page.php' ) && !is_404() ) {
        $show = true;
    }

	if(function_exists('iwj_option')){
		$candidate = IWJ_Candidate::get_candidate(get_the_ID());
		$candidate_details_version      = iwj_option( 'candidate_details_version', 'v1' );
        $template_c_detail_version = '';
        if ($candidate) {
            $template_c_detail_version           = $candidate->get_template_detail_version();
        }
		$details_versions = $template_c_detail_version ? $template_c_detail_version : $candidate_details_version;

		if ((is_singular( 'iwj_candidate' ) && $details_versions == 'v2')) {
			$show = false;
		}

		$employer = IWJ_Employer::get_employer( get_the_ID() );
		$employer_details_version  = iwj_option( 'employer_details_version', 'v1' );
        $template_e_detail_version = '';
        if ($employer) {
            $template_e_detail_version = $employer->get_template_detail_version();
        }
		$employer_details_versions = $template_e_detail_version ? $template_e_detail_version : $employer_details_version;

		if ((is_singular( 'iwj_employer' ) && $employer_details_versions == 'v2')) {
			$show = false;
		}

		$job = IWJ_Job::get_job( get_the_ID() );
		$job_details_version  = iwj_option( 'job_details_version', 'v1' );
        $template_j_detail_version = '';
        if ($job) {
            $template_j_detail_version = $job->get_template_detail_version();
        }
		$job_details_versions = $template_j_detail_version ? $template_j_detail_version : $job_details_version;

		if ((is_singular( 'iwj_job' ) && $job_details_versions == 'v2')) {
			$show = false;
		}
	}

	if($show){
		get_template_part('blocks/page', 'heading');
	}
    ?>