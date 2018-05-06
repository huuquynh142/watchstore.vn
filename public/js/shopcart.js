$(document).ready(function () {
    $(document).on('change', '#checkout_shipping_address_province', function () {
        $.getJSON('/frontend/CheckOuts/district/' + this.value, function (data) {
            if (data.code == 'success') {
                updateInfo();
                $("#checkout_shipping_address_city").html(data.data);
                console.log(data.data);
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
});