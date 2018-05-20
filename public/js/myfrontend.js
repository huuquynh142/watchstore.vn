$(document).ready(function () {
    loadAction();

    function loadAction(){
        $action = $("#actionPage").val();
        $(".nav-item").removeClass("active");
        switch ($action){
            case "home":
                $("#home").addClass("active");
                break;
            case "product":
                $("#product").addClass("active");
        }
    }

    // $("#search").keyup(function(){
    //     $.ajax({
    //         type: "POST",
    //         url: "/Search/index",
    //         data:'keyword='+$(this).val(),
    //         beforeSend: function(){
    //             $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
    //         },
    //         success: function(data){
    //             $("#suggesstion-box").show();
    //             $("#suggesstion-box").html(data);
    //             $("#search-box").css("background","#FFF");
    //         }
    //     });
    // });


    $("#search-form").validate({
        ignore: [],
        rules: {
            keyword: {
                required: true
            }
        },
        messages: {
            keyword: {
                required: "Vui lòng nhập sản phẩm tìm kiếm"
            }
        },
        submitHandler: function(form) {
            $("#btn_submit").attr("disabled", true);
            window.location.href =  'http://dev.huuquynh.com:1030/san-pham-tim-kiem/'+ $("#search").val();
            return false;
        }
    });


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
    $(document).on('click','#addShopcart',function () {
        var id = $(this).attr('data-id');
        var quatity = $(".quantity_" + id).val();

        $.ajax({
            type: 'POST',
            url: '/ShopCart/add',
            dataType: 'json',
            data: {'id':id , 'quatity': quatity},
            success: function(data) {
                if (data.code == 'success') {
                    $('.reveal-modal-bg').css({"visibility": "hidden", "display": "none"});
                    $('.reveal-modal').css({"visibility": "hidden", "display": "none"});

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


    $(document).on('click','#addToCart',function () {
        var id = $(this).attr('data-id');
        var quatity = $(".quantity_" + id).val();

        $.ajax({
            type: 'POST',
            url: '/ShopCart/add',
            dataType: 'json',
            data: {'id':id , 'quatity': quatity},
            success: function(data) {
                if (data.code == 'success') {
                    // $('.reveal-modal-bg').css({"visibility": "hidden", "display": "none"});
                    // $('.reveal-modal').css({"visibility": "hidden", "display": "none"});

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
    var brand = '0';
    var price = '0';
    var sortby = '0';
    $(document).on('click' , '.advanced-filters .advanced-filter' , function () {
        var buttonGroup = $(this).parents('.advanced-filters');
        buttonGroup.find('.active-filter').removeClass("active-filter");

        var id = $(this).attr('data-id').toString().toLowerCase();
        $(this).last().toggleClass( "active-filter" );
        $indextype = null;
        if ($(this).data('group') == 'Brand')
            brand = id;
        if ($(this).data('group') == 'Price')
            price = id;
        type = $(this).data('currenttype');
        searchProduct();
        return false;
    });

    function arrParamFilter(){
        return [type,brand,price,sortby];
    }

    $("#sortBy").change(function() {
        console.log($(this).val());
        sortby = $(this).val();
        type = $(this).data('currenttype');
        searchProduct();
    });

    function searchProduct(){
        var params = '';
        var arr = arrParamFilter();
        $.each(arr, function (index, value) {
            params += ( value + '/');
            console.log(value);

        });
        window.history.pushState(null,null,'/san-pham/' + params);
        $.get('/product-search/' + params, function (data) {
            $("#container_product").html(data);

        })
    }

    $(document).on('click','.js-qty .js-qty__adjust',function (){
        var id = $(this).closest('.js-qty').data('id');
        if( $(this).hasClass('js-qty__adjust--plus') ) {
            var max = 0;
            var maxquantity = $("#js-qty___quantity_" + id).data('id');
            var countInput  = $(this).parent().find(".js-qty__num");
            var count = countInput.val();
            if (maxquantity > 10)
                max = 10;
            else
                max = maxquantity;
            if(count < max)
            {
                var  value = parseInt( count ) + 1;
                countInput.val(value );
                updateCart(id , value);
            }else
                alert('Số sản phẩm có thể mua tối đa là ' +max +' sản phẩm !')

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
            {
                if (data.code == 'empty')
                    window.location.href =  'http://dev.huuquynh.com:1030/trang-chu';
                else
                    alert('Xóa không thành công!');

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
                    {
                        window.location.href =  'http://dev.huuquynh.com:1030/trang-chu';
                    }
                    else
                        jAlert(data.message);
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
                    {
                        window.location.href =  'http://dev.huuquynh.com:1030/trang-chu';
                    }

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