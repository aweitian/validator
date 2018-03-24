<?php
/**
 * @date 2017/7/10 13:38:45
 */

namespace Aw\Validator;

class BooleanValidator extends Validator
{
    /**
     *
     * @var boolean whether the comparison to {@link trueValue} and {@link falseValue} is strict.
     *      When this is true, the attribute value and type must both match those of {@link trueValue} or {@link falseValue}.
     *      Defaults to false, meaning only the value needs to be matched.
     */
    public $strict = false;


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
