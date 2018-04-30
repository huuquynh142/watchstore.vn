$(document).ready(function () {
    $(document).on('click','#btnQuickOder',function () {
        jDialog('<div>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</div>', 'popup_bidding_frame', 'cvnb', function(data) {

            data = $.parseJSON(data);

            jAlert(data.msg);

            if (data.code == 0) {
                location.reload(true);
            }
        });
    });

    $("#customer_login").validate({
        ignore: [],
        rules: {
            customer_phone: {
                required: true,
                phoneno:true
            },
            customer_password: {
                required: true
            }
        },
        messages: {
            customer_phone: {
                required: "Vui lòng nhập số điện thoại",
                phoneno: "Vui lòng nhập đúng định dạng số điện thoại"
            },
            customer_password: {
                required: "Vui lòng nhập mật khẩu"
            }
        },
        submitHandler: function(form) {
            $("#btn_submit").attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: '/account/login',
                dataType: 'json',
                data: $("#customer_login").serialize(),
                success: function(data) {
                    if (data.code == 'success')
                        window.location.href = 'http://dev.huuquynh.com:1030/index/index';
                    else
                        jAlert('Đăng nhập không thành công . Vui lòng kiểm tra lại!');
                }});
            return false;
        }
    });

    $("#create_customer").validate({
        ignore: [],
        rules: {
            customer_full_name: {
                required: true
            },
            customer_phone_number: {
                required: true,
                phoneno:true
            },
            customer_email: {
                emailz: true
            },
            customer_address: {
                required: true
            },
            customer_password: {
                required: true,
                lenghPassword: true
            },
            retype_password: {
                required: true,
                retypePassword: true
            }
        },
        messages: {
            customer_phone_number: {
                required: "Vui lòng nhập số điện thoại",
                phoneno: "Vui lòng nhập đúng định dạng số điện thoại"
            },
            customer_full_name: {
                required: "Vui lòng nhập họ và tên"
            },
            customer_email: {
                emailz: "Vui lòng nhập đúng định dạng email"
            },
            customer_address: {
                required: "Vui lòng nhập địa chỉ"
            },
            customer_password: {
                required: "Vui lòng nhập mật khẩu",
                lenghPassword: "Mật khẩu tối thiểu 6 ký tự"
            },
            retype_password: {
                required: "Vui lòng nhập lại mật khẩu",
                retypePassword: "Mật khẩu không khớp !"
            }
        },
        submitHandler: function(form) {
            $("#btn_submit").attr("disabled", true);
            $.ajax({
                type: 'POST',
                url: '/account/register',
                dataType: 'json',
                data: $("#create_customer").serialize(),
                success: function(data) {
                    if (data.code == 'success')
                        window.location.href = 'http://dev.huuquynh.com:1030/index/index';
                    else
                        jAlert(data.message);
                },
                error: function(data) {
                    jAlert(data);
                }});
            return false;
        }
    });

    jQuery.validator.addMethod("phoneno", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 6 &&
            phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
    });
    jQuery.validator.addMethod("lenghPassword", function(password, element) {
        return this.optional(element) || password.length > 5 });
    jQuery.validator.addMethod("retypePassword", function(password, element) {
        var pass = $("#create_customer #password").val();
        return this.optional(element) || password == pass });

    jQuery.validator.addMethod("emailz", function(email, element) {
        return this.optional(element) ||
            email.match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/);
    });


});