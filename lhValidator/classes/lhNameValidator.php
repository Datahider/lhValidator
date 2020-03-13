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
        $this->more_info['found'] = 0;
        $this->more_info['found_names'] = '';
        $this->more_info['full'] = '';
        $this->more_info['gender'] = null;
        $this->more_info['is_known'] = false;
        foreach (preg_split("/\s+/u", $text) as $word) {
            try { 
                $full = $n->full($word); 
                $this->setResult(true);
            } 
            catch (Exception $e) { 
                $full = false; 
            }
            if ($n->is_known()) {
                $this->more_info['full'] = $full;
                $this->more_info['gender'] = $n->gender();
                $this->more_info['is_known'] = $n->is_known();
                $this->more_info['found_names'] = $n->foundNames();
                $count = 0;
                foreach (preg_split("/\s+/u", $this->more_info['found_names']) as $name) {
                    $this->more_info['found'.$count] = $name;
                    $count++;
                }
                $this->more_info['found'] = $count;
                $this->setResult((bool)$full);
                return $this->getResult();
            }
        }
        return $this->getResult();
    }
    
    public function getValid() {
        if (!$this->validate()) {
            throw new Exception("Invalid name");
        }
        return $this->more_info['full'];
    }
}
