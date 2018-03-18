$(document).ready(function(){
    
    $('#select-external').change(function(){
        
        if ($(this).val()=='1'){
            $('#fieldset-external').show();
        } else {
            $('#fieldset-external').hide();
        }
        
    })
    
})