<?php

/**
 * @date 2017/7/10 13:38:45
 */

namespace Aw\Validator;

abstract class Validator
{
    /**
     *
     * @var boolean|\Closure whether the attribute value can be null or empty. Defaults to true,
     *      meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty = false;
    /**
     *
     * @var boolean whether the comparison to {@link trueValue} and {@link falseValue} is strict.
     *      When this is true, the attribute value and type must both match those of {@link trueValue} or {@link falseValue}.
     *      Defaults to false, meaning only the value needs to be matched.
     */
    public $strict = false;
    /**
     *
     * @var string {attribute} as placeholder
     */
    public $message;

    /**
     *
     * @param $value
     * @return bool
     */
    abstract public function validate($value);

    /**
     * Checks if the given value is empty.
     * A value is considered empty if it is null, an empty array, or an empty string.
     * Note that this method is different from PHP empty(). It will return false when the value is 0.
     *
     * @param mixed $value
     *            the value to be checked
     * @return bool whether the value is empty
     */
    public function isEmpty($value)
    {
        if (is_callable($value)) {
            return call_user_func($this->allowEmpty, $value);
        } else {
            return $value === null || $value === array() || $value === '';
        }
    }

    public function setEmpty($closure)
    {
        $this->allowEmpty = $closure;
    }
}