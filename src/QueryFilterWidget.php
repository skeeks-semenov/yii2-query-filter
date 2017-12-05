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
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class QueryFilterWidget
 * @package skeeks\yii2\queryfilter
 */
class QueryFilterWidget extends Widget
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
            $config['id'] = $id;
            $this->handlers[$id] = \Yii::createObject($config);
            if (!$this->handlers[$id] instanceof IQueryFilterHandler) {
                unset($this->handlers[$id]);
            }
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->registerAssets();
        return $this->render($this->viewFile);
    }

    public function registerAssets()
    {
        return $this;
    }

    /**
     * @param ActiveQuery $activeQuery
     * @return $this
     */
    public function search(ActiveQuery $activeQuery)
    {
        if ($this->filtersHandlers) {
            foreach ($this->filtersHandlers as $searchHandler) {
                $searchHandler->initQuery($activeQuery);
            }
        }

        return $this;
    }

    public $filtersParamName = 'f';

    protected $_data = [];

    public function loadFromRequest()
    {

        if ($data = \Yii::$app->request->post()) {
            //Чистить незаполненные
            if (isset($data[$this->filtersParamName])) {
                foreach ($data[$this->filtersParamName] as $key => $value) {
                    if (!$value) {
                        unset($data[$this->filtersParamName][$key]);
                    }
                }
            }
            if (isset($data['_csrf'])) {
                unset($data['_csrf']);
            }

            $this->_data = $data;
            $this->load($data);

            /*\Yii::$app->response->redirect($this->getFilterUrl());
            \Yii::$app->end();*/

            $newUrl = $this->getFilterUrl();
            \Yii::$app->view->registerJs(<<<JS
window.history.pushState('page', 'title', '{$newUrl}');
JS
            );


        } elseif ($data = \Yii::$app->request->get($this->filtersParamName)) {
            $data = (array)unserialize(base64_decode($data));
            $this->_data = $data;
            $this->load($data);
        }

        return $this;
    }

    public function getFilterUrl()
    {
        return \Yii::$app->request->absoluteUrl . "?" . http_build_query([
                $this->filtersParamName => base64_encode(serialize($this->_data))
            ]);
    }

    /**
     * @param $data
     * @return $this
     */
    public function load($data)
    {
        if ($this->filtersHandlers) {
            foreach ($this->filtersHandlers as $searchHandler) {
                $searchHandler->load($data);
            }
        }

        return $this;
    }

}