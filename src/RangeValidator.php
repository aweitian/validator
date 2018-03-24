<?php

/**
 * @date 2017/7/10 13:38:45
 */

namespace Aw\Validator;

class RangeValidator extends Validator
{
    /**
     *
     * @var array list of valid values that the attribute value should be among
     */
    public $range;
    /**
     *
     * @var boolean whether the comparison is strict (both type and value must be the same)
     */
    public $strict = false;

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->allowEmpty && $this->isEmpty($value))
            return true;
        if (!is_string($value) && !is_numeric($value))
            return false;
        if (is_array($this->range) && !in_array($value, $this->range, $this->strict)) {
            $this->message = '{attribute} is not in the list.';
            return false;
        }
        return true;
    }
}

