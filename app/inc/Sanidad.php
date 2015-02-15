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
 * This class provide some sanitize functionality.
 */
class Sanidad extends \Prefab {

    private $f3;

    function __construct($f3){
        $this->f3 = $f3;
    }

    /**
     * String sanitize sql or html injection abuse and bad words. 
     * 
     * @param string $string The input.
     * @return string.
     */
    function noNaughty($string){          
    
        foreach($this->f3->get('bad_words') as $mala){              
            if (strcasecmp($mala,$string) == 0){
                $string = preg_replace('/'.$mala.'/i', 'Anonymous', $string);
                break;
            } 
        }
                
        $string = preg_replace("/'/i", '&rsquo;', $string);
        $string = preg_replace('/%39/i', '&rsquo;', $string);
        $string = preg_replace('/&#039;/i', '&rsquo;', $string);
        $string = preg_replace('/&039;/i', '&rsquo;', $string);
        $string = preg_replace('/"/i', '&quot;', $string);
        $string = preg_replace('/%34/i', '&quot;', $string);
        $string = preg_replace('/&034;/i', '&quot;', $string);
        $string = preg_replace('/&#034;/i', '&quot;', $string);
        return $string;
    }

    /**
     * Safe typing.
     * 
     * @param string $input The input.
     * @return string.
     */
    function safeTyping($input){
        return preg_replace("/[^a-zA-Z0-9 \!\@\%\^\&\*\.\*\?\+\[\]\(\)\{\}\^\$\:\;\,\-\_\=]/",
                            "", $input);
    }

    /** 
     * Sanidad.
     * Remove HTML tags and non-printable characters to mitigate     
     * XSS/code injection attacks
     */
    function sanitize($string){
        $string = $this->noNaughty($string);        
        $string = $this->f3->clean($string);    
        $string = $this->safeTyping($string);        
        return trim($string);
    }

    /**
     * Simple validations on the score.
     *
     * @param string Theencrypted score.
     * @return int The score presumably clean.         
     */
    function auditScore($score){    
        if (!is_numeric($score)) {
            $numeric = 0;
        } else {
            $numeric = (int)$score;

            if ($numeric >= (int)$this->f3->get('max_score_allowed') or $numeric < 0) {
                $numeric = 0;
            }
        }    
        return $numeric; 
    }    

    /**
     * Simple anti-cheat mechanism.
     * 
     * @param  string  $data The data.
     * @return boolean Success returns true, otherwise returns false.
     */
    function antiCheat($data){ 
        $deepKey = $this->f3->get('cryptme')
                            ->doubleDecrypt($data,
                                            $this->f3->get('magic_key'));
                
        if(substr($deepKey,3,4) == $this->f3->get('magic_number')){
            return true;
        }
        return false;
    }

}