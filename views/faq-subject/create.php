<?php

use yii\helpers\Html;
use matacms\theme\simple\assets\ModuleUpdateAsset;

ModuleUpdateAsset::register($this);
/* @var $this yii\web\View */
/* @var $model mata\contentblock\models\ContentBlock */

$this->title = sprintf('Create %s', \Yii::$app->controller->id);
$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-block-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render("_form", [
        'model' => $model,
    ]) ?>

</div>

<script>

	parent.mata.simpleTheme.header
	.showBackToListView()
	.setBackToListViewURL("<?= sprintf("/mata-cms/%s/%s", $this->context->module->id, $this->context->id) ?>")
	.hideVersions()
	.show();

</script>
