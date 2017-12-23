$(function () {

    var button = $("#deleteAllBtn");
    var resultAlert = $('#resultAlert');

    button.on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        resultAlert.hide();
        resultAlert.removeClass( "alert-success alert-danger" );

        $.ajax({
            type: 'POST',
            url: '/admin/ajax/blog/post/delete-all',
            data: true,

            beforeSend: function() {
                button.prop('disabled', true);
            },
            complete: function () {
                button.prop('disabled', false);
            }
        }).done(function (data) {

            if (data.status) {
                resultAlert.addClass('alert-success');
            } else {
                resultAlert.addClass('alert-danger');
            }

            resultAlert.html(data.msg);
            resultAlert.show();

        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        });
    });
});

