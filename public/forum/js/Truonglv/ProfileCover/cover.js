!function ($, window, document, _undefined) {
    "use strict";

    XF.ProfileCover = XF.Element.newHandler({
        options: {
            cropper: {
                rotatable: false,
                zoomable: false,
                zoomOnTouch: false,
                zoomOnWheel: false,
                responsive: true,
                viewMode: 3,
                dragMode: 'move',
                autoCropArea: 1,
                guides: false,
                modal: false,
                cropBoxMovable: true,
                cropBoxResizable: false
            }
        },

        init: function () {
            this.$image = this.$target.find('.profileCover--img');

            var minWidth = Math.max(this.$target.data('min-width'), this.$target.height()),
                minHeight = this.$target.data('min-height');

            this.onRepositioning = false;
            if (this.$target.hasClass('reposition') && this.$image.length > 0) {
                var options = $.extend({
                    minContainerWidth: minWidth,
                    minContainerHeight: minHeight,
                    minCanvasWidth: minWidth,
                    minCanvasHeight: minHeight,

                    minCropBoxWidth: minWidth,
                    minCropBoxHeight: minHeight
                }, this.options.cropper);

                this.$image.cropper(options);

                this.$target.find('.profileCover--save').on('click', $.proxy(this, 'saveReposition'));
                this.onRepositioning = true;
            }

            this.lastWidth = 0;
            this.onResizeHandler();
            $(window).onPassive('resize', $.proxy(this, 'onResizeHandler'));
        },

        onResizeHandler: function (ev) {
            if (this.lastWidth == this.$target.width()
                || this.onRepositioning
            ) {
                return;
            }

            this.lastWidth = this.$target.width();
            this.$target.find('.profileCover-inner').height(this.$image.height());
        },

        saveReposition: function (ev) {
            ev.preventDefault();

            var $button = $(ev.currentTarget);
            $button.prop('disabled', true).addClass('disabled');

            function saveResponseHandler(ajaxData) {
                $button.prop('disabled', false).removeClass('disabled');

                XF.redirect(ajaxData.redirect);
            }

            this.$image.cropper('getCroppedCanvas', {
                width: this.$target.data('min-width'),
                height: this.$target.data('min-height'),
                imageSmoothingEnabled: false
            }).toBlob(function (blob) {
                var formData = new FormData();

                formData.append('cover', blob);
                formData.append('reposition', 1);

                XF.ajax('post', $button.data('url'), formData, saveResponseHandler);
            });
        }
    });

    XF.Element.register('profileCover', 'XF.ProfileCover');
}
(jQuery, this, document);