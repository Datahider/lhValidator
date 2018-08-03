<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of loEmailValidator
 *
 * @author user
 */
require_once __DIR__ . '/../abstract/lhAbstractValidator.php';

class lhNameValidator extends lhAbstractValidator {
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
        $this->setResult(false);
        foreach (preg_split("/\s+/", $text) as $word) {
            try { $full = $n->full($word); } catch (Exception $e) { $full = false; }
            if ($full) {
                $this->setResult(true);
                $this->more_info['full'] = $full;
                $this->more_info['gender'] = $n->gender();
                $this->more_info['is_known'] = $n->is_known();
                $this->setResult(true);
                return $this->getResult();
            }
        }
        return $this->getResult();
    }
    
}
