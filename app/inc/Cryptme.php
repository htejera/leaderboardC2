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
 * Cryptme is a set PHP functions made to encrypt and decrypt strings.
 * Based on @link https://github.com/Hunter-Dolan/Crypt.
 */
class Cryptme extends \Prefab {

    function __construct(){}
    
    /**
     * Encrypt.
     * 
     * @param  string $sData The data to encrypt. 
     * @param  string $sKey  The key.
     * @return string The encrypt data.
     */
    function encrypt($sData, $sKey){
        $sResult = '';
        for($i=0;$i<strlen($sData);$i++){
            $sChar = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar = chr(ord($sChar) + ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $this->encode_base64($sResult);
    }

    /**
     * Decrypt.
     * 
     * @param  string $sData The encrypt data. 
     * @param  string $sKey  The key.
     * @return string The descrypt data.
     */
    function decrypt($sData, $sKey){
        $sResult = '';
        $sData = $this->decode_base64($sData);
        for($i=0;$i<strlen($sData);$i++){
            $sChar = substr($sData, $i, 1);
            $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
            $sChar = chr(ord($sChar) - ord($sKeyChar));
            $sResult .= $sChar;
        }
        return $sResult;
    }

    /**
     * Double decrypt.
     * 
     * @param string The encrypted data.
     * @param string The magic key.                            
     */
    function doubleDecrypt($data, $magicKey){        
        return $this->decrypt($this->decrypt($data, $magicKey),$magicKey);   
    }

    /**
     * Encodes data with MIME base64.
     * 
     * @param  string $sData The data to encode. 
     * @return string The encoded data, as a string or FALSE on failure. 
     */
    function encode_base64($sData){
        $sBase64 = base64_encode($sData);
        return strtr($sBase64, '+/', '-_');   
    }

    /**
     * Decodes data encoded with MIME base64.
     * 
     * @param  string $sData The encoded data. 
     * @return string Returns the original data or FALSE on failure. 
     *                The returned data may be binary. 
     */
    function decode_base64($sData){
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64);
    } 


}