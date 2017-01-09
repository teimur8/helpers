<?php

header("Location:".$_SERVER['HTTP_REFERER']);


/**  -------------------------------  */


/**
var_dump deep

; with sane limits
xdebug.var_display_max_depth = 5
xdebug.var_display_max_children = 256
xdebug.var_display_max_data = 1024 


; with no limits
; (maximum nesting is 1023)
xdebug.var_display_max_depth = -1 
xdebug.var_display_max_children = -1
xdebug.var_display_max_data = -1 
Of course, these may also be set at runtime via ini_set(), useful if you don't want to modify php.ini and restart your web server but need to quickly inspect something more deeply.

*/

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
