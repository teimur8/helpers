<?php

$orderDocData =  [
    'header'   => 'Список заказов за рассчетный период с %s по %s',
    'client'   => 'Покупатель 1',
    'products' => [
        'items'     => [],
        'sum'       => [0, 0, 0],
        'nds'       => 10009.28,
        'sumString' => [
            'Всего наименований 10, на сумму 123 660,00 KZT',
            '1 двадцать три тысячи шестьсот шестьдесят тенге 00 тиын',
        ]
    ]
];

$temp['header'] = sprintf($temp['header'], @$data['period_start'], @$data['period_end']);
$temp['client'] = $orders[0]['client_name'];

function createCurrentOrderXlsx($data)
{
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objReader->load(VIEWS . "doc_template/current_order_template.xlsx");
    
    $objPHPExcel->getActiveSheet()->setCellValue('B1', $data['header']);
    $objPHPExcel->getActiveSheet()->setCellValue('H7', $data['client']);
    
    $objPHPExcel->getActiveSheet()->setCellValue('V12', $data['products']['sum'][0]); // Сумма без скидки
    $objPHPExcel->getActiveSheet()->setCellValue('Z12', $data['products']['sum'][1]); // Скидка
    $objPHPExcel->getActiveSheet()->setCellValue('AC12', $data['products']['sum'][2]); // Сумма
    $objPHPExcel->getActiveSheet()->setCellValue('AC13', $data['products']['nds']); // ндс
    
    $objPHPExcel->getActiveSheet()->setCellValue('B15', $data['products']['sumString'][0]); // Всего наименований ... на сумму ...
    $objPHPExcel->getActiveSheet()->setCellValue('B16', $data['products']['sumString'][1]);
    
    $objPHPExcel->getActiveSheet()->setCellValue('B17', sprintf('Дата формирования: %s', date('d.m.Y H:i', time())));
    
    $row1 = 10;
    $productLetters = [
        'B', // #
        'D', // Дата заказа
        'G', // Номер заказа
        'J', // Артикул
        'M', // Наименование
        'P', // Кол-во
        'Q', // Ед.
        'R', // Статус
        'S', // Ожидаемая дата доставки
        'T', // Цена
        'V', // Сумма без скидки
        'Z', // Скидка
        'AC' // Сумма
    ];
    
    foreach ($data['products']['items'] as $item) {
        
        // last iteration
        if ($item === end($data['products']['items'])) {
            $objPHPExcel->getActiveSheet()->removeRow($row1, 1);
        } else {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row1 + 1, 1);
            $this->copyRange($objPHPExcel->getActiveSheet(), "B$row1:AC$row1", 'B' . ($row1 + 1));
        }
        
        foreach ($productLetters as $k => $v) {
            $cell = $v . $row1;
            $objPHPExcel->getActiveSheet()->setCellValue($cell, $item[$k]);
        }
        
        $row1++;
    }
    
    return $objPHPExcel;
}

$orderDoc = $dc->createCurrentOrderXlsx($orderDocData);

$dc->download_file_xlsx($orderDoc, $doc . '.xlsx');

function download_file_xlsx($objPHPExcel, $file_name, $type = 'Excel2007')
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

function download_file_csv($file, $file_name = 'test.csv')
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
