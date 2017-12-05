<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright (c) 2010 SkeekS
 * @date 13.11.2017
 */

namespace skeeks\yii2\queryfilter;

use common\models\V3pFeature;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;

/**
 * Class QueryFilterWidget
 * @package skeeks\yii2\queryfilter
 */
class QueryFilterWidget extends Widget implements IQueryFilterWidget
{
    /**
     * @var
     */
    public $viewFile = '@skeeks/yii2/queryfilter/views/query-filter';

    /**
     * @var IQueryFilterHandler[]
     */
    public $handlers = [];

    public function init()
    {
        parent::init();

        foreach ($this->handlers as $id => $config) {
            if (is_string($config)) {
                $config = ['class' => $config];
            }

            $handler = \Yii::createObject($config);
            if ($handler instanceof IQueryFilterHandler) {
                $this->handlers[] = $handler;
            }

        }
    }

    /**
     * @param IQueryFilterHandler $queryFilterHandler
     * @return $this
     */
    public function registerHandler(IQueryFilterHandler $queryFilterHandler)
    {
        $this->handlers[] = $queryFilterHandler;
        return $this;
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->registerAssets();
        return $this->render($this->viewFile);
    }

    /**
     * @return $this
     */
    public function registerAssets()
    {
        return $this;
    }

    /**
     * @param QueryInterface $activeQuery
     * @return $this
     */
    public function applyToQuery(QueryInterface $activeQuery)
    {
        if ($this->handlers) {
            foreach ($this->handlers as $searchHandler) {
                $searchHandler->applyToQuery($activeQuery);
            }
        }

        return $this;
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @return $this
     */
    public function applyToDataProvider(DataProviderInterface $dataProvider)
    {
        if ($this->handlers) {
            foreach ($this->handlers as $searchHandler) {
                $searchHandler->applyToDataProvider($activeQuery);
            }
        }

        return $this;
    }


    /**
     * @param $data
     * @return $this
     */
    public function load($data)
    {
        if ($this->handlers) {
            foreach ($this->handlers as $searchHandler) {
                $searchHandler->load($data);
            }
        }

        return $this;
    }
}