<?php
/**
 * @date 2017/7/10 13:38:45
 */
namespace Tian\Validator;

class BooleanValidator extends Validator {
	/**
	 *
	 * @var mixed the value representing true status. Defaults to '1'.
	 */
	public $trueValue = '1';
	/**
	 *
	 * @var mixed the value representing false status. Defaults to '0'.
	 */
	public $falseValue = '0';
	/**
	 *
	 * @var boolean whether the comparison to {@link trueValue} and {@link falseValue} is strict.
	 *      When this is true, the attribute value and type must both match those of {@link trueValue} or {@link falseValue}.
	 *      Defaults to false, meaning only the value needs to be matched.
	 */
	public $strict = false;
	/**
	 *
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 *      meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;
	public function validate($value) {
		if ($this->allowEmpty && $this->isEmpty ( $value ))
			return true;
		if (! $this->strict && $value != $this->trueValue && $value != $this->falseValue || $this->strict && $value !== $this->trueValue && $value !== $this->falseValue) {
			$this->message = '{attribute} must be either true or false.';
			return false;
		}
		return true;
	}
}
