<?php

/**
 * @date 2017/7/10 13:38:45
 */

namespace Aw\Validator;

class DateValidator extends Validator
{
    const MODE_DATETIME = 0;
    const MODE_DATE = 1;
    const MODE_TIME = 2;
    const MODE_YEAR = 3;
    /**
     *
     * @var string datetime/date/time/year
     */
    public $mode = DateValidator::MODE_DATETIME;

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->allowEmpty && $this->isEmpty($value))
            return true;
        switch ($this->mode) {
            case DateValidator::MODE_DATETIME:
                return !!preg_match("/^(\d{4})[\/\-\.](0?[1-9]|1[012])[\/\-\.](0?[1-9]|[12][0-9]|3[01]) (0?[0-9]|[1-6][0-9]):(0?[0-9]|[1-6][0-9]):(0?[0-9]|[1-6][0-9])$/", $value);
            case DateValidator::MODE_DATE:
                return !!preg_match("/^(\d{4})[\/\-\.](0?[1-9]|1[012])[\/\-\.](0?[1-9]|[12][0-9]|3[01])$/", $value);
            case DateValidator::MODE_TIME:
                return !!preg_match("/^(0?[0-9]|[1-6][0-9]):(0?[0-9]|[1-6][0-9]):(0?[0-9]|[1-6][0-9])$/", $value);
            case DateValidator::MODE_YEAR:
                return !!preg_match("#^\d{4}$#", $value);
            default:
                break;
        }
        return false;
    }
}
