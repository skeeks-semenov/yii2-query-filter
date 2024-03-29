<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
/* @var $this yii\web\View */
/* @var $this \skeeks\yii2\queryfilter\QueryFilterWidget */
$wiget = $this->context;
?>
<? $form = \yii\widgets\ActiveForm::begin($wiget->activeFormConfig); ?>
<? foreach ($wiget->hand as $filtersHandler) : ?>
    <?= $filtersHandler->render($form); ?>
<? endforeach; ?>

<? $form::end(); ?>
