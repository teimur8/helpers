<?php
[
    ['post_date', 'post_date_close'], 'datetime',
    'format' => 'php:Y-m-d H:i:s',
];
?>

<?= $form->field($model, 'post_date_close')->widget(\kartik\widgets\DateTimePicker::class, [
    'value'         => date('Y-m-d H:i:s'),
    'convertFormat' => true,
    'pluginOptions' => [
        'todayHighlight' => true,
        'todayBtn'       => true,
        'autoclose'      => true,
        'format'         => 'yyyy-MM-dd h:i:s',
    ]
]) ?>
