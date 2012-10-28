<?php

$media_src = $_GET['src'];

?>
<html>
<head>
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">

   <style>
        body {
            width: 100%;
            height: 100%;
            background-color: black;
        }
        #media {
            width: 100%;
        }
    </style>

</head>
<body style="width:100%; height:100%; margin:0; padding:0;">
<!--   <iframe id="media" src="<?php echo $media_src; ?>" style="width:100%; height:100%; margin:0; padding:0;"></iframe> -->
<video id="video" src="<?php echo $media_src; ?>" controls autoplay width="100%" height="100%"></video>
<script>

      var video_dom = document.querySelector('#video');

      
      video_dom.addEventListener('canplaythrough', function(){
        //this.webkitRequestFullscreen();
      }, false);
      video_dom.addEventListener("loadedmetadata", function(){
        this.webkitEnterFullscreen();
      }, false);
      
      video_dom.addEventListener('pause', function() {
        history.back();
      }, false);
      video_dom.addEventListener('stop', function() {
        history.back();
      }, false);
      
      video_dom.addEventListener('ended', function() {
        history.back();
      }, false);

</script>
</body>
</html>