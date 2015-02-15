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
 * A selection of general-utility functions.
 */
class Utils extends \Prefab {

    function __construct(){}

    /**
     * Bye array...
     * 
     * @param string A JSON array representation.
     * @return string A string without [].
     */
    function byeArray($json_array){    
        return str_replace('[', '', str_replace(']', '', $json_array));
    }
    
    /**
     * Replaces substrings in a string.
     *
     * @param string $search The substring to search for.
     * @param string $replace The replacement string.
     * @param string $subject The string to evaluate.
     * @return string Returns the new string.
     */
    function strReplace($search, $replace, $subject){
        return preg_replace('~(.*)' . preg_quote($search, '~') . '~', '$1' . $replace, $subject, 1);
    }
}
