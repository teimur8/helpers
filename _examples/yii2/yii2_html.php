<?php
?>


<?= Html::a('Reject', ['moderate', 'id' => $model->id, 'action' => 'reject'], ['class' => 'btn btn-danger', 'data-method' => 'post', 'data-confirm' => 'Уверены?']) ?>
