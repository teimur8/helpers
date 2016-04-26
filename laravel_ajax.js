$('#ajaxGet').on('click', function () {
    $.get('/ajax',function(data){
        $('.posts').slideUp(500, function() {
            $('.posts').html(data).slideDown(500);
        });
    })
})

$('#ajaxForm').submit(function(e){
    e.preventDefault();
    var name = $('.form-name').val();
    var email = $('.form-email').val();
    var token = $('.form-token').val();
    $.post('/ajax',{name:name,email:email,_token:token})
        .done(function(data){
            $('.formajax').fadeOut(500, function() {
                $(this).html(data).fadeIn(500);
            });
        });

});