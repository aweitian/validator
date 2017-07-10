<?php

/**
 * @date 2017/7/10 13:38:45
 */
namespace Tian\Validator;

abstract class Validator {
	/**
	 *
	 * @var string {attribute} as placeholder
	 */
	public $message;
	/**
	 *
	 * @param unknown $value        	
	 * @return bool
	 */
	abstract public function validate($value);
	/**
	 * Checks if the given value is empty.
	 * A value is considered empty if it is null, an empty array, or an empty string.
	 * Note that this method is different from PHP empty(). It will return false when the value is 0.
	 * 
	 * @param mixed $value
	 *        	the value to be checked
	 * @return bool whether the value is empty
	 */
	public function isEmpty($value) {
		if ($this->isEmpty !== null) {
			return call_user_func ( $this->isEmpty, $value );
		} else {
			return $value === null || $value === [ ] || $value === '';
		}
	}
}