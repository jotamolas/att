function buildFieldsForm(){
    
    $('.input-daterange input').each(function() {
        $(this).datepicker({
            language: "es",
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });    
   
    $('.clockpicker').clockpicker({
        placement: 'top',
        autoclose: true
    });
    
    $('.select2').select2({
        theme: "bootstrap"
    });
};

function initForm() {
    
    $('body').on('submit', '.attForm', function (e) {
        
        e.preventDefault();
        var forElement = document.getElementById('attForm');
        var formData = new FormData(forElement);
        
        $.ajax({
            cache: false,
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false
        })
        .done(function (data) {
            
                if(data['status'] !== 'ok'){
                $('#form_body').html(data['form']);
                $('#panel').attr('class','panel panel-danger');
                buildFieldsForm();
                
                }else{
                   
                    $('#content').html("<div class='alert alert-info'>\n\
                                        <p class='text-center'>"+data['plansNew']+"</p>\n\
                                        <p class='text-center'>"+data['plansUpdate']+"</p>\n\
                                        <p class='text-center'>"+data['errors']+"</p>\n\
                                     </div>");
                }
                    
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {                
                alert(errorThrown);
             }
        });
       
     });
     };
    


