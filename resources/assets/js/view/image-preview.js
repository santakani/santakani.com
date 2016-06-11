/**
 * Image thumbnail used for image upload, select and management.
 * Bind to model Image.
 *
 * Class: ImagePreview
 */

var Backbone = require('backbone');

var Image = require('../model/image');
var tpl = require('../utility/template');

module.exports = Backbone.View.extend({

    tagName: 'div',

    className: 'image-preview',

    template: _.template(tpl.load('#image-preview-template')),

    width: 150,

    height: 150,

    size: 'thumb',

    removeable: false,

    selectable: false,

    multiple: false, // true: select like checkbox; false: select like radio button

    inputName: null,

    destroyOnRemove: false, // true: destroy model on server through ajax. false: only remove view.

    events: {
        'click .remove': 'close',
        'click': 'select'
    },

    initialize: function (options) {
        _.extend(this, _.pick(options, 'width', 'height', 'size', 'removeable',
            'selectable', 'multiple', 'inputName', 'destroyOnRemove'));

        if (!this.model) {
            this.model = new Image();
            this.model.readElement(this.el);
        }

        this.render();

        // Responsive size
        this.updateSize();
        var that = this;
        $(window).resize(function () {
            that.updateSize();
        });

        this.listenTo(this.model, 'change', this.update);
    },

    render: function () {
        this.$el.html(this.template(this.model.attributes));
        this.update();
        return this;
    },

    update: function () {
        if (this.removeable) {
            this.$('.remove').show();
        } else {
            this.$('.remove').hide();
        }

        this.updateImage();
        this.updateSize();
        this.updateSelect();
        this.updateProgress();

        if (this.inputName) {
            this.$('input').attr('name', this.inputName);
            this.$('input').val(this.model.get('id'));
        } else {
            this.$('input').removeAttr('name');
            this.$('input').val('');
        }
    },

    updateSize: function () {
        this.$el.css('width', this.width + 'px'); // max-width controlled by CSS.
        this.$el.css('height', this.$el.width() * this.height / this.width);
    },

    select: function () {
        if (this.multiple) {
            this.model.set({selected: !this.model.get('selected')});
        } else {
            this.model.set({selected: true});
            this.trigger('select', this);
        }
    },

    unselect: function () {
        this.model.set({selected: false});
    },

    /**
     * Update view based on select states.
     */
    updateSelect: function () {
        if (this.selectable && this.model.get('selected')) {
            this.$el.addClass('selected');
        } else {
            this.$el.removeClass('selected');
        }
    },

    updateProgress: function () {
        var progress = this.model.get('progress');
        if (progress === false) {
            this.$('.progress').hide();
        } else {
            this.$('.progress-bar').css('width', progress + '%');
            this.$('.progress').show();
        }
    },

    updateImage: function () {
        if (this.model.get('id') && this.model.get('mime_type')) {
            this.$el.css('background-image', 'url(' + this.model.fileUrl(this.size) +')');
        } else {
            this.$el.css('background-image', 'none');
        }
    },

    close: function () {
        if (this.destroyOnRemove) {
            this.model.destroy({
                data: {
                    _token: csrfToken
                },
                processData: true
            });
        }
        this.remove();
    }
});
