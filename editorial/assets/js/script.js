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

    //bad-comment
    if($('blockquote.bad-comment').length > 0) {
        var b = 'blockquote.bad-comment>p';
        var s = 'p.show>a';
        $(b).hide();
        $(s + '>span').text('Show hidden');
        $(s).click(function(e){
            e.preventDefault();
            if($(b).css('display') == 'block') {
                $(b).fadeOut('fast');
                $(s + '>span').text('Show hidden');
            }
            else {
                $(b).fadeIn('fast');
                $(s + '>span').text('Hide shown');
            }
        })
    }

    // ajax comment post
    $('#comments-form').submit(function() {
        // use ajax to submit form
        dataString = 'name='+$('#name').val()+'&email='+$('#email').val()+'&url='+$('#url').val()+'&comment='+$('#comment').val()+'&riddle='+$('#riddle').val()+'&comment_post_ID='+$('#comment_post_ID').val();
        $.ajax({
            type: 'POST',
            url: $('#comments-form').attr('action'),
            data: dataString,
            complete: function(msg) {
                var response = $.parseJSON(msg.responseText);

                // remove errors notice, if present
                $('#errors').remove();

                if (response.errors) {
                    // show errors
                    $(response.html).insertBefore('#comments-form');
                    $('html,body').animate({scrollTop: $("#errors").offset().top},'slow');
                    // add error fields
                    for (error in response.errors) {
                        var id = response.errors[error];
                        $('#'+id).parent().addClass('error');
                    }
                }
                else {
                    // add success notice
                    $(response.success).insertBefore('#comments-form');
                    // add new comment to html
                    if ($('#comments').length > 0) {
                        // add to list
                        $(response.html).insertBefore('#comments article:first-child');
                    }
                    else {
                        // replace no comments notice & add comment
                        $('#single .notice').html(response.notice)
                                            .after('<section id="comments">'+response.html+'</section>');
                    }

                    // scroll to new comment
                    $('html,body').animate({scrollTop: $("#success").offset().top},'slow');

                    // reset form
                    $('#comment').val('');
                    $('#name').val('');
                    $('#email').val('');
                    $('#url').val('');
                    $('#riddle').val('');
                }

                // set new riddle
                $('#comments-form .captcha label[for="riddle"]').html(response.riddle.notice);
                $('#comments-form .qa span').html(response.riddle.riddle);
                // reset riddle
                $('#riddle').val('');
            }
        });
        return false;
    });
});

