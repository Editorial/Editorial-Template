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
	width: 80%;
	padding: 10px;
	background: #efefef;
	-moz-border-radius: 5px;
	border-radius: 5px;
	border: 1px solid #bbb;
}

#editorial #authors .handle {
	display: block;
	float: left;
	cursor: move;
	width: 15px;
	height: 17px;
	background: url(<?php echo get_bloginfo('template_directory'); ?>/assets/images/handle.png) no-repeat;
	text-indent: -99999px;
	outline: none;
	margin-right: 10px;
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

</style>
<div id="editorial" class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<?php include $this->_page.'.php'; ?>
</div>