function buildFieldsForm() {
    $('.input-daterange').datepicker({
        language: "es",
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
    $('.fileInput').fileinput({
        showUpload: true,
        previewFileType: 'any',
        theme: 'fa',
        browseClass: 'btn btn-outline-primary btn-sm',
        browseLabel: "Browse",
        cancelClass: 'btn btn-outline-secondary btn-sm',
        removeClass: 'btn btn-outline-danger btn-sm',
        uploadClass: 'btn btn-outline-success btn-sm'
    });

    $('.datepicker').datepicker({
        language: "es",
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
}
;

function initForm() {
    $('body').on('submit', '.attForm', function (e) {

        e.preventDefault();
        var forElement = document.getElementById('attForm');
        var formData = new FormData(forElement);
        $('#atcertificate_type').attr('disabled', false);
        $.ajax({
            cache: false,
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false
        })
                .done(function (data) {

                    if (data['message'] !== 'ok') {

                        $('#form_body').html(data['form']);
                        $('.inputFile').fileinput({
                            showUpload: true,
                            previewFileType: 'any',
                            'theme': 'fa',
                            browseClass: 'btn btn-outline-primary btn-sm',
                            browseLabel: "Browse",
                            cancelClass: 'btn btn-outline-secondary btn-sm',
                            removeClass: 'btn btn-outline-danger btn-sm',
                            uploadClass: 'btn btn-outline-success btn-sm'
                        });
                        $('.input-daterange').datepicker({
                            language: "es",
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            todayHighlight: true
                        });
                        $('.datepicker').datepicker({
                            language: "es",
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            todayHighlight: true

                        });
                    } else {
                        alert('Processed');
                        $('#content').load(Routing.generate('att_plan_showafterimport',
                                {
                                    'id': data['id']
                                }));
                    }

                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    if (typeof jqXHR.responseJSON !== 'undefined') {
                        alert(errorThrown);
                    }
                });

    });
}
;



