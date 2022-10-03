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
        $this->more_info = [];
        if ( $text === null ) {
            $text = $this->text;
        } else {
            $text = trim($text);
            $this->text = $text;
        }
        // Сначала уберем все допустимые символы из номера
        $digits = preg_replace("/[\s\+\-\(\)]/", '', $text);
        if (preg_match("/^\d{10,12}$/", $digits)) {
            $phone = preg_replace("/^7/", '+7', $digits);
            $phone = preg_replace("/^8/", '+7', $phone);
            $phone = preg_replace("/^(\d{10})$/", '+7$1', $phone);
            $phone = preg_replace("/^380/", '+380', $phone);
            $phone = preg_replace("/^44/", '+44', $phone);
            $phone = preg_replace("/\+380(\d{2})(\d{3})(\d{2})(\d{2})/", '+380 ($1) $2-$3-$4', $phone);
            $phone = preg_replace("/\+44(\d{3})(\d{3})(\d{2})(\d{2})/", '+44 ($1) $2-$3-$4', $phone);
            $this->more_info['phone'] = preg_replace("/^\+7(\d{3})(\d{3})(\d{2})(\d{2})/", '+7 ($1) $2-$3-$4', $phone);
            $this->setResult(true);
        } else {
            $this->setResult(false);
        }
        return $this->getResult();
    }

    public function getValid() {
        if (!$this->validate()) {
            throw new Exception("Invalid phone number");
        }
        return $this->moreInfo()['phone'];
    }
    
}
