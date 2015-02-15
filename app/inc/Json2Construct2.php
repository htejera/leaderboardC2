<?php
/**
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 henry.tejera
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * JSON to Construct2 data converter.
 */
class Json2Construct2 extends \Prefab {

    function __construct(){}

    /**
     * Return a Construct2 dictionary string format.
     * 
     * @param string $value The dictionary data.
     * @return string Returns a Construct2 dictionary string (JSON representation). 
     */
    function toC2Dictionary($value){
        return "{\"c2dictionary\":true,\"data\":".$value."}";
    }

    /**
     * Return a Construct2 array string format.
     * Note the JSON includes additional information like the dimensions of the array, 
     * and is always saved in a 3D format,
     *
     * @param string $firstSize First array dimension.
     * @param string $secondsSize Seconds array dimension.     
     * @param string $thirdSize Third array dimension.
     * @param string $value String that represents the array.
     * @return string Returns a Construct2 array string (JSON representation). 
     */
    function toC2Array($firstSize,$secondsSize,$thirdSize,$value){
        return  "{\"c2array\":true,
                \"size\":[".$firstSize.",".$secondsSize.",".$thirdSize."],
                \"data\":[".$value."]]}";
    }
    
}