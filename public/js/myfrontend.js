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


});