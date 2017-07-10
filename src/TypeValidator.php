<?php
/**
 * @date 2017/7/10 17:19:45
 */
namespace Tian\Validator;
class TypeValidator extends Validator
{
	/**
	 * @var string the data type that the attribute should be. Defaults to 'string'.
	 * Valid values include 'string', 'integer', 'float', 'date', 'time' and 'datetime'.
	 * Note that 'time' and 'datetime' have been available since version 1.0.5.
	 */
	public $type='string';
	/**
	 * @var string the format pattern that the date value should follow. Defaults to 'MM/dd/yyyy'.
	 * Please see {@link CDateTimeParser} for details about how to specify a date format.
	 * This property is effective only when {@link type} is 'date'.
	 */
	public $dateFormat='MM/dd/yyyy';
	/**
	 * @var string the format pattern that the time value should follow. Defaults to 'hh:mm'.
	 * Please see {@link CDateTimeParser} for details about how to specify a time format.
	 * This property is effective only when {@link type} is 'time'.
	 * @since 1.0.5
	 */
	public $timeFormat='hh:mm';
	/**
	 * @var string the format pattern that the datetime value should follow. Defaults to 'MM/dd/yyyy hh:mm'.
	 * Please see {@link CDateTimeParser} for details about how to specify a datetime format.
	 * This property is effective only when {@link type} is 'datetime'.
	 * @since 1.0.5
	 */
	public $datetimeFormat='MM/dd/yyyy hh:mm';
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;

	/**
	 * @inheritdoc
	 */
	public function validate ($value)
	{
		if($this->allowEmpty && $this->isEmpty($value))
			return true;

		if($this->type==='integer')
			$valid=preg_match('/^[-+]?[0-9]+$/',trim($value));
		else if($this->type==='float')
			$valid=preg_match('/^[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?$/',trim($value));
		else if($this->type==='date')
			$valid=CDateTimeParser::parse($value,$this->dateFormat)!==false;
	    else if($this->type==='time')
			$valid=CDateTimeParser::parse($value,$this->timeFormat)!==false;
	    else if($this->type==='datetime')
			$valid=CDateTimeParser::parse($value,$this->datetimeFormat)!==false;
		else
		{
			$this->message = 'Invalid type '.var_export($this->type,true);
			return false;

		}
		if(!$valid)
		{
			$message=$this->message!==null?$this->message : Yii::t('yii','{attribute} must be {type}.');
			$this->addError($object,$attribute,$message,array('{type}'=>$this->type));
		}
	}
}

