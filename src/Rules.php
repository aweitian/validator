<?php
/**
 * https://laravel.com/docs/5.4/validation#available-validation-rules
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 13:06
 */

namespace Tian\Validator;
class Rules
{
    private $rules = [];
    private $errors = [];
    private $isBail = false;
    private $data = [];

    public function setRules(array $ruels)
    {
        $this->rules = $ruels;
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

    /**
     * @param array $data
     * @param array $rules
     */
    public function validate(array $data, array $rules = null)
    {
        if (!is_null($rules)) {
            $this->setRules($rules);
        }
        $this->errors = [];
        $this->isBail = false;
        $this->data = $data;
        foreach ($data as $key => $val) {
            if (array_key_exists($key, $this->rules)) {
                $rule = $this->rules[$key];

                $this->validateRule($rule, $val);
                if ($this->isBail) {
                    break;
                }
            }
        }
    }

    /***
     * bool:0,1  $strict|$allowEmpty
     * [eq|ne|gt|ge|lt|le]:pwd2,0,0 $strict|$allowEmpty
     * email
     * url
     * required:taw
     * str:20   str:3,9
     * range:aaa,bbb,ccc,dddd
     * int:3   int:,9   int:4,9
     * number:3    number,9.02,number:5.4,999.99
     * regexp:^\d+$
     * fun:calss::handle
     * @param $string_rules
     * @param $value
     */
    public function validateRule($string_rules, $value)
    {
        $rules = explode("|", $string_rules);
        foreach ($rules as $rule) {
            $cmd = explode(":", $rule, 2);
            if (isset($cmd[1])) {
                $args = explode(",", $cmd[1]);
            } else {
                $args = [];
            }
            $reg = $cmd[1];
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
                    if (is_callable($reg))
                    {
                        call_user_func_array($reg,[$value]);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    private function bool_validate($cmd, $args, $value)
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

    private function regexp_validate($cmd, $reg, $value)
    {
        $v = new RegularExpressionValidator();
        $v->pattern = $reg;
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    private function range_validate($cmd, $args, $value)
    {
        $v = new RangeValidator();
        $v->range = $args;
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    private function int_validate($cmd, $args, $value)
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

    private function number_validate($cmd, $args, $value)
    {
        $v = new NumberValidator();
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

    private function email_validate($cmd, $args, $value)
    {
        $v = new EmailValidator();
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    private function url_validate($cmd, $args, $value)
    {
        $v = new UrlValidator();
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    private function string_validate($cmd, $args, $value)
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

    private function required_validate($cmd, $args, $value)
    {
        $v = new RequiredValidator();
        if (isset($args[0])) {
            $v->requiredValue = $args[0];
        }
        if (!$v->validate($value)) {
            $this->errors[] = $v->message;
        }
    }

    private function cmp_validate($cmd, $args, $value)
    {
        $map = [
            "eq" => "==",
            "ne" => "!=",
            "gt" => ">",
            "ge" => ">=",
            "lt" => "<",
            "le" => "<=",
        ];
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