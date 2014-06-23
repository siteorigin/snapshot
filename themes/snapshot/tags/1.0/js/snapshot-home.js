jQuery(function($){
    // Resize the main home slider image
    $('#home-slider img.slide').load(function(){
        var $$ = $(this);
        if($$.height() < 420){
            $$.css({
                'height' : 420,
                'width' : 'auto'
            });
        }

        else if($$.width() < $('#home-slider').width()){
            $$.css({
                'height' : 'auto',
                'width' : '100%'
            });
        }

        $$.css('margin-top', -$$.height()/2);
    });
    
    // This handles resizing the window
    $(window).resize(function(){
        $('#home-slider img.slide.current').load();
    })

    // Display a slide
    var displaySlide = function(i){
        if(i < 0 || i >= $('#home-slider img.slide').length){
            i = i % $('#home-slider img.slide').length;
        }

        var c = $('#home-slider img.slide.current').index('#home-slider img.slide');
        if(c != -1){
            // Hide the slide
            $('#home-slider img.slide').eq(c)
                .add($('#home-slider .post-titles a').eq(c))
                .clearQueue().animate({'opacity' : 0}, 600, function(){
                    $(this).hide();
                });
        }
        $('#home-slider img.slide, #home-slider .post-titles a').removeClass('current');

        
        // Show the new slide
        $('#home-slider img.slide').eq(i).load()
            .add($('#home-slider .post-titles a').eq(i))
            .addClass('current').show().css('opacity', 0).clearQueue().animate({'opacity' : 1}, 600);
    }
    
    var assets = [];
    $('#home-slider img.slide').each(function(){
        assets.push({'type' : 'image', 'url' : $(this).attr('src')});
    });
    $.preload(assets, {
        complete: function(){
            $('#home-slider').removeClass('loading');
            displaySlide(0);

            // Temporary slide transition
            var cc = 0;
            var interval;

            var resetInterval = function(){
                clearInterval(interval);
                interval = setInterval(function(){
                    displaySlide(++cc);
                }, snapshotHome.sliderSpeed);
            };
            resetInterval();

            $('#home-slider a.next').click(function(){
                displaySlide(++cc);
                resetInterval();
                return false;
            });

            $('#home-slider a.previous').click(function(){
                displaySlide(--cc);
                resetInterval();
                return false;
            });
        }
    });
})