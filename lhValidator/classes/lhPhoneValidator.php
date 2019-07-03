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
        // Сначала уберем все допустимые символы из номера
        $digits = preg_replace("/[\s\+\-\(\)]/", '', $text);
        if (preg_match("/^\d{10,11}$/", $digits)) {
            $phone = preg_replace("/^7/", '+7', $digits);
            $phone = preg_replace("/^8/", '+7', $phone);
            $phone = preg_replace("/^(\d{10})$/", '+7$1', $phone);
            $this->more_info['phone'] = preg_replace("/^\+7(\d{3})(\d{3})(\d{2})(\d{2})/", '+7 ($1) $2-$3-$4', $phone);
            $this->setResult(true);
        } else {
            $this->setResult(false);
        }
        return $this->getResult();
    }
    
}
