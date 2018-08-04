<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lhFullNameValidator
 *
 * @author Петя Datahider
 */
require_once __DIR__ . '/../abstract/lhAbstractValidator.php';

class lhFullNameValidator extends lhAbstractValidator {
    static $debug = false;
    
    public function validate($text=null) {
        $this->more_info = [];
        if ( $text === null ) {
            $text = $this->text;
        } else {
            $text = trim($text);
            $this->text = $text;
        }
        $n = new lhRuNames();
        $this->setResult(true);
        $this->more_info['full'] = '';
        $this->more_info['gender'] = null;
        $this->more_info['is_known'] = false;
        $this->more_info['found_names'] = '';
        try {
            $n->setFullName($text);
            $this->more_info['full'] = $n->full();
            $this->more_info['gender'] = $n->gender();
            $this->more_info['is_known'] = $n->is_known();
            $this->more_info['found_names'] = $n->foundNames();
        } catch (Exception $exc) {
            $this->setResult(false);
        }
        return $this->getResult();
    }
    
}