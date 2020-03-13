<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lhFakeNameValidator
 *
 * @author user
 */
class lhFakeNameValidator extends lhAbstractValidator {
    
    function validate($text=null) {
        if (preg_match("/[\!\?\.]$/", $text)) {
            $this->setResult(false);
        } elseif (preg_match("/[\!\@\#\$\%\^\&\*\(\)\-\=\+\_\№\"\;\:\?\|\\\\\/\>\<0-9]/u", $text)) {
            $this->setResult(true);
        } elseif (preg_match("/(.)\g{-1}\g{-1}/ui", $text)) {
            $this->setResult(true);
        } else {
            $this->setResult(false);
        }
        return $this->getResult();
    }
    
    public function getValid() {
        throw new Exception("There is no valid fake names at all");
    }
}
