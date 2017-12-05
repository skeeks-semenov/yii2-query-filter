<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 05.12.2017
 */

namespace skeeks\yii2\queryfilter;

use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;
use yii\widgets\ActiveForm;

/**
 * Interface IQueryFilterHandler
 * @package skeeks\yii2\queryfilter
 */
interface IQueryFilterHandler
{
    /**
     * @return mixed
     */
    public function init();

    /**
     * @param QueryInterface $activeQuery
     * @return static
     */
    public function initQuery(QueryInterface $activeQuery);

    /**
     * @param DataProviderInterface $dataProvider
     * @return static
     */
    public function initDataProvider(DataProviderInterface $dataProvider);

    /**
     * @param $data
     */
    public function load($data);

    /**
     * @param string $code
     * @return string
     */
    public function renderByAttribute($code, ActiveForm $form);
}