<div id="postbox-container-1" class="postbox-container">
<div id="side-sortables" class="meta-box-sortables ui-sortable">

<div class="postbox " style="display: block; ">
  <div class="handlediv" title="Click to toggle"><br></div>
  <h3 class="hndle"><span><?php _e('Help & Support', 'Editorial'); ?></span></h3>
  <div class="inside">
    <div class="table table_content" id="editorial-faq">

        <p>Loading ...</p>
        <script type="text/javascript">
        <?php printf('var editorialGroup = "%s";', $faqGroup); ?>
        jQuery(function(){
            // fetch the specified group from editorialtemplate.com -> need to use a bridge to load it
            jQuery.getJSON('<?php echo get_bloginfo('template_directory').'/admin/load-faq.php'; ?>', function(data) {
                /*var faq = jQuery(data);
                var group = faq.find('.group').eq(editorialGroup);
                var questions = group.find('.questions');
                questions.find('a').each(function() {
                    jQuery(this).attr('href', 'http://editorialtemplate.com'+jQuery(this).attr('href'));
                    jQuery(this).attr('target', '_blank');
                });
                jQuery('#editorial-faq').empty().append(questions);*/
                jQuery('#editorial-faq').empty();
                jQuery.each(data[editorialGroup], function(key, val) {
                    jQuery('#editorial-faq').append('<p><a href="'+val.href+'" target="_blank">'+val.title+'</a></p>');
                });
            })
            .fail(function(e) {
                jQuery('#editorial-faq').empty();
            });
        });
        </script>

    </div>
  </div>
 </div>

</div>
</div>