
$(document).ready(function () {
    $(document).on('click','#btl_login',function () {
        var userName = $('#user_name').val();
        var password = $('#password').val();
        if (!userName || !password)
           alert('vui lòng nhập đầy đủ thông tin !');
    });
});