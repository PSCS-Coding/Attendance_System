$(document).ready(function(){ 
    $('#offsiteloc').keydown(function () {
        var max = 25;
        var len = $(this).val().length;
        if (len >= max) {
            $('#offsiteloc').addClass('textRed');           
        } 
        else {
            var ch = max - len;
            $('#offsiteloc').removeClass('textRed');          
        }
    });    
});
