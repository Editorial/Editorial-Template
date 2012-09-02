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
  A child theme allows you to safely customize Editorial default style. Even when the parent theme is updated, your custom changes will not be overwritten.
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
    <div class='updated fade'><p>Please activate the child theme <b>Editorial Custom</b> from the <a href="<?php echo get_bloginfo('url'); ?>/wp-admin/themes.php">Appearance/Themes</a> for the customization to work.</p></div>
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

