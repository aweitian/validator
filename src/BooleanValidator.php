<?php
/**
 * @date 2017/7/10 13:38:45
 */

namespace Aw\Validator;

class BooleanValidator extends Validator
{
    public function validate($value)
    {
        if ($this->allowEmpty && $this->isEmpty($value))
            return true;
        if ($this->strict)
        {
            return $value === true;
        }
        return !!$value;
    }
}
