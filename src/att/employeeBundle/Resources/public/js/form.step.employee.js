   $(document).ready(function () {

    var entity;
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();

    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);

        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });

    $(".next-step-validate-step-1").click(function (e) {

        e.preventDefault();
        var formElement = document.getElementById('employee-personal-form');
        var formData = new FormData(formElement);
        $.ajax({
            cache: false,
            type: $("#employee-personal-form").attr('method'),
            url: $("#employee-personal-form").attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data['message'] !== 'ok') {
                $('#formStep1').html(data['form']);

            } else {

                $('#formStep2').html(data['form']);
                entity = data['entity'];

                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
            }

        });
    });


    $(".next-step-validate-step-2").click(function (e) {
        //alert(entity);
        e.preventDefault();
        var formElement = document.getElementById('employee-contact-form');
        var formData = new FormData(formElement);
        formData.append('entity', entity);

        $.ajax({
            cache: false,
            type: $("#employee-contact-form").attr('method'),
            url: $("#employee-contact-form").attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data['message'] !== 'ok') {
                $('#formStep2').html(data['form']);
            } else {
                $('#formStep3').html(data['form']);
                entity = data['entity'];

                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
            }

        });
    });

    $(".next-step-validate-step-3").click(function (e) {

        e.preventDefault();
        var formElement = document.getElementById('employee-addressing-form');
        var formData = new FormData(formElement);
        formData.append('entity', entity);

        $.ajax({
            cache: false,
            type: $("#employee-addressing-form").attr('method'),
            url: $("#employee-addressing-form").attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data['message'] !== 'ok') {
                $('#formStep3').html(data['form']);
            } else {
                entity = data['entity'];
                $('#show').html(data['show']);
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
            }

        });
    });
    
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });


});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}



        