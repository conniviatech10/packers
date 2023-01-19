(function($) {
'use strict';    
    // role wise permissiom ajax script
    $(document).on('change', '#role', function(){
        var token = $('#token').val();
        var getUrl = window.location;
        var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
        baseUrl=baseUrl;//+'/admin';
        if(!$(this).val()){
            var notyf = new Notyf();
            notyf.open({type:'error', message:'Please select a Role',position:{x:'center',y: 'top'}});
            return false;
        }
        $.ajax({
            url : baseUrl+"/get-role-permissions-badge",
            type: 'get',
            data: {
                id : $(this).val(),
                _token : token
            },
            success: function(res)
            {
                $('#permission').html(res);
            },
            error: function()
            {
                alert('failed...');

            }
        });
    });

    $('select').select2();
})(jQuery);
