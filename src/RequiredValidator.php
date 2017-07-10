<?php

/**
 * @date 2017/7/10 17:19:45
 */
namespace Tian\Validator;

class RequiredValidator extends Validator {
	/**
	 *
	 * @var mixed the desired value that the attribute must have.
	 *      If this is null, the validator will validate that the specified attribute does not have null or empty value.
	 *      If this is set as a value that is not null, the validator will validate that
	 *      the attribute has a value that is the same as this property value.
	 *      Defaults to null.
	 * @since 1.0.10
	 */
	public $requiredValue;
	/**
	 *
	 * @var boolean whether the comparison to {@link requiredValue} is strict.
	 *      When this is true, the attribute value and type must both match those of {@link requiredValue}.
	 *      Defaults to false, meaning only the value needs to be matched.
	 *      This property is only used when {@link requiredValue} is not null.
	 * @since 1.0.10
	 */
	public $strict = false;
	/**
	 * @inheritdoc
	 */
	public function validate($value) {
		if ($this->requiredValue !== null) {
			if (! $this->strict && $value != $this->requiredValue || $this->strict && $value !== $this->requiredValue) {
				$this->message = strtr ( '{attribute} must be {value}.', array (
						'{value}' => $this->requiredValue 
				) );
				return false;
			}
		} else if ($this->isEmpty ( $value, true )) {
			$this->message = '{attribute} cannot be blank.';
			return false;
		}
		return true;
	}
}
