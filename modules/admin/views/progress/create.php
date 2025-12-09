<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Progress $model */

$this->title = Yii::t('app', 'Create Progress');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Progresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="progress-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
