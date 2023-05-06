$('.module').on( "mouseenter", function (e) {
    console.log($(this));
    $(this).toggleClass('list-group-item-primary');
} ).on( "mouseleave", function () {
    $(this).toggleClass('list-group-item-primary');
} );