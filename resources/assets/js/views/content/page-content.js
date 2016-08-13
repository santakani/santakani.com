var Backbone = require('backbone');

module.exports = Backbone.View.extend({
    el: '#page-content',

    initialize: function () {
        this.$el.lightGallery({
            selector: this.$el.find('a > img').parent(),
            download: false,
        });
    }
});
