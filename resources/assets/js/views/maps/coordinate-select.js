var Backbone = require('backbone');
var Leaflet = require('leaflet');

module.exports = Backbone.View.extend({

    el: '#coordinate-select',

    zoom: 14,

    events: {
        'click .lookup-button': 'lookup',
    },

    /**
     * @param {object} options
     * @param {CitySelect} options.citySelect
     * @param {jQuery} options.addressInput
     */
    initialize: function (options) {
        _.extend(this, _.pick(options, 'citySelect', 'addressInput'));
        _.bindAll(this, 'getCenter', 'setCenter', 'lookup', 'lookupTimer');

        // Initinalize map
        this.map = Leaflet.map(this.$('.map')[0], {
            scrollWheelZoom: false,
            attributionControl: false,
        });

        this.tile = Leaflet.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2FudGFrYW5pIiwiYSI6ImNpcW02em1lZzAwMWpoeW5tdmRiOHh4MTcifQ.sE-MLInkW3KwjlwoaaKuAQ', {
            attribution: '© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
            detectRetina: true,
        }).addTo(this.map);

        var latitude = parseFloat(this.$('input[name="latitude"]').val());
        var longitude = parseFloat(this.$('input[name="longitude"]').val());

        // When non coordinate
        if (!latitude || !longitude) {
            // Insert city coordinate
            if (this.citySelect && this.citySelect.selectedData()) {
                latitude = this.citySelect.selectedData().latitude;
                longitude = this.citySelect.selectedData().longitude;
            } else {
                // Default coordinate is Helsinki
                latitude = 60.167987;
                longitude = 24.942398;
            }
            // Send search request, wait for coordinates
            this.lookup();
        }
        this.setCenter(latitude, longitude);

        this.map.on('moveend', this.getCenter);

        // Update coordinate when changing address and city
        this.addressInput[0].oninput = this.lookupTimer;
        this.citySelect.selectize.on("item_add", this.lookup);
    },

    // Read coordinate when map center changed by user drag or setCenter()
    getCenter: function () {
        var center = this.map.getCenter();
        this.setCoordinate(center.lat, center.lng);
    },

    setCenter: function (latitude, longitude) {
        if (latitude && longitude) {
            this.map.setView([latitude, longitude], 14);
            this.setCoordinate(latitude, longitude);
        }
    },

    setCoordinate: function (latitude, longitude) {
        this.latitude = Math.round(latitude*1000000)/1000000;
        this.longitude = Math.round(longitude*1000000)/1000000;
        this.$('input[name="latitude"]').val(this.latitude);
        this.$('input[name="longitude"]').val(this.longitude);
        this.$('.latitude').text(this.latitude);
        this.$('.longitude').text(this.longitude);
    },

    openFoundAlert: function () {
        this.$('.alert-success').show();
        var that = this;
        setTimeout(function () {
            that.$('.alert-success').fadeOut();
        }, 2000);
    },

    openNotFoundAlert: function () {
        this.$('.alert-warning').show();
        var that = this;
        setTimeout(function () {
            that.$('.alert-warning').fadeOut();
        }, 2000);
    },

    search: function (query, alert) {
        var url = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&json_callback=?';
        var that = this;
        $.getJSON(url, function(data) {
            if (data.length > 0) {
                that.setCenter(parseFloat(data[0].lat), parseFloat(data[0].lon));
                if (alert) {
                    that.openFoundAlert();
                }
            } else {
                if (alert) {
                    that.openNotFoundAlert();
                }
            }
        });
    },

    lookup: function () {
        if (!this.addressInput || !this.addressInput.val().trim()) {
            return;
        }

        if (!this.citySelect || !this.citySelect.selectedData()) {
            return;
        }

        var query = this.addressInput.val().trim() + ', ' + this.citySelect.selectedData().english_full_name;

        this.search(query, true);
    },

    lookupTimer: function () {
        clearTimeout(this.lookupTimeout);
        this.lookupTimeout = setTimeout(this.lookup, 500);
    },
});
