<?php

/**
 * @date 2017/7/10 17:19:45
 */

namespace Aw\Validator;

class UrlValidator extends Validator
{
    /**
     *
     * @var string the regular expression used to validates the attribute value.
     */
    public $pattern = '/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';
    /**
     *
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     *      meaning that if the attribute is empty, it is considered valid.
     */
    public $allowEmpty = true;

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($this->allowEmpty && $this->isEmpty($value))
            return true;
        if (!$this->validateValue($value)) {
            $this->message = '{attribute} is not a valid URL.';
            return false;
        }
        return true;
    }

    protected function validateValue($value)
    {
        return is_string($value) && preg_match($this->pattern, $value);
    }
}

