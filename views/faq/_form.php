<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use matacms\widgets\ActiveForm;

use matacms\faq\models\FaqSubject;

$subjects = FaqSubject::find()->with('questions')->all();
$subjectsArray = ArrayHelper::map($subjects, 'Id', 'Subject');

?>

<style>

.redactor-editor .custom-border-section {
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    margin: 10px 0;
    padding: 10px 0;
}

.redactor-editor .custom-border-section div {
    margin: 0;
}
.redactor-editor .custom-border-section p {
    line-height: 20px;
    margin: 0;
}
</style>

<div class="faq">

    <?php $form = ActiveForm::begin([
        "id" => "form-faq"
        ]);
    ?>

        <?= $form->field($model, 'Question') ?>

        <?= $form->field($model, 'Answer')->wysiwyg() ?>

        <?= $form->field($model, 'SubjectId')->dropDownList($subjectsArray, ['prompt' => 'Select Subject', 'clientOptions' => ['create' => true]]); ?>

        <?= $form->submitButton($model) ?>

    <?php ActiveForm::end(); ?>

</div>
