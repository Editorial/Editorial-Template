<style type="text/css">
#editorial p.note {
	font-size: 11px;
	color: #999;
}

#editorial p.karma {
	float: left;
}

#editorial input[type="text"] {
	width: 400px;
}

#editorial .logos img {
	border: 1px solid #DFDFDF;
	padding: 5px;
	margin: 5px;
}

#editorial .logos img.gallery {
	background: #000;
}

#editorial #authors li {
/*	width: 80%;*/
	padding: 15px;
	background: #efefef;
	-moz-border-radius: 5px;
	border-radius: 5px;
	border: 1px solid #bbb;
	/*min-height: 40px;*/
}

#editorial #authors li img {
	float: left;
	margin-right: 10px;
	/*margin-left: 10px;*/
}

#editorial #authors .handle {
	display: block;
	float: left;
	cursor: move;
	width: 15px;
	height: 17px;
	background: url(<?php echo get_bloginfo('template_directory'); ?>/images/handle.png) no-repeat;
	text-indent: -99999px;
	outline: none;
	margin-right: 10px;
	/*margin-top: 5px;*/
}

#editorial #authors input {
	float: left;
	margin: 4px 10px 0 0;
}

#editorial #authors input[type="text"] {
	margin-top: -3px;
	width: 150px;
}

#editorial input[name="karma-treshold"] {
	float: left;
	width: 40px;
	margin-right: 5px;
}

#poststuff #post-body.columns-2 {
	margin-right: inherit;
}

#post-body.columns-2 #postbox-container {
width: 65%;
}

#post-body.columns-2 #postbox-container-1 {
margin-right: 0;
width: 30%;
}

#normal-sortables .postbox .submit {
float: none;

}

.postbox .hndle { cursor: pointer; }

</style>
<div id="editorial" class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<?php include $this->_page.'.php'; ?>
</div>
<?php wp_enqueue_script('postbox'); ?>
<script>
jQuery(document).ready(function(){ 
	postboxes.add_postbox_toggles(pagenow); 
	jQuery('.meta-box-sortables').sortable({
        disabled: true
    });
});


</script>