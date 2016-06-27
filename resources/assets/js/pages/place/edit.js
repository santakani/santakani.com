/**
 * Control designer edit form.
 *
 * View - views/designer/edit.blade.php
 * Style - assets/sass/_edit-layout.scss
 * Script - assets/js/edit/ajax/designer-edit.js
 */

var ImagePreview = require('../../views/image-preview');
var Image = require('../../models/image');
var ImageManager = require('../../views/image-manager');
var ContentEditor = require('../../views/content-editor');
var GalleryEditor = require('../../views/gallery-editor');
var CitySelect = require('../../views/city-select');
var TagSelect = require('../../views/tag-select');
var CoordinateSelect = require('../../views/coordinate-select');

$(function () {

    // Page check
    if ($('#place-edit-page').length === 0) {
        return;
    }

    // Image manager
    var manager = new ImageManager({
        parentType: 'place',
        parentId: parseInt($('form').data('id'))
    });

    // Cover
    var coverPreview = new ImagePreview({
        el: '#image-form-group .image-preview',
        width: 600,
        height: 200,
        size: 'medium',
        inputName: 'image_id',
    });

    $('#image-form-group button').click(function () {
        manager.call({
            multiple: false,
            done: function (image) {
                coverPreview.model.set(image.attributes);
            }
        });
    });

    var contentEditor = new ContentEditor({el: '#input-content', imageManager: manager});

    var galleryEditor = new GalleryEditor({el: '#gallery-editor', imageManager: manager});

    var citySelect = new CitySelect({el: '.city-select'});

    var coordinateSelect = new CoordinateSelect();



    //====================================================================
    // Update coordinate when changing address
    //====================================================================

    var searchTimeout;

    function searchCoordinateTimer () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(searchCoordinate, 500);
    }

    function searchCoordinate() {
        var address = $('#address-input').val().trim();
        var city = $('#city-select option').text().trim();
        if (address.length > 0 && city.length > 0) {
            var query = address + ', ' + city;
            coordinateSelect.search(query);
        }
    }

    if (!coordinateSelect.latitude || !coordinateSelect.longitude) {
        searchCoordinate();
    }

    $('#address-input')[0].oninput = searchCoordinateTimer;
    $('#city-select')[0].onchange = searchCoordinate;
    $('#search-coordinate-button')[0].onclick = searchCoordinate;


    var tagSelect = new TagSelect({el: '.tag-select'});

    // Submit form
    $('button[type="submit"]').click(function (e) {
        e.preventDefault();

        $.ajax({
            method: 'PUT',
            url: $('#place-edit-form').attr('action'),
            data: $('#place-edit-form').serializeArray()
        }).done(function () {
            window.location.href = $('#place-edit-form').attr('action');
        }).fail(function (error) {
            var response = error.responseJSON;
            var $alert = $('#place-edit-form .alert');
            var message = '';

            for (var id in response) {
                message += '<p>' + response[id] + '</p>';
            }

            $alert.html(message).show().scrollTo();
        });
    });

});