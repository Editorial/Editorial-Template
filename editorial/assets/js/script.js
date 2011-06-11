
    if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i)) {
        var viewportmeta = document.querySelectorAll('meta[name="viewport"]')[0];
        if (viewportmeta) {
            viewportmeta.content = 'width=device-width, minimum-scale=1.0, maximum-scale=1.0';
            document.body.addEventListener('gesturestart',function() {
                viewportmeta.content = 'width=device-width, minimum-scale=0.25, maximum-scale=1.6';
            },false);
        }
    }

$(function(){

    //embed-code select
    $('#embed-code').click(function(){$(this).select();});


    //max-width IE6
    /*if ($.browser.msie && $.browser.version < 7) {
        function maxWidth() {
            if ($('body').width() > 960) {$('body').css('width','960px');}
        }
        $(window).resize(function(){maxWidth();});
    }*/

    // ajax comment post
    $('#comments-form').submit(function() {
        // use ajax to submit form
        dataString = 'name='+$('#name').val()+'&email='+$('#email').val()+'&url='+$('#url').val()+'&comment='+$('#comment').val()+'&riddle='+$('#riddle').val();
        $.ajax({
            type: 'POST',
            url: $('#comments-form').attr('action'),
            data: dataString,
            complete: function(msg) {
                var response = $.parseJSON(msg.responseText);
                if (response.errors) {
                    // add errors
                    $('#errors').remove();
                    $(response.html).insertBefore('#comments-form');
                    $('html,body').animate({scrollTop: $("#errors").offset().top},'slow');
                    // add error fields
                    for (error in response.errors) {
                        var id = response.errors[error];
                        $('#'+id).parent().addClass('error');
                    }
                }
                else {
                    // add new comment to html
                    // scroll to new comment
                    // reset form
                }
            }
        });
        return false;
    });
});

