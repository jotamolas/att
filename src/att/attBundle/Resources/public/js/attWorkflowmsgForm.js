function initForm() {
    
    $('body').on('submit', '#attForm', function (e) {
        
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
            
                if(data['message'] !== 'ok'){
                    $('.modal-body').html(data['form']);
                }else{
                    alert('Sent');
                    $('#newMsg').modal('hide');
                }
                    
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {                
                alert(errorThrown);
             }
        });
       
     });
     };
    


