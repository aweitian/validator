<?php
/**
 * https://laravel.com/docs/5.4/validation#available-validation-rules
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 13:06
 */

namespace Aw\Validator;
class Rules
{
    const MODE_SINGLE = 0;
    const MODE_MUT = 1;
    protected $rules = array();
    protected $errors = array();
    protected $isBail = true;
    protected $isEmpty = null;
    protected $isStrict = null;
    protected $isArray = null;

    protected $data = array();

    protected $text = array();

    public $mode = self::MODE_MUT;


    protected $overrideErrors = array();

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return array
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param array $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return array
     */
    public function getOverrideErrors()
    {
        return $this->overrideErrors;
    }

    /**
     * @param array $overrideErrors
     */
    public function setOverrideErrors($overrideErrors)
    {
        $this->overrideErrors = $overrideErrors;
    }

    /**
     * @param $key
     * @param $rule
     * @return $this
     */
    public function addRule($key, $rule)
    {
        $this->rules[$key] = $rule;
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function removeRule($key)
    {
        if (isset($this->rules[$key])) {
            unset($this->rules[$key]);
        }
        return $this;
    }

    /**
     * @param array $rules
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * bool:0,1  $strict|$allowEmpty
     * [eq|ne|gt|ge|lt|le]:pwd2,0,0 $strict|$allowEmpty
     * email
     * url
     * required:taw
     * str:20   str:3,9
     * range:aaa,bbb,ccc,dddd
     * int:3   int:,9   int:4,9
     * number:3    number,9.02,number:5.4,999.99
     * regexp:#^\d+$#
     * fun:calss::handle
     * @param array $data
     * @param array $rules
     * @return bool
     */
    public function validate(array $data = null, array $rules = null)
    {
        if (!is_null($rules)) {
            $this->setRules($rules);
        }
        $this->errors = array();
        $this->isBail = false;

        if (!is_null($data)) {
            $this->data = $data;
        }

        foreach ($this->rules as $key => $rule) {
            $this->isArray = false;
            $val = isset($this->data[$key]) ? $this->data[$key] : null;
            $this->validateRule($rule, $val, $key);
            if (!empty($this->errors) && $this->mode == self::MODE_SINGLE) {
                break;
            }
        }
        return empty($this->errors);
    }

    protected function friendErr($key, $error)
    {
        if (isset($this->overrideErrors[$key])) {
            $error = strtr(
                $this->overrideErrors[$key],
                array(
                    "{attribute}" => isset($this->text[$key]) ? $this->text[$key] : $key
                )
            );
        } else {
            $error = strtr(
                $error,
                array(
                    "{attribute}" => isset($this->text[$key]) ? $this->text[$key] : $key
                )
            );
        }

        if (!isset($this->errors[$key])) {
            $this->errors[$key] = $error;
        } else if (is_string($this->errors[$key])) {
            $this->errors[$key] = array($this->errors[$key], $error);
        } else if (is_array($this->errors[$key])) {
            $this->errors[$key][] = $error;
        } else {
            $this->errors[$key] = $error;
        }

    }

    /***
     * CMD[:参数列表] | CMD[:参数列表]
     *
     * empty 表示允许为空 (通用)
     * strict 严格开关 (通用)
     * array 数组批量验证 (通用)
     *
     * bool
     * [eq|ne|gt|ge|lt|le]:pwd2
     * email
     * url
     * required:taw
     * str:20   str:3,9  一个数字为is,两个为min,max
     * range:aaa,bbb,ccc,dddd
     * int:3   int:,9   int:4,9
     * number:3    number,9.02,number:5.4,999.99   number:2,3,true,true    min,max,unsigned,intonly
     * regexp:#^\d+$#
     * fun:calss::handle
     * @param $string_rules
     * @param $value
     * @param $key
     */
    public function validateRule($string_rules, $value, $key)
    {
        $this->isBail = true;
        $this->isEmpty = null;
        $this->isStrict = null;
        $rules = explode("|", $string_rules);
        $err = count($this->errors);//本轮是否有错误
        foreach ($rules as $rule) {
            //设置了Bail,并且已出现错误
            if (count($this->errors) != $err && $this->isBail === true) {
                return;
            }

            $cmd = explode(":", $rule, 2);
            if (isset($cmd[1])) {
                $args = explode(",", $cmd[1]);
            } else {
                $args = array();
            }

            $cmd = $cmd[0];
            switch ($cmd) {
                case 'empty':
                    $this->isEmpty = true;
                    continue;
                case 'array':
                    $this->isArray = true;
                    continue;
                case 'strict':
                    $this->isStrict = true;
                    continue;
                case "bail":
                    $this->isBail = false;
                    continue;
                case "bool":
                    $this->bool_validate($key, $value);
                    break;
                case "eq":
                case "ne":
                case "gt":
                case "ge":
                case "lt":
                case "le":
                    $this->cmp_validate($key, $cmd, $args, $value);
                    break;
                case "email":
                    $this->email_validate($key, $value);
                    break;
                case "required":
                    $this->required_validate($key, $args, $value);
                    break;
                case "str":
                    $this->string_validate($key, $args, $value);
                    break;
                case "range":
                    $this->range_validate($key, $args, $value);
                    break;
                case "int":
                    $this->int_validate($key, $args, $value);
                    break;
                case "date":
                    $this->date_validate($key, $value);
                    break;
                case "time":
                    $this->time_validate($key, $value);
                    break;
                case "year":
                    $this->year_validate($key, $value);
                    break;
                case "datetime":
                    $this->datetime_validate($key, $value);
                    break;
                case "number":
                    $this->number_validate($key, $args, $value);
                    break;
                case "regexp":
                    $this->regexp_validate($key, $args, $value);
                    break;
                case "url":
                    $this->url_validate($key, $value);
                    break;
                case "fun":
                    if (!isset($args[0])) {
                        $this->friendErr($key, "{attribute} requires callback.");
                        break;
                    }
                    $reg = $args[0];
                    if ($reg instanceof \Closure || (is_array($reg) && is_callable($reg))) {
                        $ret = call_user_func_array($reg, array($value));
                        if ($ret !== true) {
                            $this->friendErr($key, "{attribute} callback test failed.");
                        }
                    } else {
                        if (is_string($reg)) {
                            $call = explode("::", $reg, 2);
                            if (count($call) == 2) {
                                if (class_exists($call[0])) {
                                    $rc = new \ReflectionClass($call[0]);
                                    if ($rc->hasMethod($call[1])) {
                                        $ins = $rc->newInstance();
                                        $me = $rc->getMethod($call[1]);
                                        if (true === $me->invokeArgs($ins, array($value))) {
                                            break;
                                        } else {
                                            $this->friendErr($key, "field:{attribute},{$reg} executed value is false.");
                                        }
                                    } else {
                                        $this->friendErr($key, "field:{attribute},{$call[1]} is invalid method.");
                                    }
                                } else {
                                    $this->friendErr($key, "field:{attribute},$reg is invalid callback.");
                                }
                            } else {
                                $this->friendErr($key, "field:{attribute},$reg format is class::method.");
                            }
                        } else {
                            $this->friendErr($key, "field:{attribute},$reg is invalid callback.");
                        }
                    }
                    break;
                default:
                    break;
            }
        }
    }

    protected function bool_validate($key, $value)
    {
        $v = new BooleanValidator();

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function regexp_validate($key, $reg, $value)
    {
        $v = new RegularExpressionValidator();
        $v->pattern = $reg[0];
        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function range_validate($key, $args, $value)
    {
        $v = new RangeValidator();
        $v->range = $args;

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }


    protected function year_validate($key, $value)
    {
        $v = new DateValidator();
        $v->mode = DateValidator::MODE_YEAR;

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function time_validate($key, $value)
    {
        $v = new DateValidator();
        $v->mode = DateValidator::MODE_TIME;

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function datetime_validate($key, $value)
    {
        $v = new DateValidator();
        $v->mode = DateValidator::MODE_DATETIME;

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function date_validate($key, $value)
    {
        $v = new DateValidator();
        $v->mode = DateValidator::MODE_DATE;

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function int_validate($key, $args, $value)
    {
        $v = new NumberValidator();
        $v->integerOnly = true;
        if (isset($args[0]) && $args[0] != "") {
            $v->min = intval($args[0]);
        }
        if (isset($args[1])) {
            $v->max = intval($args[1]);
        }

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function number_validate($key, $args, $value)
    {
        $v = new NumberValidator();
        if (isset($args[0]) && $args[0] != "") {
            $v->min = intval($args[0]);
        }
        if (isset($args[1])) {
            $v->max = intval($args[1]);
        }
        if (isset($args[2])) {
            $v->unsignedOnly = 'true' == $args[2];
        }
        if (isset($args[3])) {
            $v->integerOnly = 'true' == $args[3];
        }
        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function email_validate($key, $value)
    {
        $v = new EmailValidator();


        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function url_validate($key, $value)
    {
        $v = new UrlValidator();
        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function string_validate($key, $args, $value)
    {
        $v = new StringValidator();
        if (count($args) == 1) {
            $v->is = intval($args[0]);
        } else if (count($args) == 2) {
            if ($args[0] != '') {
                $v->min = intval($args[0]);
            }
            if ($args[1] != '') {
                $v->max = intval($args[1]);
            }
        }

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function required_validate($key, $args, $value)
    {
        $v = new RequiredValidator();
        if (isset($args[0])) {
            $v->requiredValue = $args[0];
        }

        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }

    protected function cmp_validate($key, $cmd, $args, $value)
    {
        $map = array(
            "eq" => "==",
            "ne" => "!=",
            "gt" => ">",
            "ge" => ">=",
            "lt" => "<",
            "le" => "<=",
        );
        $v = new CompareValidator();
        $v->operator = $map[$cmd];
        if (!isset($args[0])) {
            $this->friendErr($key, 'Field:{attribute},Require Compare attribute');
            return;
        }
        if (!isset($this->data[$args[0]])) {
            $this->friendErr($key, 'Field:{attribute},Compare attribute does not exist');
            return;
        }
        $v->compareValue = $this->data[$args[0]];
        if (is_bool($this->isEmpty)) {
            $v->allowEmpty = $this->isEmpty;
        }

        if (is_bool($this->isStrict)) {
            $v->strict = $this->isStrict;
        }

        if (is_bool($this->isArray)) {
            $v->isArray = $this->isArray;
        }

        if (!$v->validate($value)) {
            $this->friendErr($key, $v->message);
        }
    }
}