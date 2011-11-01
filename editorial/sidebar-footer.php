<?php
/**
 * Footer sidebar
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// check if we have any sidebars
if (!is_active_sidebar(EDITORIAL_WIDGET))
{
	return;
}

echo '<aside id="widgets">';
dynamic_sidebar(EDITORIAL_WIDGET);
echo '</aside>';