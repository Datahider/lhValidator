<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of loNameValidator
 *
 * @author user
 */
class loNameValidator extends lhValidator {
    public function __construct($text='') {
        if ($text) {
            $this->parseText($text);
        }
    }
    
    public function parseText($text) {
        
    }
    
    public function detectGender($aword) {
        $system = new loSystem();
        $mensNames = $system->systemCommand('anstempl.byname', [ 'su' => 'system', 'template_name' => 'Мужские имена']);
        $womensNames = $system->systemCommand('anstempl.byname', [ 'su' => 'system', 'template_name' => 'Женские имена']);
    }
}
