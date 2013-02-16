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
                var faq = jQuery('#editorial-faq');
                faq.empty();
                jQuery.each(data[editorialGroup], function(key, val) {
                    var txt = '<h4>'+key+'</h4><ol>';
                    jQuery.each(val, function(key, val) {
                        txt += '<li><a href="'+val.href+'" target="_blank">'+val.title+'</a></li>'
                    });
                    txt += '</ol>';
                    faq.append(txt);
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