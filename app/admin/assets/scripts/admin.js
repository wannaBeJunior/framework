$('.module').on( "mouseenter", function (e) {
    $(this).toggleClass('list-group-item-primary');
} ).on( "mouseleave", function () {
    $(this).toggleClass('list-group-item-primary');
} );