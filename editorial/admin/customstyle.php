<?php


$has_child = Editorial::getOption( 'child-theme' );

var_dump( $has_child );

?>


<h2><?php _e('Editorial', 'Editorial'); ?> &mdash; <?php _e('Customize', 'Editorial'); ?></h2>

<?php
if ( !$has_child ) :
?>

<p>
  To preserve changes you make to the theme, it is advised to make them in a child theme. 
  If you make changes to the Editorail style.css instead, all changes will be lost once you upgrade them theme.
</p>
  
<form action="admin.php?page=editorial-customstyle" method="POST">
  <input type="hidden" name="create-theme" value="1" />
  <p class="submit">
      <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Create Child Theme', 'Editorial'); ?>">
  </p>
</form>

<?php
else: 
?>

We have a child theme!

<?php
endif;
?>

