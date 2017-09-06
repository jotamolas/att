$('#editModal').on('show.bs.modal', function (e) {
    //get data-id attribute of the clicked element
    var employeeid = $(e.relatedTarget).data('id');
    var mode = $(e.relatedTarget).data('mode');
    $('#modal-body-edit').load(Routing.generate('employee_edit',
            {
                mode: mode,
                id: employeeid
            }));

    $('#save').attr('data-employee-id', employeeid);

});

$('#addressModal').on('show.bs.modal', function (e) {
    //get data-id attribute of the clicked element
    var employeeid = $(e.relatedTarget).data('id');
    var mode = $(e.relatedTarget).data('mode');
    $('#modal-body-address-show').load(Routing.generate('employee_address_show',
            {
                'mode': mode,
                'id': employeeid
            }));
});

$('#addresseditModal').on('show.bs.modal', function (e) {
    //get data-id attribute of the clicked element
    var employeeid = $(e.relatedTarget).data('id');
    var mode = $(e.relatedTarget).data('mode');
    $('#modal-body-address-edit').load(Routing.generate('employee_address_edit',
            {
                'mode': mode,
                'id': employeeid
            }));
    $('#modal-footer-address-edit').html("<input type='button' class='btn btn-default' id='updateAddress' value='Update' data-employee-id='" + employeeid + "'>");
});

$('#employeeshowModal').on('show.bs.modal', function (e) {
    //get data-id attribute of the clicked element
    var employeeid = $(e.relatedTarget).data('id');
    var mode = $(e.relatedTarget).data('mode');
    $('#modal-body-employee-show').load(Routing.generate('employee_show',
            {
                'mode': mode,
                'id': employeeid
            }));
});

$(document).on('click', '#save', function (e) {
    e.preventDefault();
    var form = "employee-form";
    var row = '#row-employee-' + $(this).data('employee-id');
    var formElement = document.getElementById(form);
    var formData = new FormData(formElement);
    formData.append('employee-id', $(this).data('employee-id'));

    $.ajax({
        cache: false,
        type: formElement.method,
        url: formElement.action,
        data: formData,
        contentType: false,
        processData: false
    }).done(function (data) {
        if (data['status'] !== 'ok') {
            $('.modal-body').html(data['form']);
        } else {
            $('#successMsg').html("<div class='alert alert-success'><strong>" + data['message'] + "</strong></div>");
            $(row).html(data['row']);
            $('#editModal').modal('hide');
            $('.alert-success').fadeOut(7000, function () {
                $('.alert-success').remove();
            });
        }
    });
});

$(document).on('click', '#remove', function (e) {

    var employeeid = $(this).data('id');
    var mode = $(this).data('mode');
    var row = $(this).closest('tr');
    
    bootbox.confirm({
        size: "small",
        message: "Are you sure?",
        callback: function (result) {
            if (result) {
                $.ajax({
                    url: Routing.generate('employee_delete',
                            {
                                'id': employeeid,
                                'mode': mode
                            }),
                    type: 'delete'
                }).done(function (data) {
                    row.css("background-color", "#FF3700");
                    row.fadeOut(4000, function () {
                        row.remove();
                    });
                });
            }
        }
    });
});

$(document).on('click', '#updateAddress', function (e) {
    var employeeid = $(this).data('employee-id');
    e.preventDefault();
    var formElement = document.getElementById('employee-addressing-form');
    var formData = new FormData(formElement);
    formData.append('employee-id', employeeid);
    $.ajax({
        cache: false,
        type: $("#employee-addressing-form").attr('method'),
        url: $("#employee-addressing-form").attr('action'),
        data: formData,
        contentType: false,
        processData: false
    }).done(function (data) {
        if (data['message'] !== 'ok') {
            $('.modal-body-address-edit').html(data['form']);
        } else {
            $('#successMsg').html("<div class='alert alert-success'><strong>" + data['message'] + "</strong></div>");
            $('#addresseditModal').modal('hide');
            $('.alert-success').fadeOut(7000, function () {
                $('.alert-success').remove();
            });
        }

    });
});




