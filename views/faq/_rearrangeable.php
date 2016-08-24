<?php

use yii\web\View;
use yii\helpers\Html;
use matacms\environment\models\ItemEnvironment;

$environmentModule = \Yii::$app->getModule("environment");

?>

<h3>Rearrange <?= \Yii::$app->controller->id ?></h3>

<?php
if(\mata\helpers\BehaviorHelper::hasBehavior(Yii::$app->controller->getModel(), \matacms\language\behaviors\LanguageBehavior::class) && count(\Yii::$app->getModule('language')->getSupportedLanguages()) > 1):
?>
<div class="language-versions" style="display:block;margin-left:10px;margin-bottom: 20px;">
<?php
$language = \Yii::$app->request->get('Language');
$subjectId = \Yii::$app->request->get('SubjectId');
foreach(\Yii::$app->getModule('language')->getSupportedLanguages() as $locale => $name):
	$cssClass = 'btn btn-primary';
	$isSelected = ($language && $language == $locale);
	if($isSelected) {
		$cssClass = 'btn btn-warning';
		$selectedLanguage = $locale;
		$languageURL = 'javascript:void(0)';
	}
	else {
		$languageURL = ['rearrangeable', 'SubjectId' => $subjectId, 'Language' => $locale];
	}
?>
	<?= Html::a($name, $languageURL, ['class' => $cssClass, 'data-url' => \yii\helpers\Url::to(['rearrangeable', 'SubjectId' => $subjectId, 'Language' => $locale]), 'disabled' => $isSelected, 'style' => 'margin-right:5px;']) ?>
<?php endforeach; ?>
</div>
<?php endif; ?>

<ol class="smooth-sortable overlay-list-container" data-rearrange-action-url="<?= $rearrangeActionUrl ?>">
	<?php
	foreach($dataProvider->models as $model):
		?>
	<li data-entity-pk="<?= \mata\helpers\ActiveRecordHelper::getPk($model) ?>"><?= $model->getLabel(); ?>
        <div class="svg-icon-container">

            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 21 16" enable-background="new 0 0 21 16" xml:space="preserve">
            <g class="rearrangeable-icon">

                <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                11.6,5.1 15.8,0.9 20.1,5.1  "/>

                <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                15.8,0.9 15.8,15.1 11.4,15.1    "/>

                <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                9.4,10.9 5.2,15.1 0.9,10.9  "/>

                <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4031" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                5.2,15.1 5.2,0.9 9.6,0.9    "/>
            </g>
            <g class="tick-icon">

                <polyline fill="none" stroke="#5bbc60" stroke-width="4.25" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                17.8,2.2 7.2,13.8 2.2,7.9   "/>
            </g>
        </svg>
    </div>
    <?php if(!$environmentModule->hasEnvironmentBehavior($model)): ?>
        <div class="fadding-container"> </div>
    <?php endif; ?>

    <?php if($environmentModule->hasEnvironmentBehavior($model)):
    $ie = ItemEnvironment::find()->where([
        "DocumentId" => $model->getDocumentId()->getId(),
        "Revision" => $model->_revision->Revision,
        ])->one();

    $evironmentClass = 'draft';
    $isLive = false;
    $delta = 0;
    $status = 'DRAFT';

    if($model->hasLiveVersion()) {
        $eventDateAttribute = $model->getEventDateAttribute();
        $isLive = $model->$eventDateAttribute > date('Y-m-d H:i:s');
        $evironmentClass = !$isLive ? 'live' : 'scheduled';
        $delta = $model->getRevisionDelta();
        $status = $isLive ? 'SCHEDULED' : Yii::$app->getModule("environment")->getLiveEnvironment();
    }

        ?>
    <div class="small-list list-version-container <?= strtolower($evironmentClass) ?>">
        <div class="fadding-container"> </div>
        <div class="list-version-inner-container">
            <div class="version-status">
                <span><?= $status; ?></span>
            </div>
            <?php if ($delta > 0): ?>
                <div class="revision-delta">
                    <?= "+ " . $delta . " versions ahead";
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

</li>
<?php endforeach; ?>
</ol>

<?php

$csrf = \Yii::$app->request->getCsrfToken();
$script = <<< JS

matacms.rearrange.init();

$('.smooth-sortable li').draggable(
{
    axis: 'y',
    containment: 'parent',
    scroll: 'true',
    helper: 'original',
    start: matacms.rearrange.sortable.start,
    drag: matacms.rearrange.sortable.drag.throttle(17),
    stop: function(event, ui) {

    	$('.smooth-sortable li').draggable('disable');

        $(matacms.rearrange.sortable.items[matacms.rearrange.sortable.dragItemIndex].node).css({
            'top': matacms.rearrange.sortable.items[matacms.rearrange.sortable.dragItemIndex].displacement,
            'z-index': 9999
        });

setTimeout(function() {
            // Keep the dragged item on top of other items during transition and then reset the Z-Index
    $(matacms.rearrange.sortable.items[matacms.rearrange.sortable.dragItemIndex].node)[0].style.zIndex = '';
            // Rewrite the dom to match the new order after everthing else is done.
    matacms.rearrange.sortable.items.forEach(function(item, i, items) {
        $(item.node).css('top', 0);
        $('.smooth-sortable').append(item.node);
    });

            // Re-enable dragging.

var actionUrl = $('.smooth-sortable').data('rearrange-action-url');
var csrf = "$csrf";
var items = $('.smooth-sortable li');
var pks = $.map(items, function(item) {
    return $(item).data("entity-pk");
});

var tickIcon = $('.tick-icon', ui.helper);
var rearrangeableIcon = $('.rearrangeable-icon', ui.helper);


$.ajax({
    type: "POST",
    url: actionUrl,
    data: {"pks":pks, "_matacmscsrf": csrf},
    dataType: "json",
    success: function(data) {
        console.log("success");

        tickIcon.fadeOut(250);
        rearrangeableIcon.fadeOut(250);


        tickIcon.fadeIn(100, function() {
            setTimeout(function() {
                tickIcon.fadeOut(250);
                rearrangeableIcon.fadeIn(250);
            }, 2500);
});
},
error: function() {
 console.log("error");
}
});

$('.smooth-sortable li').draggable('enable');
}, matacms.rearrange.sortable.transitionDuration);
}

}
);
JS;

$this->registerJs($script, View::POS_READY);

if(\mata\helpers\BehaviorHelper::hasBehavior(Yii::$app->controller->getModel(), \matacms\language\behaviors\LanguageBehavior::class) && count(\Yii::$app->getModule('language')->getSupportedLanguages()) > 1):

$this->registerJs("

	$('.language-versions a.btn').on('click', function(){
		if(!$(this).attr('disabled')) {
			mata.simpleTheme.reloadRearrangeData($(this).attr('data-url'));
		}
		return false;
	});

", View::POS_READY);

endif;
?>
