/* ========================================================================
 * cui: ajax-get.js v0.0.1
 * http://cui.corethink.cn/
 * ========================================================================
 * Copyright 2015-2020 Corethink, Inc.
 * ======================================================================== */


+function ($) {
    'use strict';

    //ajax get请求
    $(document).delegate('.ajax-get', 'click', function() {
        var target;
        var that = this;
        if ($(this).hasClass('confirm')) {
            if (!confirm('确认要执行该操作吗?')) {
                return false;
            }
        }
        if ((target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            $(this).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
            $.get(target).success(function(data) {
                if (data.errorCode == 1) {
                    if (data.url && !$(that).hasClass('no-refresh')) {
                        var message = data.errorDesc + ' 页面即将自动跳转~';
                    } else {
                        var message = data.errorDesc;
                    }
                    $.alertMessager(message, 'success');
                    setTimeout(function() {
                        $(that).removeClass('disabled').prop('disabled', false);
                        if ($(that).hasClass('no-refresh')) {
                            return false;
                        }
                        if (data.url && !$(that).hasClass('no-forward')) {
                            location.href = data.url;
                        } else {
                            location.reload();
                        }
                    }, 2000);
                } else {
                        $.alertMessager(data.errorDesc, 'danger');
                    setTimeout(function() {
                        $(that).removeClass('disabled').prop('disabled', false);
                    }, 2000);
                }
            });
        }
        return false;
    });

}(jQuery);
