<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lhAbstractValidator
 *
 * @author Peter Datahider
 */
require_once LH_LIB_ROOT . 'lhValidator/interface/lhValidatorInterface.php';
abstract class lhAbstractValidator implements lhValidatorInterface {
    protected $text;
    protected $more_info;


    public function __construct($text=null) {
        $this->text = $text;
        $this->more_info = [];
    }
    
    protected function addErrorInfo($error_info) {
        $this->more_info['error_info'] = !$this->more_info['error_info'] ? $error_info : $this->more_info['error_info'].' '.$error_info;
    }
    
    protected function setResult($param) {
        $this->more_info['result'] = $param;
    }
    
    protected function getResult() {
        return $this->more_info['result'];
    }
}
