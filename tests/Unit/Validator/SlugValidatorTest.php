<?php

namespace Tests\Unit\Validator;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SlugValidatorTest extends TestCase
{

    public function testValidatorWorksAsExpected()
    {
        $data = [
            'slug' => [
                "laaa-laala",
                "ddd-"
            ]
        ];


        $validator = Validator::make($data, [
            'slug.*' => "slug"
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testValidatorFails()
    {
        $data = [
            'slug' => [
                "laaaa",
                "ddds"
            ]
        ];

        $validator = Validator::make($data, [
            'slug.*' => "slug"
        ]);

        $this->assertFalse($validator->passes());
    }
}
