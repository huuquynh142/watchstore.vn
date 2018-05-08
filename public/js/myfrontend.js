$(document).ready(function () {
    $(document).on('click','#btnQuickOder',function () {

        $.ajax({
            type: 'POST',
            url: '/ShopCart/add',
            dataType: 'json',
            data: {'id':$(this).attr('data-id')},
            success: function(data) {
                if (data.code == 'success') {
                    jDialog(data.data, 'popup', 'Giỏ hàng', function (data) {

                        data = $.parseJSON(data);

                        jAlert(data.msg);

                        if (data.code == 0) {
                            location.reload(true);
                        }
                    });
                    $('#CartCount').text(data.countCart);
                    $('#CartCost').text(data.totalCart);
                    $('.cart-subtotal .money').text(data.totalCart);
                }
                else
                    alert(data.message)
            }});


    });
    $(document).on('click','#btn_quick_view', function () {
        console.log($(this).attr('data-id'));
        $.get('/frontend/product/updateview/'+ $(this).attr('data-id'));
    });

    var type = 'tat-ca-san-pham';
    var brand = '';
    var price = '';
    var sortby = '';
    $(document).on('click' , '.advanced-filters .advanced-filter' , function () {
        var buttonGroup = $(this).parents('.advanced-filters');
        buttonGroup.find('.active-filter').removeClass("active-filter");

        var id = $(this).attr('data-id').toString().toLowerCase();
        $(this).last().toggleClass( "active-filter" );
        var params = '';
        $indextype = null;
        if ($(this).data('group') == 'Brand')
            brand = id;
        if ($(this).data('group') == 'Price')
            price = id;
        var arr = arrParamFilter();
        type = $(this).data('currenttype');
        $.each(arr, function (index, value) {
            if (value){
                params += ( value + '/');
                // console.log(value);
            }
            console.log(value);

        });
        window.history.pushState(null,null,'/san-pham/' + params);
    });

    function arrParamFilter(){
        return [type,brand,price,sortby];
    }

    $(document).on('click','.js-qty .js-qty__adjust',function (){
        var id = $(this).closest('.js-qty').data('id');
        if( $(this).hasClass('js-qty__adjust--plus') ) {
            var countInput  = $(this).parent().find(".js-qty__num");
            var count = countInput.val();
            if(count < 10)
            {
                var  value = parseInt( count ) + 1;
                countInput.val(value );
                updateCart(id , value);
            }else
                alert('Mỗi sản phẩm chỉ được mua số lượng tối đa là 10 \n Vui lòng kiểm tra lại !')

        }else {
            var countInput  = $(this).parent().find(".js-qty__num");
            var count = countInput.val();
            if( parseInt(countInput.val())  > 1 ) {
                countInput.val( count - 1 );
                updateCart(id , (count - 1));
            }
        }
    });
    $(document).on('click','#btn-remove',function () {
        var id = $(this).attr('data-id');
        $.getJSON('/frontend/ShopCart/delete/' + id, function (data) {
            if (data.code == 'success') {
                jDialog(data.data, 'popup', 'Giỏ hàng', function (data) {

                    data = $.parseJSON(data);

                    jAlert(data.msg);

                    if (data.code == 0) {
                        location.reload(true);
                    }
                });
                $('#CartCount').text(data.countCart);
                $('#CartCost').text(data.totalCart);
                $('.cart-subtotal .money').text(data.totalCart);
            }else
                alert('Xóa không thành công!');
        });
    });

    $(document).on('click','#btn_remove_index',function () {
        var id = $(this).attr('data-id');
        $.getJSON('/frontend/ShopCart/delete/' + id, function (data) {
            if (data.code == 'success') {
                $('#CartCount').text(data.countCart);
                $('#CartCost').text(data.totalCart);
                $('.cart-subtotal .money').text(data.totalCart);
                window.location.reload();
            }else
                alert('Xóa không thành công!');
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

    function updateCart(id , quatity) {
        $.getJSON('/frontend/ShopCart/edit/' + id + '/' + quatity, function (data) {
            if (data.code == 'success')
                $("#money_" + id).text(data.total);
                $('#CartCount').text(data.countCart);
                $('#CartCost').text(data.totalCart);
                $('.cart-subtotal .money').text(data.totalCart);
        });
    }


});