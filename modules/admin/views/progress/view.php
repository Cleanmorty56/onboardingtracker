<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Progress $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Progresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="progress-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'user_id',
                'label' => 'Сотрудник',
                'value' => function($model) {
                    return $model->user->name;
                }
            ],
            [
                'attribute' => 'module_id',
                'label' => 'Модуль',
                'value' => function($model) {
                    return $model->module->title;
                }
            ],
            'completed_at',
        ],
    ]) ?>

</div>
