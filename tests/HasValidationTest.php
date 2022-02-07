<?php

namespace Tests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

trait HasValidationTest
{

    protected function assertFails(array $data, FormRequest $form_request)
    {
        $this->assertFalse($this->isValid($data, $form_request));
    }

    protected function assertPasses(array $data, FormRequest $form_request)
    {
        $this->assertTrue($this->isValid($data, $form_request));
    }

    private function isValid(array $data, FormRequest $form_request)
    {
        return Validator::make($data, $form_request->rules())->passes();
    }
}
