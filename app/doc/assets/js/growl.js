/* ========================================================================
 * cui: ajax-get.js v0.0.1
 * http://cui.corethink.cn/
 * ========================================================================
 * Copyright 2015-2020 Corethink, Inc.
 * ======================================================================== */


+function ($) {
    'use strict';
    //ajax post submit请求
    $(document).delegate('.ajax-post', 'click', function() {
        var target, query, form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm = false;

        if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            form = $('.' + target_form);
            if ($(this).attr('hide-data') === 'true') { //无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            } else if (form.get(0) == undefined) {
                return false;
            } else if (form.get(0).nodeName == 'FORM') {
                if ($(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                if ($(this).attr('url') !== undefined) {
                    target = $(this).attr('url');
                } else {
                    target = form.get(0).action;
                }
                query = form.serialize();
            } else if (form.get(0).nodeName == 'INPUT' || form.get(0).nodeName == 'SELECT' || form.get(0).nodeName == 'TEXTAREA') {
                form.each(function(k, v) {
                    if (v.type == 'checkbox' && v.checked == true) {
                        nead_confirm = true;
                    }
                });
                if (nead_confirm && $(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                query = form.serialize();
            } else {
                if ($(this).hasClass('confirm')) {
                    if (!confirm('确认要执行该操作吗?')) {
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }

            $(that).addClass('disabled').attr('autocomplete', 'off').prop('disabled', true);
            $.post(target, query).success(function(data) {

                if (data.errorCode == 1) {
                    if (data.url && !$(that).hasClass('no-refresh')) {
                        var message = data.errorDesc + ' 页面即将自动跳转~';
                    } else {
                        var message = data.errorDesc;
                    }
                    $.alertMessager(message, 'success');
                    setTimeout(function() {
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
                    if($('.reload-verify').length > 0){
                        $('.reload-verify').click();
                    }
                }
            });
        }
        return false;
    });


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

    //jQuery弹窗提醒插件
    $.alertMessager = function(message, type, time) {
        type = type ? type : 'danger';
        var messager = '<div style="width:50%;height:auto;margin:0 auto;max-width: 90%;top:52px;left:0;right:0;z-index:99999;"'+
                         'class="messager navbar-fixed-top border-none alert alert-'+type+'"><button type="button" class="close" '+
                         'data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+message+'</div>';
        $('.messager').remove();
        $('body').prepend(messager);
        setTimeout(function(){
            $('.messager').remove();
        }, time ? time : 2000);
    };

}(jQuery);
