$(document).ready(function () {
    $(document).on('change', '#checkout_shipping_address_province', function () {
        $.getJSON('/frontend/CheckOuts/district/' + this.value, function (data) {
            if (data.code == 'success') {
                updateInfo();
                $("#checkout_shipping_address_city").html(data.data);
            }
        });
    });

    $(document).on('change', '#checkout_shipping_address_provinces', function () {
        $.getJSON('/frontend/CheckOuts/district/' + this.value, function (data) {
            if (data.code == 'success') {
                $("#checkout_shipping_address_citys").html(data.data);
                console.log(data.data);
            }
        });
    });

    $(document).on('change', '#checkout_shipping_address_city', function () {
        updateInfo();
    });

    function getRandomInt() {
        var items = Array('10.000','15.000','20.000','25.000','30.000','35.000','40.000');
       return items[Math.floor(Math.random()*items.length)];
    }

    function updateInfo() {
        var price = getRandomInt();
        $("#price_shipping").text(price);
        $("#order_shipping_method").text(price);
        $.getJSON('/frontend/CheckOuts/updatetotal/' + price, function (data) {
            if (data.code == 'success') {
                $("#total_all_payment").text(data.data);
                $("#update_total_price").text(data.data);
                $("#total_recap_price").text(data.data);
                console.log(data.data);
            }
        });
    }

    $("#customer_info").validate({
        ignore: [],
        rules: {
            checkout_phone: {
                required: true,
                phoneno: true
            },
            checkout_name: {
                required: true
            },
            checkout_province: {
                province: true
            },
            checkout_shipping_address: {
                required: true
            }

        },
        messages: {
            checkout_phone: {
                required: "Vui lòng nhập số điện thoại",
                phoneno: "Vui lòng nhập đúng định dạng số điện thoại"
            },
            checkout_name: {
                required: "Vui lòng nhập họ và tên"
            },
            checkout_province: {
                province: "Vui lòng chọn tỉnh/thành phố"
            },
            checkout_shipping_address:{
                required: "Vui lòng nhập địa chỉ"
            }
        },
        submitHandler: function(form) {
            $("#btn_submit").attr("disabled", true);
            $("#checkout_price_shipper").val($("#price_shipping").text());
            $("#checkout_price_total").val($("#total_all_payment").text());
            $.ajax({
                type: 'POST',
                url: '/checkouts/customerInfopost',
                dataType: 'json',
                data: $("#customer_info").serialize(),
                success: function(data) {
                    if (data.code == 'success')
                    {
                        window.location.href =  'http://dev.huuquynh.com:1030/thanh-toan/' + data.id;
                    }

                    else
                        alert(data.message);
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
    jQuery.validator.addMethod("province", function(province, element) {
        var province = $("#checkout_shipping_address_province").val();
        return this.optional(element) || province != "---" });
    jQuery.validator.addMethod("lenghPassword", function(password, element) {
        return this.optional(element) || password.length > 5 });
    jQuery.validator.addMethod("retypePassword", function(password, element) {
        var pass = $("#create_customer #password").val();
        return this.optional(element) || password == pass });

    jQuery.validator.addMethod("emailz", function(email, element) {
        return this.optional(element) ||
            email.match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/);
    });


    $(document).on('click','#paymentOnepay' , function () {
        var id = $(this).data('id');
        var phone = $(this).data('phone');
        var price = $(this).data('price')
        window.open('/frontend/payment/onePayRequest/' + id + '/' + phone + '/' + price , '_blank');
    });

    $(document).on('click','#btnfinit',function () {
        $.ajax({
            type: 'POST',
            url: '/frontend/checkouts/confirm',
            dataType: 'json',
            data: $("#tesstform").serialize(),
            success: function(data) {

            },
            error: function(data) {
                jAlert(data);
            }});
    });
    $(document).on('click','#btnclearnSession', function () {
        $.get("http://dev.huuquynh.com:1030/frontend/checkouts/clearnsession");
    })
});