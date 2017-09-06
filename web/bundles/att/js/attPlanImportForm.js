function buildFieldsForm(){

    $('.fileInput').fileinput({
        'showUpload':true, 
        'previewFileType':'any',
        'language':'es',
        'theme':'gly'
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
                
                $('#formErrors').html(data['message']);
                $('#panel').attr('class','panel panel-danger');
                buildFieldsForm();
                
                }else{
                    alert('Processed');
                    $('#content').html("<p>"+data['plans']+"</p>\n\\n\
                                        <p> Are this errors in File: "+data['errors_file']+"</p>\n\
                                        <p> Are this errors in Plans: "+data['errors_entities']+"</p>");
                    }
                    
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {                
                alert(errorThrown);
             }
        });
       
     });
     };
    


