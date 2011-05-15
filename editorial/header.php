<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php bloginfo('name'); ?><?php wp_title('&ndash;'); ?></title>
<link rel="shortcut icon" href="<?php echo get_bloginfo('template_directory'); ?>/assets/images/ico/favicon.ico" type="image/x-icon" />
<meta name="description" content="<?php echo get_bloginfo('description'); ?>" />
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/assets/css/main.css" type="text/css" />
<link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/assets/css/print.css" type="text/css" media="print" />
<?php add_theme_support('automatic-feed-links'); ?>
<?php wp_head(); ?>