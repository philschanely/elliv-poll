$(document).ready(function(){
    $('.nominee-header').on('click', function() {
        if ($(window).width() < 767) {
                $(this).children('.dropdown-arrow').toggleClass('rotated');
                $(this).next().slideToggle('slow', function() {
                    // Animation complete.
                });
            } else {
                // console.log('More than 767');
                $(this).next('.nominee-description').addClass('show');
                $('.shade').addClass('show');
                $('.close').on('click', function () {
                    $('.nominee-description').removeClass('show');
                    $('.shade').removeClass('show');
                });
            }
        });
    $('.nominee-vote').on('click', function() {
        $(".select-circle").removeClass("select-circle");
        $(".select-nominee").removeClass("select-nominee");
        $(this).toggleClass('select-circle');
        $(this).parent().toggleClass('select-nominee');
    });
});