<?php

// add cache expires headers so the request is not always made
header("Cache-Control: must-revalidate");
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + 3600*24*2) . " GMT";
header($ExpStr);
// fetch editorial faq page
echo file_get_contents('http://editorialtemplate.com/wp-content/themes/marketing/faq.json');