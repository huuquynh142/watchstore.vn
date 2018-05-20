
$(document).ready(function () {
    $(document).on('click','#btl_login',function () {
        var userName = $('#user_name').val();
        var password = $('#password').val();
        if (!userName || !password)
           alert('vui lòng nhập đầy đủ thông tin !');
    });
    $(document).on('change', '#province_id_backend', function () {
        $.getJSON('/frontend/CheckOuts/district/' + this.value, function (data) {
            if (data.code == 'success') {
                $("#district_id_backend").html(data.data);
                console.log(data.data);
            }
        });
    });

});