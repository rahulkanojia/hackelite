<?php

/**
 * InJob functions and definitions
 *
 * @package injob
 */
/* Theme option framework */
require_once get_template_directory() . '/framework/theme-option.php';
require_once get_template_directory() . '/framework/option-framework/inof.php';

/* Require helper function */
require_once get_template_directory() . '/framework/inc/helper.php';

/* Require importer function */
require_once get_template_directory() . '/framework/importer/importer.php';

/* Custom nav */
require_once get_template_directory() . '/framework/inc/custom-nav.php';

/* Customizer theme */
require_once get_template_directory() . '/framework/inc/customizer.php';

/* Require custom widgets */
require_once get_template_directory() . '/framework/widgets/info-footer.php';
require_once get_template_directory() . '/framework/widgets/subscribe.php';
require_once get_template_directory() . '/framework/widgets/recent-comment.php';
require_once get_template_directory() . '/framework/widgets/recent-post.php';

/* TGM plugin activation. */
require_once get_template_directory() . '/framework/inc/class-tgm-plugin-activation.php';

/* Plugins */
require_once get_template_directory() . '/framework/theme-plugin-load.php';

/* Theme Options */
require_once get_template_directory() . '/framework/theme-function.php';

/* Post, page metabox */
require_once get_template_directory() . '/framework/theme-metabox.php';

/* Theme Register */
require_once get_template_directory() . '/framework/theme-register.php';

/* Theme Support */
require_once get_template_directory() . '/framework/theme-support.php';

/* Theme enqueue_scripts */
require_once get_template_directory() . '/framework/theme-style-script.php';
