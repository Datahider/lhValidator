<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lhTgBotTokenValidator
 *
 * @author user
 */
require_once __DIR__ . '/../abstract/lhAbstractValidator.php';

class lhTgBotTokenValidator extends lhAbstractValidator{
    
    public function validate($text=null) {
        if ( $text === null ) {
            $text = $this->text;
        } else {
            $text = trim($text);
            $this->text = $text;
        }
        foreach (preg_split("/\s+/u", $text) as $suspect) {
            $data = $this->getMe($suspect);
            if ( ($data !== false) && ($data->ok) ) {
                $result = $data->result->is_bot;
                $this->more_info['first_name'] = $data->result->first_name;
                $this->more_info['username'] = $data->result->username;
                $this->more_info['token'] = $suspect;
                $this->setResult(true);
                break;
            } else {
                $this->more_info = [];
                $this->setResult(false);
            }
        }
        return $this->getResult();
    }
    
    private function getMe($botapitoken) {
        $func = 'getMe';
        $data = [];
        $ch = curl_init('https://api.telegram.org/bot'.$botapitoken.'/'.$func);
        if ( $ch ) {
            if (curl_setopt_array( $ch, array(
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $data
            ))) {    
                $content=curl_exec($ch);
                if (curl_errno($ch)) throw new Exception (curl_error ($ch).' Content provided: '.$content);
                curl_close($ch);
                return json_decode($content);
            }
        }
        return false;
    }
    
    public function getValid() {
        if (!$this->validate()) {
            throw new Exception("Invalid Telegram bot token");
        }
        return $this->more_info['token'];
    }
}
