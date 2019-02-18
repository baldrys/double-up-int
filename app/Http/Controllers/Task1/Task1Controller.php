<?php

namespace App\Http\Controllers\Task1;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Task1Controller extends BaseController
{
    public function helloWorld(){
        return "Hello World";
    }

    public function uuid(){
        $uuid1 = Uuid::uuid1();
        return response()->json([
            'success' => 'true', 
            'data' => [
                'uuid' => $uuid1->toString()
                ]
            ]);
    }

    public function data_from_config(){
        return response()->json([
            'success' => 'true', 
            'data' => [
                'config' => [
                    'test_config_value' => env('TEST_CONFIG_VALUE', null)
                    ]
                ]
            ]);
    }
}
