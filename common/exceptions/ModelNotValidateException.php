<?php

namespace common\exceptions;

use service\components\Utility;
use yii\base\Model;

class ModelNotValidateException extends \Exception
{
    private $_model;

    public function __construct(Model $model = null, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->_model = $model;
        if ($message === null && $model) {
            $message = $this->getModelErrorsString($model);
        }
        parent::__construct($message, $code, $previous);
    }

    public function getModel(): ?Model
    {
        return $this->_model;
    }

    private function getModelErrorsString(Model $model): string
    {
        $errorList = $model->getFirstErrors();
        if (empty($errorList)){
            return "";
        }
        if (is_array($errorList)){
            return implode(PHP_EOL, $errorList);
        }
        return "";
    }
}
