jQuery(function($){
    $('#post-images .image a').click(function(){
        var $$ = $(this);
        if(!$('#post-single-viewer').length) return true;
        
        var v = $('#post-single-viewer');
        v.find('img').attr({
            'src' : $$.attr('data-src'),
            'width' : $$.attr('data-width'),
            'height' : $$.attr('data-height')
        });
        
        $('html, body').animate({'scrollTop' : $('#page-title').offset().top}, 'fast');
        
        return false;
    })
});