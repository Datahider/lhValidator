<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of loPhoneValidator
 *
 * @author user
 */
require_once __DIR__ . '/../abstract/lhAbstractValidator.php';

class lhPhoneValidator extends lhAbstractValidator {
    
    public function validate($text=null) {
        $text = trim($text);
        // Начнем замены
        $this->setResult(true);
        $this->more_info['phone'] = $text;
        
        return $this->getResult();
    }
    
}
