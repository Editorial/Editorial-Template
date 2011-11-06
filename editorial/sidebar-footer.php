<?php
/**
 * Footer sidebar
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, Editorial
 * @link       http://www.editorialtemplate.com
 * @author     Miha Hribar
 * @version    1.0
 */

// check if we have any sidebars
if (!is_active_sidebar(EDITORIAL_WIDGET))
{
	return;
}

echo '<div id="widgets" class="clear">

';
dynamic_sidebar(EDITORIAL_WIDGET);
echo '
</div>';