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

            if (isset($data['_csrf'])) {
                unset($data['_csrf']);
            }

            foreach ($data as $handlerName => $handlerData) {
                if (is_array($data[$handlerName])) {
                    foreach ($data[$handlerName] as $key => $value) {
                        if (!$value && $value != '0') {
                            unset($data[$handlerName][$key]);
                        }
                    }
                }
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
        $dataTmp = $this->_data;
        ksort($dataTmp);

        $data = [
            $this->filtersParamName => base64_encode(serialize($dataTmp))
        ];
        $data = array_merge((array) $_GET, $data);

        \Yii::$app->request->setQueryParams($data);

        $url = \Yii::$app->request->absoluteUrl;

        if ($pos = strpos($url, "?")) {
            $url = substr($url, 0, $pos);
        }

        ArrayHelper::remove($data, 'id');
        return $url . "?" . http_build_query($data);
    }
}