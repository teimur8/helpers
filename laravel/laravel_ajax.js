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



// modal



// VIEW

    //// target
    <a title="Изменить" class="get_modal_form"
                          data-value="{{ $item->id }}" 
                          data-model="deal"
                          data-target="status_id"
                            >


    //// modal
    // <div class="modal fade" tabindex="-1" role="dialog" id="modal_to_edit_modal">
    //     <div class="modal-dialog">
    //         <div class="modal-content">
    //             <div class="modal-body" id="modal_to_edit_content" >
    //                 <p>One fine body&hellip;</p>
    //             </div>
    //             <div class="modal-footer">
    //                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    //                 <button type="button" class="btn btn-success" id="modal_to_edit_form_sumbit">Сохранить</button>

    //             </div>
    //         </div><!-- /.modal-content -->
    //     </div><!-- /.modal-dialog -->
    // </div><!-- /.modal -->


// JS

    $('.get_modal_form').on('click', function(e){
        e.preventDefault();
        var value = $(this).attr('data-value');
        var model = $(this).attr('data-model');
        var target = $(this).attr('data-target');

        $.get('{{route('get.form')}}',{value:value,model:model,target:target}, function(data){
            $('#modal_to_edit_content').html(data);
            $('#modal_to_edit_modal').modal('show');
        })
    });


    $(document).on('submit', 'modal_to_edit_form', function(e) {
        alert('clicked');
        e.preventDefault();
    });

    $('#modal_to_edit_form_sumbit').on('click', function(e){
        $.post('{{ route('post.form') }}',$('#modal_to_edit_form').serialize(), function(data){
            $('.get_modal_form').find('span').html(data);
            $('#modal_to_edit_content').html();
            $('#modal_to_edit_modal').modal('hide');
        })
        e.preventDefault();
    })

// ROUTE
    
    // Route::get('/get-edit-form', ['as'=>'get.form','uses'=>'Admin\AdminController@getEditForm']);
    // Route::post('/get-edit-form', ['as'=>'post.form','uses'=>'Admin\AdminController@postEditForm']);

// CONTROLLER

    // public function getEditForm()
    // {
    //     $value = Input::get('value');
    //     $model = Input::get('model');
    //     $target = Input::get('target');

    //     if('deal' == $model) $item = Deal::findOrFail($value);

    //     return view('ajax.form-'.$model.'-'.$target, ['item'=>$item])->render();
    // }

    // public function postEditForm(Request $request)
    // {
    //     $this->validate($request, [
    //        'model'=> 'required',
    //        'model_id'=> 'required',
    //     ]);

    //     if('deal' == $request['model']){
    //         return $this->changeDealStatus($request);
    //     }

    // }