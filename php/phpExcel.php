<?php

/**
 * Отрисовывает excel
 */
class doc_controller
{

    /**
     * Создает прас формата .xlsx для распродаж и search_result и возращает $objPHPExcel.
     * Если будет много занчений, может довольно долго генериться.
     */
    public function salesPriceCreateXlsx($data, $price_type = null, $getStores = true)
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load(VIEWS."doc_template/sales_template.xlsx");

        // указываем дату создания прайса
        $date_string =  (new DateTime())->format('Y-m-d');
        $objPHPExcel->getActiveSheet()->setCellValue('D2', 'Прайс лист от '. $date_string);

        $store_col = 6;     // начала склада
        $rows_count = 0;    // количество записей
        $stores = [];       // склады
        $row = 8;           // начала ввода

        foreach ($data['search_result'] as $item1) {
            if(empty($item1['stocks']) && count($item1['stocks']) == 0) continue; // если товаров нет

            // прорисовывает каждый товар
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $item1['manufacturer']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $item1['oem']);  // артикул
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $item1['name']); // наименование

            $car_appl = current($item1['stocks']);
            $car_appl_val = $car_appl['params']['car_appl'];
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row,  $car_appl_val); // применимость


            foreach ($item1['stocks'] as $stock_item) {

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $this->salesPrice($stock_item, $price_type)); // цена

                if($getStores){
                    // собирает склады и ставит им номер ряда
                    if(!array_key_exists($stock_item['store_id'], $stores)){
                        $stores[$stock_item['store_id']] = ['name'=>$stock_item['store_name'], 'col' => $store_col];
                        $store_col++;
                    }

                    // ставит значения остатков по складам согласно рядя
                    $store_col1 = $stores[$stock_item['store_id']]['col'];
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($store_col1, $row,  $this->salesQuantity($stock_item));
                }
            }

            $row++;
            $rows_count++;
        }

        // стили для тела
        $row_style = [
            'borders'   => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),),
            'alignment' => array(
//                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
                'wrap'       => false
            ),
        ];

        for($i = 8; $rows_count + 8 > $i; $i ++){   // ряды
            for($i2 = 1; $store_col > $i2; $i2 ++){ // линии
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($i2, $i)->applyFromArray($row_style);
            }
        }

        // стили для заголовка
        $header_style = [
            'font'      => array(
                'bold'  => true,
                'name'  => 'Arial',
                'color' => array(
                    'rgb' => 'ffffff'
                ),
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap'       => true
            ),
            'borders'   => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),),
            'fill'      => array(
                'type'       => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'argb' => 'FF0000',
                )
            ),
        ];
        foreach ($stores as $key => $item) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item['col'], 7, $item['name']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($item['col'], 7)->applyFromArray($header_style);
        }

        //region адрес и телефон
        $address =  auth::getFilialAddress();
        $manager =  auth::getManagerPhone();
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 2, $address['filial_name'].', '.$address['filial_address']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 3, 'т.: '.$address['filial_contacts']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'сот.: '.$manager['phone'] ?? '');
        //endregion

        // автофильтр
//        $objPHPExcel->getActiveSheet()->setAutoFilter('B7:F7');
        // ширина брэнда
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

        return $objPHPExcel;
    }

    /**
     * Выводим HTTP-заголовки
     * и скачиваем фаил.
     */
    public function download_file_xlsx($objPHPExcel, $file_name, $type = 'Excel2007')
    {
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header ( "Content-Disposition: attachment; filename=".$file_name );

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $type);
        $objWriter->save('php://output');
        die;
    }

    public function download_file_csv($file, $file_name = 'test.csv')
    {
        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache;" );
        header ( "Content-type: text/csv;charset=utf-8;" );
        header ( "Content-Disposition: attachment; filename=".$file_name.';' );
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        fseek($file, 0);
        echo $contents = stream_get_contents($file);;
        fclose($file);
        die();
    }

    /**
     * Генерация csv фаила
     *
     */
    public function salesPriceCreateCsv($data, $price_type = null, $getStores = true)
    {
        $result = [];
        $stores = []; // склады
        $store_col = 4; // начало складов в таблице
        foreach ($data['search_result'] as $item1) {
            if(empty($item1['stocks'])) continue; // если товаров нет

            $items = [];
            // прорисовывает каждый товар
            $items[0] = $item1['manufacturer']; // бренд
            $items[1] = $item1['oem'];// артикул
            $items[2] = $item1['name'];//наименовани

//            $car_appl = current($item1['stocks']);
//            $car_appl_val = $car_appl['params']['car_appl'];
//            $items[3] = $car_appl_val;//Применяемость

//            $this->salesPrice($stock_item);

            foreach ($item1['stocks'] as $stock_item) {

                // собирает склады и ставит им номер ряда
                if(!array_key_exists($stock_item['store_id'], $stores)){
                    $stores[$stock_item['store_id']] = ['name'=>$stock_item['store_name'], 'col' => $store_col];
                    $store_col++;
                }

                $items[3] = $this->salesPrice($stock_item, $price_type);//цена

                // ставит значения остатков по складам согласно рядя
                $store_col1 = $stores[$stock_item['store_id']]['col'];
                $items[$store_col1] = $this->salesQuantity($stock_item);//наличие на складе
            }

            $result[] = $items;
        }


        //generate csv file
        ob_clean();
        $file = tmpfile();

        $stores_name = [];
        if($getStores){
            foreach ($stores as $item) {
                $stores_name[$item['col']] = $item['name'];
            }
        }else{ // удаляем склады
            for ($i = 0; count($result) > $i; $i++) {
                $result[$i] = array_slice($result[$i],0, 4);
            }
        }


        $file_header = array_merge([
            "Брэнд",
            "Артикул",
            "Наименование",
//            "Применяемость",
            "Цена"
        ], $stores_name);

//        var_dump($file_header);


        //region добавляю пустые строки для недостающих ключей и сортирую массив
        for($i = 0; count($result) > $i; $i ++){
            for($i2 = 0; count($file_header) > $i2; $i2 ++){
                if(!isset($result[$i][$i2])) $result[$i][$i2] = '---';
            }
            ksort($result[$i]);
        }
        //endregion

//        var_dump($result);die;

        //region адрес и телефон
        $address =  auth::getFilialAddress();
        $manager =  auth::getManagerPhone();
        fputcsv($file, [$address['filial_name'].', '.$address['filial_address']], ';');
        fputcsv($file, ['т.: '.$address['filial_contacts']], ';');
        fputcsv($file, ['т.: '.$address['filial_contacts']], ';');
        fputcsv($file, ['сот.: '.$manager['phone']], ';');
        //endregion

        fputcsv($file, $file_header, ';');
        for($i = 0; count($result) > $i; $i ++){
            fputcsv($file, $result[$i], ';');
        }

        return $file;
    }

    /**
     * ВЫсчитвает цену товара как на сайте.
     */
    public function salesPrice($stock_data, $type = 'fact')
    {
        if($type == 'fact'){
            if (isset($stock_data['overpriced']) && $stock_data['overpriced'] == true) {
                return $stock_data['overprice'];
            } else {
                if ($stock_data['base_price'] > $stock_data['price']) {
                    return $stock_data['base_price'];
                }
//                return $stock_data['price'] + ($stock_data['delivery_price'] ?? 0);
                return $stock_data['price'] + ($stock_data['delivery_price'] ?? 0);
            }
        }else{
            // для учета скидки
            return $stock_data['base_price'] > $stock_data['price'] ? $stock_data['price'] : $stock_data['base_price'];
        }
    }

    /**
     * Отадат количество как на сайте
     */
    public static function salesQuantity($stock_data)
    {
        if ($stock_data['quantity'] == 0) {
            return 'на заказ';
        } else if($stock_data['quantity'] < 10 ) {
            return $stock_data['quantity'];
        }else{
            return '> 10';
        }
    }


    /**
     * Генерирует фаил и отдает на скачивание.
     * @param $file_name
     * @param $type
     * @param $data
     */
    public  function downloadPriceWithDataByType($file_name, $type, $data)
    {
       $getStores = false;
       if(auth::hasRoles([R_ALL, CRM_PRICE_WITH_STORES])) $getStores = true;

        if($type == 'xlsx'){
            $obj = $this->salesPriceCreateXlsx($data, 'sale', $getStores);
            $this->download_file_xlsx($obj, $file_name.'xlsx');
        }else{
            $obj = $this->salesPriceCreateCsv($data, 'sale', $getStores);
            $this->download_file_csv($obj, $file_name.'csv');
        }
    }


    // скачивает csv
    public function saveCsv($obj)
    {
        $string = '';
        $string .= "\xEF\xBB\xBF"; // UTF-8 BOM
        fseek($obj, 0);
        $string .= stream_get_contents($obj);;
        fclose($obj);

        return $string;
    }

    /**
     * @param $sheet
     * @param $srcRange
     * @param $dstCell
     * https://stackoverflow.com/questions/34590622/copy-style-and-data-in-phpexcel
     */
    function copyRange($sheet, $srcRange, $dstCell)
    {
        // Validate source range. Examples: A2:A3, A2:AB2, A27:B100
        if (!preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $srcRange, $srcRangeMatch)) {
            // Wrong source range
            return;
        }
        // Validate destination cell. Examples: A2, AB3, A27
        if (!preg_match('/^([A-Z]+)(\d+)$/', $dstCell, $destCellMatch)) {
            // Wrong destination cell
            return;
        }

        $srcColumnStart = $srcRangeMatch[1];
        $srcRowStart = $srcRangeMatch[2];
        $srcColumnEnd = $srcRangeMatch[3];
        $srcRowEnd = $srcRangeMatch[4];

        $destColumnStart = $destCellMatch[1];
        $destRowStart = $destCellMatch[2];

        // For looping purposes we need to convert the indexes instead
        // Note: We need to subtract 1 since column are 0-based and not 1-based like this method acts.

        $srcColumnStart = PHPExcel_Cell::columnIndexFromString($srcColumnStart) - 1;
        $srcColumnEnd = PHPExcel_Cell::columnIndexFromString($srcColumnEnd) - 1;
        $destColumnStart = PHPExcel_Cell::columnIndexFromString($destColumnStart) - 1;

        $rowCount = 0;
        for ($row = $srcRowStart; $row <= $srcRowEnd; $row++) {
            $colCount = 0;
            for ($col = $srcColumnStart; $col <= $srcColumnEnd; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $style = $sheet->getStyleByColumnAndRow($col, $row);
                $dstCell = PHPExcel_Cell::stringFromColumnIndex($destColumnStart + $colCount) . (string)($destRowStart + $rowCount);
                $sheet->setCellValue($dstCell, $cell->getValue());
                $sheet->duplicateStyle($style, $dstCell);

                // Set width of column, but only once per row
                if ($rowCount === 0) {
                    $w = $sheet->getColumnDimensionByColumn($col)->getWidth();
                    $sheet->getColumnDimensionByColumn($destColumnStart + $colCount)->setAutoSize(false);
                    $sheet->getColumnDimensionByColumn($destColumnStart + $colCount)->setWidth($w);
                }

                $colCount++;
            }

            $h = $sheet->getRowDimension($row)->getRowHeight();
            $sheet->getRowDimension($destRowStart + $rowCount)->setRowHeight($h);

            $rowCount++;
        }

        foreach ($sheet->getMergeCells() as $mergeCell) {
            $mc = explode(":", $mergeCell);
            $mergeColSrcStart = PHPExcel_Cell::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[0])) - 1;
            $mergeColSrcEnd = PHPExcel_Cell::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[1])) - 1;
            $mergeRowSrcStart = ((int)preg_replace("/[A-Z]*/", "", $mc[0]));
            $mergeRowSrcEnd = ((int)preg_replace("/[A-Z]*/", "", $mc[1]));

            $relativeColStart = $mergeColSrcStart - $srcColumnStart;
            $relativeColEnd = $mergeColSrcEnd - $srcColumnStart;
            $relativeRowStart = $mergeRowSrcStart - $srcRowStart;
            $relativeRowEnd = $mergeRowSrcEnd - $srcRowStart;

            if (0 <= $mergeRowSrcStart && $mergeRowSrcStart >= $srcRowStart && $mergeRowSrcEnd <= $srcRowEnd) {
                $targetColStart = PHPExcel_Cell::stringFromColumnIndex($destColumnStart + $relativeColStart);
                $targetColEnd = PHPExcel_Cell::stringFromColumnIndex($destColumnStart + $relativeColEnd);
                $targetRowStart = $destRowStart + $relativeRowStart;
                $targetRowEnd = $destRowStart + $relativeRowEnd;

                $merge = (string)$targetColStart . (string)($targetRowStart) . ":" . (string)$targetColEnd . (string)($targetRowEnd);
                //Merge target cells
                $sheet->mergeCells($merge);
            }
        }
    }

    public function orderCreateXlsx()
    {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load(VIEWS . "doc_template/order_template.xlsx");

        $data = [
            'header' => 'Счет на оплату № 20180000001 от 1 марта 2018 г.',
            'client' => 'Покупатель 1',
            'reason' => 'Договор 1',
            'products'=>[
                'items' => [
                    [
                        1,
                        'article',
                        'name',
                        'count',
                        'ed',
                        1,
                        2,
                        3,
                        4,
                    ],
                ],
                'sum' =>[
                    100000,
                    220000,
                    30000,
                ],
                'nds'=>10009.28,
                'sumString'=> [
                    'Всего наименований 10, на сумму 123 660,00 KZT',
                    '1 двадцать три тысячи шестьсот шестьдесят тенге 00 тиын',
                ]
            ]
        ];


        $objPHPExcel->getActiveSheet()->setCellValue('B14', $data['header']);
        $objPHPExcel->getActiveSheet()->setCellValue('H20', $data['client']);
        $objPHPExcel->getActiveSheet()->setCellValue('H22', $data['reason']);

        $objPHPExcel->getActiveSheet()->setCellValue('N28', $data['products']['sum'][0]);
        $objPHPExcel->getActiveSheet()->setCellValue('R28', $data['products']['sum'][1]);
        $objPHPExcel->getActiveSheet()->setCellValue('U28', $data['products']['sum'][2]);
        $objPHPExcel->getActiveSheet()->setCellValue('S29', $data['products']['nds']);

        $objPHPExcel->getActiveSheet()->setCellValue('B31', $data['products']['sumString'][0]);
        $objPHPExcel->getActiveSheet()->setCellValue('B32', $data['products']['sumString'][1]);


        $row1 = 25;
        $productLetters = ['B', 'D', 'G', 'J', 'K', 'L', 'N', 'R', 'U'];

        foreach ($data['products']['items'] as $item) {

            // last iteration
            if ($item === end($data['products']['items'])){
                $objPHPExcel->getActiveSheet()->removeRow($row1, 1);
            }else{
                $objPHPExcel->getActiveSheet()->insertNewRowBefore($row1 + 1, 1);
                $this->copyRange($objPHPExcel->getActiveSheet(), "B$row1:U$row1", 'B' . ($row1 + 1));
            }

            foreach ($productLetters as $k => $v) {
                $cell = $v . $row1;
                $objPHPExcel->getActiveSheet()->setCellValue($cell, $item[$k]);
            }

            $row1++;
        }


        return $objPHPExcel;

    }

}
