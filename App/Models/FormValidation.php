<?php

namespace App\Models;

/**
 * Class FormValidation
 */
class FormValidation
{
    private $_rules = [];
    private $_data = [];
    private $_errors = [];

    public function getErrors()
    {
        return $this->_errors;
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    public function setRules($data)
    {
        $this->_rules = $data;
    }

    public function minMax($rule, $filed)
    {
        $length = strlen($this->_data[$filed]);
        $valid = true;

        if (isset($rule['min'])) {
            $valid = intval($rule['min']) <= $length;
        }

        if ($valid && isset($rule['max'])) {
            $valid = $length <= intval($rule['max']);
        }

        return $valid;
    }

    public function validation()
    {
        if (empty($this->_rules))
            return true;

        foreach ($this->_rules as $fileds => $rule) {
            $fileds = explode(',', $fileds);
            foreach ($fileds as $filed) {
                $filed = trim($filed);
                if (!$filed) continue;
                if (!isset($rule['message'])) $rule['message'] = '%s error.';

                if (in_array('required', array_values($rule))) {
                    if (!isset($this->_data[$filed]) || (isset($this->_data[$filed]) && !trim($this->_data[$filed]))) {
                        $this->_errors[] = sprintf($rule['message'], $filed);
                    }
                }
                if (in_array('string', array_values($rule)) && isset($this->_data[$filed]) && trim($this->_data[$filed])) {
                    if (!$this->minMax($rule, $filed)) {
                        $this->_errors[] = sprintf($rule['message'], $filed);
                    }
                }
                if (in_array('email', array_values($rule)) && isset($this->_data[$filed]) && trim($this->_data[$filed])) {
                    if (!$this->minMax($rule, $filed) || !filter_var($this->_data[$filed], FILTER_VALIDATE_EMAIL)) {
                        $this->_errors[] = sprintf($rule['message'], $filed);
                    }
                }
                if (in_array('file', array_values($rule)) && isset($_FILES[$filed])) {
                    if ($_FILES[$filed]['size'] > 0) {
                        $ferr = true;
                        if (isset($rule['types']) && !empty($rule['types'])) {
                            $ferr = !in_array($_FILES[$filed]['type'], $rule['types']);
                        }
                        if ($ferr) {
                            $this->_errors[] = sprintf($rule['message'], $filed);
                        }
                    }
                }
            }
        }
        $this->_errors = array_unique($this->_errors);

        return empty($this->_errors);
    }
}