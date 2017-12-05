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
<? foreach ($wiget->hand as $filtersHandler) : ?>
    <? if ($filtersHandler->toArray()) :?>
        <? foreach ($filtersHandler->toArray() as $key => $value) : ?>
            <?= $filtersHandler->renderByAttribute($key); ?>
        <? endforeach;  ?>
    <? endif;  ?>
<? endforeach;  ?>
