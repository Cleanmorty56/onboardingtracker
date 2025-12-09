<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Module $model */

$this->title = Yii::t('app', 'Изменение модуля: {name}', [
    'name' => $model->title,
]);
?>
<div class="module-update">
    <div class="form-reg">
        <div class="title-reg">
            <h1 class="zag-admin"><?= Html::encode($this->title) ?></h1>
        </div>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
