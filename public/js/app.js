$(function () {

    var $grid = $('#story-list .grid')

    if ($grid.length === 0) {
        return;
    }

    $grid.masonry({
        itemSelector: '.grid-item', // use a separate class for itemSelector, other than .col-
        columnWidth: '.grid-item',
        percentPosition: true
    });

    $grid.find('.story .expand-button').click(function () {
        $(this).parent('.story').toggleClass('expanded');
        $(this).siblings('.content').scrollTop(0);
        $grid.masonry();
    });
});

var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('place-map-inner'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 8
  });
}

 

//# sourceMappingURL=app.js.map