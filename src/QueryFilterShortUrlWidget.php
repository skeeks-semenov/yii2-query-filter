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
 * @property string $filterUrl
 *
 * Class QueryFilterWidget
 * @package skeeks\yii2\queryfilter
 */
class QueryFilterShortUrlWidget extends QueryFilterWidget
{
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
}