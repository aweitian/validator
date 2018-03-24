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
    protected $rules = array();
    protected $errors = array();
    protected $isBail = false;
    protected $data = array();

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
            $val = isset($this->data[$key]) ? $this->data[$key] : null;
            $this->validateRule($rule, $val);
            if (!empty($this->errors) && $this->isBail) {
                break;
            }
        }
        return empty($this->errors);
    }

    /***
     * bool:0,1  $strict|$allowEmpty
     * [eq|ne|gt|ge|lt|le]:pwd2,0,0 $strict|$allowEmpty
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
     */
    public function validateRule($string_rules, $value)
    {
        $this->isBail = false;
        $rules = explode("|", $string_rules);
        foreach ($rules as $rule) {
            $cmd = explode(":", $rule, 2);
            if (isset($cmd[1])) {
                $reg = $cmd[1];
                $args = explode(",", $cmd[1]);
            } else {
                $reg = array();
                $args = array();
            }

            $cmd = $cmd[0];
            switch ($cmd) {
                case "bail":
                    $this->isBail = true;
                    return;
                case "bool":
                    $this->bool_validate($cmd, $args, $value);
                    break;
                case "eq":
                case "ne":
                case "gt":
                case "ge":
                case "lt":
                case "le":
                    $this->cmp_validate($cmd, $args, $value);
                    break;
                case "email":
                    $this->email_validate($cmd, $args, $value);
                    break;
                case "required":
                    $this->required_validate($cmd, $args, $value);
                    break;
                case "str":
                    $this->string_validate($cmd, $args, $value);
                    break;
                case "range":
                    $this->range_validate($cmd, $args, $value);
                    break;
                case "int":
                    $this->int_validate($cmd, $args, $value);
                    break;
                case "number":
                    $this->number_validate($cmd, $args, $value);
                    break;
                case "regexp":
                    $this->regexp_validate($cmd, $reg, $value);
                    break;
                case "url":
                    $this->url_validate($cmd, $args, $value);
                    break;
                case "fun":

                    if (is_callable($reg)) {
                        $ret = call_user_func_array($reg, array($value));
                        if ($ret !== true)
                        {
                            $this->errors[] = "$reg test failed.";
                        }
                    } else {
                        if (is_string($reg))
                        {
                            $call = explode("::",$reg,2);
                            if (count($call) == 2)
                            {
                                if (class_exists($call[0]))
                                {
                                    $rc = new \ReflectionClass($call[0]);
                                    if ($rc->hasMethod($call[1]))
                                    {
                                        $ins = $rc->newInstance();
                                        $me = $rc->getMethod($call[1]);
                                        if(true === $me->invokeArgs($ins,array($value)))
                                        {
                                            break;
                                        }
                                    } else {
                                        $this->errors[] = "{$call[1]} is invalid method.";
                                    }
                                } else {
                                    $this->errors[] = "$reg is invalid callback.";
                                }
                            } else {
                                $this->errors[] = "$reg format is class::method.";
                            }
                        } else {
                            $this->errors[] = "$reg is invalid callback.";
                        }

                    }
                    break;
                default:
                    break;
            }
        }
    }

    protected function bool_validate($cmd, $args, $value)
    {
        $v = new BooleanValidator();
        if (isset($args[0])) {
            $v->strict = $args[0] == "0" ? false : true;
        }
        if (isset($args[1])) {
            $v->allowEmpty = $args[1] == "0" ? false : true;
        }
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function regexp_validate($cmd, $reg, $value)
    {
        $v = new RegularExpressionValidator();
        $v->pattern = $reg;
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function range_validate($cmd, $args, $value)
    {
        $v = new RangeValidator();
        $v->range = $args;
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function int_validate($cmd, $args, $value)
    {
        $v = new NumberValidator();
        $v->integerOnly = true;
        if (isset($args[0]) && $args[0] != "") {
            $v->min = intval($args[0]);
        }
        if (isset($args[1])) {
            $v->max = intval($args[1]);
        }

        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function number_validate($cmd, $args, $value)
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
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function email_validate($cmd, $args, $value)
    {
        $v = new EmailValidator();
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function url_validate($cmd, $args, $value)
    {
        $v = new UrlValidator();
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function string_validate($cmd, $args, $value)
    {
        $v = new StringValidator();
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
        if (count($args) == 1) {
            $v->is = intval($args[0]);
        } else if (count($args) == 2) {
            $v->min = intval($args[0]);
            $v->max = intval($args[1]);
        }
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function required_validate($cmd, $args, $value)
    {
        $v = new RequiredValidator();
        if (isset($args[0])) {
            $v->requiredValue = $args[0];
        }
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    protected function cmp_validate($cmd, $args, $value)
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
            $this->errors[] = 'Require Compare attribute';
            return;
        }
        if (!isset($this->data[$args[0]])) {
            $this->errors[] = 'Compare attribute does not exist';
            return;
        }
        $v->compareValue = $this->data[$args[0]];
        if (isset($args[1])) {
            $v->strict = $args[1] == "0" ? false : true;
        }
        if (isset($args[2])) {
            $v->allowEmpty = $args[2] == "0" ? false : true;
        }
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }
}