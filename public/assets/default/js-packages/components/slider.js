$(function() {
    if ($('.slider').length > 0) {
        $.each($('.slider'), function () {
            var id = $(this).attr('id'),
                pagination = $(this).attr('data-pagination'),
                navigation = $(this).attr('data-navigation');
            $(this).find('.slider__item').first().addClass('slider__item--active');
            // navigation
            if (navigation == 'true') {
                var navigationHTML = '<div class="slider__navigation slider__navigation--prev" data-for="'+ id +'"></div>';
                    navigationHTML += '<div class="slider__navigation slider__navigation--next" data-for="'+ id +'"></div>';
                $(this).append(navigationHTML);
            }
            // pagination
            if (pagination == "true") {
                var paginationHTML = '',
                    clss = '';
                $(this).append('<ol class="slider__pagination"></ol>');
                $.each($('.slider__item'), function (i) {
                    i == 0 ? clss = 'slider__bullet--active' : clss = '';
                    //paginationHTML += '<li class="slider__bullet '+ clss +'" data-for="'+ id +'"></li>';
                    paginationHTML += '<li class="slider__bullet '+ clss +'"></li>';
                });
                $(this).find('.slider__pagination').append(paginationHTML);
            }
        });
        // pagination click event
        /*$('.slider__bullet').on('click', function(){
            var id = $(this).attr('data-for'),
                index = $(this).index();
            mySwipe.slide(index, 400);
            sliderProperties(id,index);

        });*/

        $('.slider__navigation').on('click', function(){
            if ($(this).hasClass('slider__navigation--next')) {
                mySwipe.next();
            } else {
                mySwipe.prev();
            }
        });
        function sliderProperties(id, index) {
            // remove navigation disabled attribute
            $('#'+id).find($('.slider__navigation').removeClass('slider__navigation--disabled'));
            // active item
            $('#'+id).find($('.slider__item').removeClass('slider__item--active').eq(index).addClass('slider__item--active'));
            // change bullet
            $('#'+id).find($('.slider__pagination').find('li').removeClass('slider__bullet--active').eq(index).addClass('slider__bullet--active'));
        }
        window.mySwipe = new Swipe(document.querySelector('.slider'), {
            startSlide: 0,
            speed: 400,
            auto: 3000,
            continuous: true,
            disableScroll: false,
            stopPropagation: false,
            callback: function(index, elem) {
                var id = elem.closest('.slider').getAttribute('id');
                sliderProperties(id, index);
            },
            transitionEnd: function(index, elem) {}
        });
    }
});