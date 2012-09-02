<?php


$has_child = Editorial::getOption( 'child-theme' );
$current_theme = wp_get_theme();


if($has_child){
  $theme_root = get_theme_root();
  //ATTENTION, This is hardcoded and it is assuming the child theme is in dir editorial-child
  $style_path = $theme_root.'/editorial-child/style.css';
}


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

  <?php
  if ( $current_theme['Name'] == 'Editorial' ) :
  ?>
    Please activate the child theme for the customization to work.
  <?php
  endif;
  ?>

  <form action="" method="post">

    <label>style.css</label>

    <br/>
    <textarea name="child-style-update" style="width:80%" rows="26"><?php echo file_get_contents($style_path); ?></textarea>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'Editorial'); ?>"></p>

  </form>

<?php
endif;
?>

