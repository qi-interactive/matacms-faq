<?php

use yii\helpers\Html;
use mata\arhistory\behaviors\HistoryBehavior;
use matacms\theme\simple\assets\ModuleUpdateAsset;

$this->title = 'Update ' . $model->getModelLabel() . ': ' . ' ' . $model->getLabel();

ModuleUpdateAsset::register($this);

?>

<div><?php // echo Html::a("Back to list view", sprintf("/mata-cms/%s/%s", $this->context->module->id, $this->context->id), ['id' => 'back-to-list-view']);?></div>

<div class="content-block-update">

	<h1><?= $this->title ?></h1>

	<?= $this->render("_form", [
		'model' => $model,
		]) ?>

	</div>

	<?= $this->render('@vendor/matacms/matacms-base/views/module/_overlay'); ?>

<script>

	parent.mata.simpleTheme.header
	.setBackToListViewURL("<?= sprintf("/mata-cms/%s/%s", $this->context->module->id, $this->context->id) ?>")
	.showBackToListView()
	.setVersionsURL('<?= sprintf("/mata-cms/%s/%s/history?documentId=%s&returnURI=%s", $this->context->module->id, $this->context->id, urlencode($model->getDocumentId()->getId()), Yii::$app->request->url) ?>')
	.showVersions()
	.show();

</script>
