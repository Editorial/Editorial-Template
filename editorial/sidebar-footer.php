<?php
/**
 * Footer sidebar
 *
 * @package    Editorial
 * @copyright  Copyright (c) 2011, ThirdFrameStudios
 * @author     Miha Hribar
 */

// check if we have any sidebars
$have = false;
for ($i = 1; $i <= EDITORIAL_WIDGET_COUNT; $i++)
{
	if (is_active_sidebar(sprintf('footer-widget-%d', $i))) $have = true;
}

if (!$have) return;

echo '<aside id="widgets">';

for ($i = 1; $i <= EDITORIAL_WIDGET_COUNT; $i++)
{
	$sidebar = sprintf('footer-widget-%d', $i);
	if (is_active_sidebar($sidebar))
	{
		dynamic_sidebar($sidebar);
	}
}

echo '</aside>';