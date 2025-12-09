<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Category $model */

$this->title = Yii::t('app', 'Изменение категории: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="category-update">

    <div class="form-reg">
        <div class="title-reg">
            <h1 class="zag-admin"><?= Html::encode($this->title) ?></h1>
        </div>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
