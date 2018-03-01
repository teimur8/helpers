<?php

// class method
if($request->ajax()){
	return view('blog.ajax.index',['data'=>$data])->render();
}

// ajax select
