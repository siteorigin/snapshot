jQuery(function($){
    // Display the search
    $('#main-menu-search a').click(function(){
        $('#hidden-search').slideToggle(250);
        if($('#hidden-search').is(':visible')){
            $('#hidden-search #s').focus();
        }
        else{
            $('#hidden-search #s').blur();
        }
        return false;
    });
    if($('body').hasClass('search')) $('#hidden-search').show();
    
    // Hover effect for a post loop
    $('#post-loop .post .post-content').css('opacity', 0);
    $('#post-loop .post')
        .mouseenter(function(){
            var $$ = $(this);
            $$.find('.post-content').clearQueue().animate({'opacity': 1}, 300);
            $$.find('.corner.corner-se').clearQueue().animate({'bottom': -20, 'right' : -20}, 300);
        })
        .mouseleave(function(){
            var $$ = $(this);
            $$.find('.post-content').clearQueue().animate({'opacity': 0}, 300);
            $$.find('.corner.corner-se').clearQueue().animate({'bottom': 5, 'right' : 5}, 300);
        });
    
    // Dropdown hover pointer
    $('#menu-main-menu .sub-menu').append($('<div class="pointer"></div>')).wrap($('<div></div>').addClass('sub-menu-wrapper'));
});