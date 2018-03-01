$returnCookie = Cookie::make(
            'crm.return_url', $_SERVER['HTTP_REFERER'] . '?order=stop', 1000, '/', env('COOKIE_DOMAIN'), false, false
        );

return response()->json(['success' => true, 'redirect' => '/#/'])
            ->withCookie($orderCreate)
            ->withCookie($clientCookie)
            ->withCookie($userCookie)
            ->withCookie($returnCookie);




    public static function format($string)
    {
        return preg_replace("/[^A-Za-zА-Яа-я0-9 ]/u", '', $string);
    }


#create
$this->validate($request, ['name' => 'required', 'club_id' => 'required']);
$parents = Club::select('id','name')->get()->pluck('name', 'id');
return view('rooms.create', compact('parents'));


#form
<div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
   {!! Form::label('label', 'Club: ', ['class' => 'col-md-4 control-label']) !!}
   <div class="col-md-6">
       {!! Form::select('club_id', null, ['class' => 'form-control']) !!}
       {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
   </div>
</div>



#foreach last first element
foreach($array as $element) {
    if ($element === reset($array))
        echo 'FIRST ELEMENT!';

    if ($element === end($array))
        echo 'LAST ELEMENT!';
}
