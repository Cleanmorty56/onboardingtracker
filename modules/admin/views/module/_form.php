<?php


use app\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Module $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="module-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(Category::find()->all(), 'id', 'name'),
        ['prompt' => 'Выберите категорию']
    ) ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn-form']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
