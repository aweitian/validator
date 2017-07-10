<?php

/**
 * @date 2017/7/10 17:19:45
 */
namespace Tian\Validator;

class RegularExpressionValidator extends CValidator {
	/**
	 *
	 * @var string the regular expression to be matched with
	 */
	public $pattern;
	/**
	 *
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 *      meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;
	
	/**
	 * @inheritdoc
	 */
	public function validateAttribute($value) {
		if ($this->allowEmpty && $this->isEmpty ( $value ))
			return true;
		if ($this->pattern === null)
			throw new Exception ( 'The "pattern" property must be specified with a valid regular expression.' );
		if (! preg_match ( $this->pattern, $value )) {
			$this->message = '{attribute} is invalid.';
			return false;
		}
		return true;
	}
}

