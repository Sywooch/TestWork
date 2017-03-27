<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Перевод';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'recipient_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sum')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Перевести' , ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
