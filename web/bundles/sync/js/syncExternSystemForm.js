function initForm() {
    $('body').on('submit','.syncExternSystemForm', function (e) {
        e.preventDefault();
        var formElement = document.getElementById('syncExternSystemForm');
        var formData = new FormData(formElement);
        $.ajax({
            cache: false,
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data['message'] !== 'ok') {
                $('#form_body').html(data['form']);
            }else{
                alert('Processed');
                $('#content').load(Routing.generate('sync_extern_system_list'));
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {
                alert(errorThrown);
                }
            });
    });
}
;



