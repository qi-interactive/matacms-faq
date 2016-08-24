<?php

use yii\helpers\Html;
use matacms\theme\simple\assets\ModuleUpdateAsset;

ModuleUpdateAsset::register($this);
/* @var $this yii\web\View */
/* @var $model mata\contentblock\models\ContentBlock */

if($subject != null) {
    $this->title = sprintf('Create %s for %s', \Yii::$app->controller->id, $subject->getLabel());
    $backViewURL = sprintf("/mata-cms/%s/%s?SubjectId=%s", $this->context->module->id, $this->context->id, $subject->Id);
}
else {
    $this->title = sprintf('Create %s', \Yii::$app->controller->id);
    $backViewURL = sprintf("/mata-cms/%s/%s", $this->context->module->id, $this->context->id);
}


$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-block-create">

    <?= $this->render("_form", [
        'model' => $model,
    ]) ?>

</div>

<script>

	parent.mata.simpleTheme.header
	.showBackToListView()
	.setBackToListViewURL("<?= $backViewURL ?>")
	.hideVersions()
	.show();

</script>
