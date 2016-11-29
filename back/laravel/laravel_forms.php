<form action="" method="POST" style="display: inline-block">
    <button class="btn btn-danger" style="padding: 0px 5px;" onclick="return confirm('Г’Г®Г·Г­Г®?')"><i class="fa fa-trash" aria-hidden="true"></i></button>
</form>


<?php

public static function statusPluck()
{
    $result =  collect(\DB::table('subscribes_bm')->pluck('name','id'))->prepend('РЎС‚Р°С‚СѓСЃ РїРѕРґРїРёСЃРєРё','');
    return $result->toArray();
}





?>
