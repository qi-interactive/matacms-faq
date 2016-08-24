<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use matacms\widgets\ActiveForm;

?>

<div class="faq-subject">

    <?php $form = ActiveForm::begin([
        "id" => "form-faq-subject"
        ]);
    ?>

        <?= $form->field($model, 'Subject') ?>

        <?= $form->submitButton($model) ?>

    <?php ActiveForm::end(); ?>

</div>
