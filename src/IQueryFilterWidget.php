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
 * Interface IQueryFilterWidget
 * @package skeeks\yii2\queryfilter
 */
interface IQueryFilterWidget
{

    /**
     * @param QueryInterface $activeQuery
     * @return static
     */
    public function applyToQuery(QueryInterface $activeQuery);

    /**
     * @param DataProviderInterface $dataProvider
     * @return static
     */
    public function applyToDataProvider(DataProviderInterface $dataProvider);

    /**
     * @param $data
     */
    public function load($data);
}