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
require_once __DIR__ . '/../interface/lhValidatorInterface.php';
abstract class lhAbstractValidator implements lhValidatorInterface {
    protected $text;            // Сохраняет текст с прошлого вызова, далее можно вызывать без параметров.
    protected $more_info;       // Сохраняет расширенную информацию о валидации 
                                //(что еще может сказать валидатор о валидируемой строке
                                // например страна если валидируем номер телефона) 


    public function __construct($text=null) {
        $this->text = $text;
        $this->more_info = [];
    }
    
    protected function addErrorInfo($error_info) {
        $this->more_info['error_info'] = !isset($this->more_info['error_info']) || !$more_info['error_info'] ? $error_info : $this->more_info['error_info'].' '.$error_info;
    }
    
    protected function setResult($param) {
        $this->more_info['result'] = $param;
    }
    
    protected function getResult() {
        return $this->more_info['result'];
    }
    public function moreInfo() {
        return $this->more_info;
    }
}
