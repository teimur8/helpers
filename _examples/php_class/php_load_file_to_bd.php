<?php

$filename_output = UPLOAD . 'tmp_' . str_replace(' ','_', transliterator_transliterate('Any-Latin; Latin-ASCII',$filename));
$filename = UPLOAD . $filename;
$output_handle = fopen($filename_output, "w");


$string_to_file = '';
$string_to_file .= $tmp_arr['main_brand'] . '(|)';
$string_to_file .= $tmp_arr['main_article'] . '(|)';
$string_to_file .= $tmp_arr['main_article_form'] . '(|)';
$string_to_file .= $tmp_arr['repl_brand'] . '(|)';
$string_to_file .= $tmp_arr['repl_article'] . '(|)';
$string_to_file .= $tmp_arr['repl_article_form'] . '(|)';
$string_to_file .= $tmp_arr['name'] . '(|)';
$string_to_file .= $tmp_arr['quality'] . '(|)';
$string_to_file .= $tmp_arr['check_dub_key'];
$string_to_file .= "\n";
$string_to_file = mysqli_real_escape_string($string_to_file);

fputs($output_handle, $string_to_file);



database::updateData('ALTER TABLE partners_cross DISABLE KEYS');

$query = "
			LOAD DATA CONCURRENT INFILE '" . $filename_output . "' IGNORE " .
    "INTO TABLE partners_cross " .
    "CHARACTER SET 'UTF8' " .
    "FIELDS TERMINATED BY '(|)' " .
    ' LINES TERMINATED BY "\n"  ' .
    "(main_brand,main_article,main_article_form,repl_brand,repl_article,repl_article_form,name,quality,check_dub_key)";

$res = database::updateData($query);
database::updateData('ALTER TABLE partners_prices_stocks ENABLE KEYS');

unlink($filename_output);
unlink($filename);
