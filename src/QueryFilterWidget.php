<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
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

    public $activeFormConfig = [];

    public function init()
    {
        parent::init();

        $this->activeFormConfig = ArrayHelper::merge([
            'method' => 'post',
            'action' => "/" . \Yii::$app->request->pathInfo,
            'options' => [
                'data' => [
                    'pjax' => 1
                ]
            ]
        ], $this->activeFormConfig);

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
    public function registerHandler(IQueryFilterHandler $queryFilterHandler, $name = null)
    {
        if ($name && is_string($name)) {
            $this->handlers[$name] = $queryFilterHandler;
        } else {
            $this->handlers[] = $queryFilterHandler;
        }

        return $this;
    }


    /**
     * @param string $name
     * @return IQueryFilterHandler
     */
    public function getHandler(string $name)
    {
        return ArrayHelper::getValue($this->handlers, $name);
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
     * @return bool
     */
    public function load($data)
    {
        $success = true;

        if ($this->handlers) {
            foreach ($this->handlers as $searchHandler) {
                if (!$searchHandler->load($data)) {
                    $r = new \ReflectionClass($searchHandler);
                    //\Yii::error('Not load data to: '.$r->getName()."; data: ".print_r($data, true));
                    $success = false;
                }
            }
        }

        return $success;
    }
}