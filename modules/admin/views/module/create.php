<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Module $model */

$this->title = Yii::t('app', 'Добавление модуля');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Modules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-create">
    <div class="form-reg">
        <div class="title-reg">
            <h1 class="zag-admin"><?= Html::encode($this->title) ?></h1>
        </div>
        <?= $this->render('_form', [
            'model' => $model,
            'categoryList' => $categoryList,
        ]) ?>
    </div>
</div>
