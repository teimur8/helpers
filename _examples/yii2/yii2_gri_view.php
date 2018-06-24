<?php
/**
 * https://p0vidl0.info/yii2-razbiraemsya-s-gridview.html
 */

/* @var $searchModel common\entities\news\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],


        // DatePicker
        [
            'attribute' => 'created_at',
            'filter'    => kartik\date\DatePicker::widget([
                'model'         => $searchModel,
                'attribute'     => 'date_from',
                'attribute2'    => 'date_to',
                'type'          => kartik\date\DatePicker::TYPE_RANGE,
                'separator'     => '-',
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'autoclose'      => true,
                    'format'         => 'yyyy-mm-dd',
                ],
            ]),
            'format'    => 'datetime',
        ],
        [
            'attribute' => 'role',
            'class'     => RoleColumn::class,
            'filter'    => $searchModel->rolesList(),
        ],
        [
            'attribute' => 'status',
            'filter'    => UserHelper::statusList(),
            'value'     => function(User $model){
                return UserHelper::statusLabel($model->status);
            },
            'format'    => 'raw',
        ],

        'title',
        'owner.phone',
        'description:ntext',
        [
            'attribute' => 'status',
            'filter'    => $searchModel->statusList(),
            'value'     => function(\common\entities\news\News $model){
                return \common\dictionaries\NewsStatus::get($model->status);
            },
            'format'    => 'raw',
        ],

        [
            'attribute' => 'role',
            'class'     => RoleColumn::class,
            'filter'    => $searchModel->rolesList(),
        ],

        ['class' => 'yii\grid\ActionColumn']
    ]
]);

// NewsSearch.php
function statusList(): array
{
    return NewsStatus::all();
}


function statusLabel($status): string
{
    switch ($status)
    {
        case User::STATUS_WAIT:
            $class = 'label label-default';
            break;
        case User::STATUS_ACTIVE:
            $class = 'label label-success';
            break;
        default:
            $class = 'label label-default';
    }

    return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
        'class' => $class,
    ]);
}




// custom column
use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\rbac\Item;

class RoleColumn extends DataColumn
{
    protected function renderDataCellContent($model, $key, $index): string
    {
        $roles = Yii::$app->authManager->getRolesByUser($model->id);
        return $roles === [] ? $this->grid->emptyCell : implode(', ', array_map(function (Item $role) {
            return $this->getRoleLabel($role);
        }, $roles));
    }

    private function getRoleLabel(Item $role): string
    {
        $class = $role->name == Rbac::ROLE_USER ? 'primary' : 'danger';
        return Html::tag('span', Html::encode($role->description), ['class' => 'label label-' . $class]);
    }
}
