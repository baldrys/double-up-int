<?php

namespace App\Support;


class Task2Utils
{

    /**
     * Проверяет данные и вызывает abort с заданным кодом и сообщением
     *
     * @param  mixed  $data
     * @param  string $op
     * @param  string $expectedData
     * @param  int    $errorCode
     * @param  string $errorMsg
     *
     * @return void
     */
    public static function validateData($data, $op, $expectedData, string $errorCode, string $errorMsg){
        if(!Task2Utils::numCond($data, $op, $expectedData)){
            abort($errorCode, $errorMsg);
        }
    }

    /**
     * Получает ассоциативный массив для заданной структуры
     *
     * @param  mixed  $data
     * @param  string $dataName
     *
     * @return Array
     */
    public static function getSuccessArray($data, string $dataName){
        return [
            'success' => true, 
            'data' => [
                $dataName => $data,
                ]
            ];
    }

    /**
     * Компаратор
     *
     * @param  mixed $var1
     * @param  sting $op
     * @param  mixed $var2
     *
     * @return boolean
     */
    public static function numCond($var1, $op, $var2) {
        switch ($op) {
            case "=":  return $var1 == $var2;
            case "!=": return $var1 != $var2;
            case ">=": return $var1 >= $var2;
            case "<=": return $var1 <= $var2;
            case ">":  return $var1 >  $var2;
            case "<":  return $var1 <  $var2;
        default:       return true;
        }   
    }
}
