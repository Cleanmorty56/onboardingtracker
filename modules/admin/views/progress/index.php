<?php

use app\models\Progress;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ProgressSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Прогресс сотрудников');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="progress-index">

    <h1 class="zag-admin"><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'user.name',
            'module.title',
            'completed_at:datetime',
        ],
    ]) ?>


</div>
