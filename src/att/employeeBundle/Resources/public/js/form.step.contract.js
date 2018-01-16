    
$(document).ready(function () {

    var business;
    var contract;
    var employee;
    var schema;


    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();

    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);

        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step-validate-step-1").click(function (e) {

        e.preventDefault();
        var formElement = document.getElementById('contract-employee-form');
        var formData = new FormData(formElement);
        $.ajax({
            cache: false,
            type: $("#contract-employee-form").attr('method'),
            url: $("#contract-employee-form").attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {

            if (data['message'] !== 'ok') {

                $('#formStep1').html(data['form']);

            } else {
                $('#formStep2').html(data['form']);
                contract = data['contract'];
                employee = data['employee'];
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
            }
        });
    });


    $(".next-step-validate-step-2").click(function (e) {

        e.preventDefault();
        var formElement = document.getElementById('contract-business-form');
        var formData = new FormData(formElement);
        formData.append('contract', contract);
        formData.append('employee', employee);
        $.ajax({
            cache: false,
            type: $("#contract-business-form").attr('method'),
            url: $("#contract-business-form").attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data['message'] !== 'ok') {
                $('#formStep2').html(data['form']);
            } else {
                $('#formStep3').html(data['form']);
                business = data['business'];
                employee = data['employee'];
                contract = data['contract'];
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
            }

        });
    });

    $(".next-step-validate-step-3").click(function (e) {

        e.preventDefault();

        var formElement = document.getElementById('contract-legal-form');
        var formData = new FormData(formElement);
        formData.append('contract', contract);
        formData.append('employee', employee);
        formData.append('business', business);
        $.ajax({
            cache: false,
            type: $("#contract-legal-form").attr('method'),
            url: $("#contract-legal-form").attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data['message'] !== 'ok') {
                $('#formStep3').html(data['form']);
            } else {
                $('#formStep4').html(data['form']);
                contract = data['contract'];
                schema = data['schema'];
                var $active = $('.wizard .nav-tabs li.active');
                $active.next().removeClass('disabled');
                nextTab($active);
            }

        });
    });

    $(".next-step-validate-step-persist").click(function (e) {

        e.preventDefault();

        var formElement = document.getElementById('contract-planning-form');
        var formData = new FormData(formElement);
        formData.append('contract', contract);
        formData.append('employee', employee);
        formData.append('business', business);
        formData.append('schema', schema);
        
        $.ajax({
            cache: false,
            type: $("#contract-planning-form").attr('method'),
            url: $("#contract-planning-form").attr('action'),
            data: formData,
            contentType: false,
            processData: false
        }).done(function (data) {
            if (data['message'] !== 'ok') {
                $('#formStep4').html(data['form']);
                
            } else {                
                $('#formStep5').html(data['confirm']);
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

    $(".skip").click(function (e) {
        $('#content').load($(".skip").attr('data-url'));
    });

});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}



                