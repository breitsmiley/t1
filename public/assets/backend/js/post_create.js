$(function () {

    $("#postCreateForm").on("submit", function (e) {

        e.preventDefault();

        var form = $(this);
        var submitButton = $(":submit", form);

        var resultAlert = $('#resultAlert');

        resultAlert.hide();
        resultAlert.removeClass( "alert-success alert-danger" );

        $.ajax({
            type: 'POST',
            url: '/admin/ajax/blog/post/create',
            data: form.serialize(),

            beforeSend: function() {
                submitButton.prop('disabled', true);
            },
            complete: function () {
                submitButton.prop('disabled', false);
            }
        }).done(function (data) {
            if (data.status) {
                resultAlert.html('Successfully Saved');
                resultAlert.addClass('alert-success');
            } else {
                resultAlert.html(data.error);
                resultAlert.addClass('alert-danger');
            }

            resultAlert.show();

        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        });
    });
});

