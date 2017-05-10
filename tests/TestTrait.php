<?php

namespace Test;

use PHPUnit_Framework_Assert as Assertion;

trait TestTrait
{
    private $errors = [];

    protected function assertValidArray(array $rules, array $resourceData)
    {
        // validate rules
        $this->validateArray($rules, $resourceData);
        // log errors to console
        if (count($this->errors) >= 1) {
            Assertion::fail(implode(PHP_EOL, $this->errors));
        }
    }

    protected function validateArray($rules, $resourceData)
    {
        foreach ($rules as $key => $rule) {
            if (!is_array($rule)) {
                $rules[$key] = $rule.'|required';
            } else {
                $rules[$key] = 'required';
                if (array_key_exists($key, $resourceData)) {
                    $this->validateArray($rule, $resourceData[$key]);
                }
            }

        }

        $validator = app('validator')->make($resourceData, $rules);

        foreach ($validator->messages()->toArray() as $error) {
            $this->errors[] =  "\e[1;31m× \033[0m".$error[0];
        }
    }
}