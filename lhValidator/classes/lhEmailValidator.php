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

class lhEmailValidator extends lhAbstractValidator {
    static $debug = false;
    
    public function validate($text=null) {
        $this->more_info = [];
        if ( $text === null ) {
            $text = $this->text;
        } else {
            $text = trim($text);
            $this->text = $text;
        }
        $split = split("@", $text, 2);
       
        $user_ok = preg_match("/^[[:alpha:]\d_]([[:alpha:]\d_\-\.]*[[:alpha:]\d_]|)$/", $split[0]);
        $domain_ok = checkdnsrr($split[1].'.', 'SOA');
        
        if ($user_ok && $domain_ok) {
            $this->setResult(true);
            $this->more_info['domain'] = $split[1];
            $this->more_info['user'] = $split[0];
            $this->more_info['address'] = $text;
        } else {
            $this->setResult(false);
            if ($domain_ok) {
                $this->more_info['domain'] = $split[1];
            } else {
                $this->more_info['domain'] = null;
                $this->addErrorInfo("Domain \"$split[1]\" not found in DNS.");
            }
            if ($user_ok) {
                $this->more_info['user'] = $split[0];
            } else {
                $this->more_info['user'] = null;
                $this->addErrorInfo("Illegal user $split[0]");
            }
        }
        return $this->getResult();
    }
    
}
