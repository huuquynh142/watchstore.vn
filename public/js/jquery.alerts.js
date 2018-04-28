// jQuery Alert Dialogs Plugin
//
// Version 1.1
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 14 May 2009
//
// Website: http://abeautifulsite.net/blog/2008/12/jquery-alert-dialogs/
//
// Usage:
//		jAlert( message, [title, callback] )
//		jConfirm( message, [title, callback] )
//		jPrompt( message, [value, title, callback] )
// 
// History:
//
//		1.00 - Released (29 December 2008)
//
//		1.01 - Fixed bug where unbinding would destroy all resize events
//
// License:
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2008 A Beautiful Site, LLC. 
//
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
(function ($) {

    $.alerts = {
        // These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time

        verticalOffset: 0, // vertical offset of the dialog from center screen, in pixels
        horizontalOffset: 0, // horizontal offset of the dialog from center screen, in pixels/
        repositionOnResize: true, // re-centers the dialog on window resize
        overlayOpacity: .1, // transparency level of overlay
        overlayColor: '#000', // base color of overlay
        draggable: true, // make the dialogs draggable (requires UI Draggables plugin)
        okButton: '&nbsp;OK&nbsp;', // text for the OK button
        closeButton: '&nbsp;CLOSE&nbsp;', // text for the OK button
        cancelButton: '&nbsp;Cancel&nbsp;', // text for the Cancel button
        dialogClass: null, // if specified, this class will be applied to all dialogs
         
        // Public methods

        alert: function (message, title, callback) {
            if (title == null)
                title = 'Thông báo';
            $.alerts._show(title, message, null, 'alert', function (result) {
                if (callback)
                    callback(result);
            });
        },
        confirm: function (message, title, callback) {
            if (title == null)
                title = 'Confirm';
            $.alerts._show(title, message, null, 'confirm', function (result) {
                if (callback)
                    callback(result);
            });
        },
        prompt: function (message, value, title, callback) {
            if (title == null)
                title = 'Prompt';
            $.alerts._show(title, message, value, 'prompt', function (result) {
                if (callback)
                    callback(result);
            });
        },
        form: function (formId, title, callback) {
            if (title == null)
                title = 'Form';
            $.alerts._show(title, null, formId, 'form', function (result) {
                if (callback)
                    callback(result);
            });
        },
        content: function (id, title) {
            if (title == null)
                title = 'Thông báo';
            $.alerts._show(title, null, id, 'content');
        },
        dialog: function (msg , id, title, callback) {
            if (title == null)
                title = 'Thông báo';
            $.alerts._show(title, msg, id , 'dialog', function (result) {
                if (callback)
                    callback(result);
            });
        },
        // Private methods

        _show: function (title, msg, value, type, callback) {


            $.alerts._hide();
            $.alerts._overlay('show');

            $("BODY").append(
                    '<div id="popup_container">' +
                    '<div class="popup_border">' +
                    '<div id="popup_title"></div>' +
                    '<div id="popup_content">' +
                    '<div id="popup_message"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');

            if ($.alerts.dialogClass)
                $("#popup_container").addClass($.alerts.dialogClass);

            // IE6 Fix
            var pos = ($.browser.msie && parseInt($.browser.version) <= 6) ? 'absolute' : 'fixed';

            $("#popup_container").css({
                position: pos,
                zIndex: 99999,
                padding: 0,
                margin: 0
            });

            $("#popup_title").text(title);
            $("#popup_content").addClass(type);
            $("#popup_message").html(msg);
            //$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );
            
            setTimeout(function() {
                $('body').addClass('hide_overflow');
//                $("#popup_container").css({
//                    minWidth: $("#popup_container").outerWidth(),
//                    //maxWidth: $("#popup_container").outerWidth()
//                });
                $.alerts._reposition();
                
            }, 200);
            
            $.alerts._maintainPosition(true);
            $( "#popup_container" ).click(function() {
                $.alerts._reposition();
                 
                return true;
            }); 
            
            switch (type) {
                case 'alert':
                    $("#popup_content").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /></div>');
                    
                    $("#popup_ok").click(function () {
                        $.alerts._hide();
                        callback(true);
                    });
                    $("#popup_ok").keypress(function (e) {
                        if (e.keyCode == 13 || e.keyCode == 27)
                            $("#popup_ok").trigger('click');
                    });
                    break;

                case 'content':
                    $("#popup_message").append($('#' + value).html()).css('padding-left', '0');

                    $("#popup_content").after('<div id="popup_panel"><input type="button" value="' + $.alerts.closeButton + '" id="popup_close" /></div>')
                            .css('background', 'none');
                    $("#popup_close").click(function () {
                        $.alerts._hide();

                    });
                    $("#popup_close").keypress(function (e) {
                        if (e.keyCode == 13 || e.keyCode == 27)
                            $("#popup_close").trigger('click');
                    });
                    break;

                case 'confirm':
                    $("#popup_content").after('<div id="popup_panel"><input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /> <input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /></div>');
                    $("#popup_ok").click(function () {
                        $.alerts._hide();
                        if (callback)
                            callback(true);
                    });
                    $("#popup_cancel").click(function () {
                        $.alerts._hide();
                        if (callback)
                            callback(false);
                    });

                    $("#popup_ok, #popup_cancel").keypress(function (e) {
                        if (e.keyCode == 13)
                            $("#popup_ok").trigger('click');
                        if (e.keyCode == 27)
                            $("#popup_cancel").trigger('click');
                    });
                    break;
                case 'prompt':
                    $("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />');
                    $('#popup_content').after('<div id="popup_panel"><input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /> <input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /></div>');
                    $("#popup_prompt").width($("#popup_message").width());
                    $("#popup_ok").click(function () {
                        var val = $("#popup_prompt").val();
                        $.alerts._hide();
                        if (callback)
                            callback(val);
                    });
                    $("#popup_cancel").click(function () {
                        $.alerts._hide();
                        if (callback)
                            callback(null);
                    });
                    $("#popup_prompt, #popup_ok, #popup_cancel").keypress(function (e) {
                        if (e.keyCode == 13)
                            $("#popup_ok").trigger('click');
                        if (e.keyCode == 27)
                            $("#popup_cancel").trigger('click');
                    });
                    if (value)
                        $("#popup_prompt").val(value);
                    $("#popup_prompt").select();
                    break;
                case 'form':

                    //trong truong hop nay value = formId

                    var action = $('#' + value).attr('action');
                    var notSubmit = $('#' + value).attr('not-submit') == "1" ? true : false;
                    $("#popup_message").append(
                            '<form method="post" action="' + action + '">' + $('#' + value).html() + '</form>' +
                            '<div class="popup_error"></div>');
                    $('#popup_content').after('<div id="popup_panel"> <input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /> <img src="/images/loading.gif" class="loading"/></div>');
                    $("#popup_prompt").width($("#popup_message").width());
                    //$('#popup_message form input[type="text"]').eq(0).focus();
                    
                    $("#popup_ok").click(function() {
                        var btns = $('#popup_panel input[type="button"]');
                        btns.hide();
                        if (notSubmit) {
                            if (callback) {
                                callback($("#popup_message form").serializeObject());
                            }
                        }
                        else {
                            $('#popup_panel .loading').show();
                            $('#popup_panel input[type="button"]').attr('disabled', 'disabled');

                            $.ajax({
                                url: action,
                                data: $('#popup_message form').serialize(),
                                type: 'post',
                                success: function (data) {
                                    if (callback)
                                        callback(data);
                                },
                                complete: function () {
                                     btns.show();
                                    $('#popup_message').find('input, textarea').removeAttr('disabled');
                                    $('#popup_panel input').removeAttr('disabled');
                                    try {
                                        $("#popup_message").animate({scrollTop: $('#popup_message')[0].scrollHeight}, 500);
                                    } catch(e){}
                                },
                                error: function () {
                                    jAlert('Hệ thống đang bận, bạn vui lòng thử lại sau giây lát.');

                                }

                            });
                        }
                    });

                    $("#popup_cancel").click(function () {
                        $.alerts._hide();
                        if (callback)
                            callback(null);
                    });

                    $("#popup_prompt, #popup_ok, #popup_cancel").keypress(function (e) {
                        if (e.keyCode == 13)
                            $("#popup_ok").trigger('click');
                        if (e.keyCode == 27)
                            $("#popup_cancel").trigger('click');
                    });
                    if (value)
                        $("#popup_prompt").val(value);
                    $("#popup_prompt").select();
                    break;
                case 'dialog':
                    $('#popup_title').append('<button class="btnClose" id="popup_cancel" ><img src="../img/icons/close.svg"></button>');

                    $("#popup_cancel").click(function () {
                        $.alerts._hide();
                    });
                    break;
            }

            // Make draggable
            if ($.alerts.draggable) {
                try {
                    $("#popup_container").draggable({handle: $("#popup_title")});
                    $("#popup_title").css({cursor: 'move'});
                } catch (e) { /* requires jQuery UI draggables */
                }
            }
        },
        _hide: function () {
            $('body').removeClass('hide_overflow'); 
            $("#popup_container").remove();
            $.alerts._overlay('hide');
            $.alerts._maintainPosition(false); 
        },
        _showError: function (msg) {
            $('#popup_container .popup_error').show().html(msg);
            $('#popup_message').find('input[type="text"], textarea').removeAttr('disabled');
            $('#popup_panel .loading').hide();
            $('#popup_panel input[type="button"]').removeAttr('disabled');
        },
        _overlay: function (status) {
            switch (status) {
                case 'show':
                    $.alerts._overlay('hide');
                    $("BODY").append('<div id="popup_overlay"></div>');
                    $("#popup_overlay").css({
                        position: 'absolute',
                        display: 'block',
                        zIndex: 99998,
                        top: '0px',
                        left: '0px',
                        width: '100%',
                        height: $(document).height(),
                        background: $.alerts.overlayColor,
                        opacity: $.alerts.overlayOpacity
                    });
                    break;
                case 'hide':
                    $("#popup_overlay").remove();
                    break;
            }
        },
        _reposition: function () {

             
                
            wh = $(window).height();
            var top = ((wh / 2) - ($("#popup_container").outerHeight() / 2)) + $.alerts.verticalOffset;
            top = top>50?50:top;
            
            var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.alerts.horizontalOffset;
            if (top < 0)
                top = 0;
            if (left < 0)
                left = 0;

            // IE6 fix
            if ($.browser.msie && parseInt($.browser.version) <= 6)
                top = top + $(window).scrollTop();

            $("#popup_container").css({
                top: top + 'px',
                left: left + 'px'
            });

            if ($("#popup_container").outerHeight() > wh -50) {
                $('#popup_message').css({height: (wh - 115) + 'px'});
            }
            else {
                $('#popup_message').css({height: 'auto'});
            }
            $("#popup_overlay").height($(document).height()); 
            

        },
        _maintainPosition: function (status) {
            if ($.alerts.repositionOnResize) {
                switch (status) {
                    case true:
                        $(window).bind('resize', $.alerts._reposition);
                        break;
                    case false:
                        $(window).unbind('resize', $.alerts._reposition);
                        break;
                }
            }
        }

    }

    // Shortuct functions
    jAlert = function (message, title, callback) {
        $.alerts.alert(message, title, callback);
    }

    jContent = function (id, title) {
        $.alerts.content(id, title);
    }

    jConfirm = function (message, title, callback) {
        $.alerts.confirm(message, title, callback);
    };

    jPrompt = function (message, value, title, callback) {
        $.alerts.prompt(message, value, title, callback);
    };

    jForm = function (id, title, callback) {
        $.alerts.form(id, title, callback);
    };

    jDialog = function (msg , id, title, callback ) {
        $.alerts.dialog(msg , id, title, callback)

    }


})(jQuery);
