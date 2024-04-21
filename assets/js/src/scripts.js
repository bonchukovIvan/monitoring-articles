$(document).ready(function() {
    $('.more-btn').each(function() {
        $(this).click(function() {
            $(this).next('.item__more-section').toggleClass("open");
            $(this).find('.arrow').toggleClass("up down");
        });
    });
});