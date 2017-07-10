<?php

/**
 * @date 2017/7/10 13:38:45
 */
namespace Tian\Validator;

class RangeValidator extends Validator {
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
	 *
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 *      meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;
	
	/**
	 * @inheritdoc
	 */
	public function validate($value) {
		if ($this->allowEmpty && $this->isEmpty ( $value ))
			return true;
		if (is_array ( $this->range ) && ! in_array ( $value, $this->range, $this->strict )) {
			$this->message = '{attribute} is not in the list.';
			return false;
		}
		return true;
	}
}

