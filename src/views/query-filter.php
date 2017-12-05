<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 13.11.2017
 */
/* @var $this yii\web\View */
/* @var $this \skeeks\yii2\queryfilter\QueryFilterWidget */
$wiget = $this->context;
?>
<? $form = \yii\widgets\ActiveForm::begin([
    'method' => 'post',
    'action' => "/" . \Yii::$app->request->pathInfo,
    'options' => [
        'data' => [
            'pjax' => 1
        ]
    ]
]); ?>

<? foreach ($wiget->hand as $filtersHandler) : ?>
    <?= $filtersHandler->render($form); ?>
<? endforeach; ?>

<? $form::end(); ?>
