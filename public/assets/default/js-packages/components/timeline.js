(function ($, window, document) {

    var Timeline = {

        init : function (options, $el) {
            var base = this;

            base.$elem = $el;
            base.className = 'content-timeline__item';
            base.$onItem = $('.' + base.className + ':in-viewport('+base.treshold+')');

            base.loadStatus = true;
            base.page = 1;
            base.perpage = 10;
            base.beforeLoad = 0;

            base.options = $.extend({}, $.fn.wtTimeline.options, base.$elem.data(), options);

            base.$header = $(base.options.header);
            base.treshold = base.$header.height();
            base.$filter = $(base.options.filter);
            base.$order = $(base.options.order);
            base.filterType = base.options.filterType;
            base.limit = base.options.limit;
            base.$container = $(base.options.container);
            base.$spinner = $(base.options.spinner);
            base.$loadMore = $(base.options.loadMore);
            base.path = base.options.path;

            //base.isActive();
            base.onScroll();
            if (base.options.pagination == null) {
                base.onLoadMore();
            }

            if (base.options.filter) {
                base.onChangeFilter();
            }

            if (base.options.order) {
                base.onChangeOrder();
            }
        },

        onScroll : function () {
            var base = this;

            $(window).on('scroll', function () {
                //base.isActive();
                if( base.options.pagination == null &&  ($(this).scrollTop() + $(this).height()) >= (base.$container.height() - base.beforeLoad) ) {
                    if (base.page <= base.limit && base.loadStatus == true) {
                        base.page++;
                        base.loadStatus = false;

                        base.getFilter();
                        base.loading(true);
                        base.loadContent();
                    }
                }
            });

        },

        onChangeFilter : function () {
            var base = this;

            base.$filter.on('change', function() {
                base.getFilter();

                if (base.options.pagination) {
                    base.urlBuilder('filter_type');
                } else {
                    base.$loadMore.hide();
                    base.loading(true);
                    base.page = 1;
                    base.loadStatus = false;
                    base.loadContent();
                    base.$container.addClass('is-hide');
                }
            });

        },

        onChangeOrder : function () {
            var base = this;

            base.$order.on('change', function() {
                base.getOrder();

                if (base.options.pagination) {
                    base.urlBuilder('order');
                } else {
                    base.$loadMore.hide();
                    base.loading(true);
                    base.page = 1;
                    base.loadStatus = false;
                    base.loadContent();
                    base.$container.addClass('is-hide');
                }
            });

        },

        onLoadMore : function () {
            var base = this;

            base.$loadMore.on('click', function(){
                base.show = false;
                base.page++;
                base.getFilter()
                base.loadContent();
                base.$loadMore.hide();
                base.loading(true);
            });
        },

        loadContent : function () {
            var base = this;

            $.ajax({
                type: "GET",
                url: base.path,
                data: {filter_type: base.filterType, page: base.page, perpage: base.perpage},
                dataType: "HTML",
                success: function(data) {
                    if (data) {
                        base.$container.removeClass('is-hide');
                        base.page > 1 ? base.$container.append(data) : base.$container.html(data);
                        base.loadStatus = true;
                        base.loading(false);
                        //base.isActive();
                        $(".lazy").lazyload({
                            threshold : 200,
                            failure_limit : 20
                        }).removeClass('lazy').addClass('lazyloaded');
                        //$(".lazy").lazyload({ threshold : 300, failure_limit : 20 }).removeClass('lazy').addClass('lazyloaded');
                        if (base.page > base.limit) {
                            base.$loadMore.show();
                        }

                    } else {
                        base.loadStatus = false;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        },

        loading : function (_status) {
            var base = this;

            if (_status == true) {
                base.$spinner.show();
            } else {
                base.$spinner.hide();
            }
        },

        /*isActive : function () {
            var base = this;

            base.treshold = base.$header.height();
            base.$onItem = $('.' + base.className + ':in-viewport('+base.treshold+')');
            base.$onItem.addClass('is-active');
        },*/

        getFilter : function () {
            var base = this;
            base.filterType = base.$filter.val();
        },

        getOrder : function () {
            var base = this;
            base.order = base.$order.val();
        },

        urlBuilder : function (_parameterType) {
            var base = this;

            var parser = document.createElement('a');
                parser.href = window.location.href;

            var url = parser.origin + parser.pathname;
            var query = parser.search.replace('?', '').split('&');

            if (query.length > 0 && query[0] != '') {
                var parameters = {};
                query.forEach(function(_param, i) {
                    var tmp = _param.split('=');
                    var key = tmp[0];
                    var value = tmp[1];

                    if (key == 'q') {
                        parameters[key] = decodeURIComponent(value.replace(/\+/g,' '));
                    }
                    if (key == 'filter_type') {
                        parameters[key] = typeof base.filterType !== 'undefined' ? (base.filterType) : (value);
                    }
                    if (key == 'order') {
                        parameters[key] = typeof base.order !== 'undefined' ? (base.order) : (value);
                    }
                });

                parameters = '?' + jQuery.param(parameters);

                if (parameters.indexOf(_parameterType) <= -1) {
                    if (_parameterType == 'filter_type') {
                        parameters += '&filter_type=' + base.filterType;
                    } else if (_parameterType == 'order') {
                        parameters += '&order=' + base.order;
                    }
                }

                url += parameters;

            } else {
                parameters = typeof base.filterType !== 'undefined' ? '?filter_type=' + (base.filterType) : '?order=' + (base.order);
                url += parameters;
            }

            window.location.href = url;

        }
    };

    $.fn.wtTimeline = function (options) {
        var timeline = Object.create(Timeline);
        timeline.init(options, $(this));
    };


}(jQuery, window, document));