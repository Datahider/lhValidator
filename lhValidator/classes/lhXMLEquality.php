<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lhXMLEquality
 *
 * @author user
 */
class lhXMLEquality extends lhAbstractValidator {
    public function __construct($text = null) {
        if (!is_a($text, 'SimpleXMLElement')) {
            throw new Exception("Awaiting a SimpleXMLElement as the argument");
        }
        $this->text = $text;
    }
    
    public function getValid() {
        if ($this->getResult()) {
            return $this->getResult();
        }
        return $this->validate();
    }

    public function validate($text = null) {
        if (!is_a($text, 'SimpleXMLElement')) {
            throw new Exception("Awaiting a SimpleXMLElement as the argument");
        }
        $json1 = json_decode(json_encode((array)$this->text));
        $json2 = json_decode(json_encode((array)$text));
        if ($json1 == $json2) {
            return true;
        }
        return false;
    }
    
    protected function _testValidate() {
        $set = [
            [
                new SimpleXMLElement('<root><a/></root>'),
                new SimpleXMLElement('<root><b/></root>'),
                false
            ],
            [
                new SimpleXMLElement('<root><ok/></root>'),
                new SimpleXMLElement('<root><ok></ok></root>'),
                true
            ],
            [
                new SimpleXMLElement('<root><a>a</a><b><c>1</c><c>2</c></b></root>'),
                new SimpleXMLElement('<root><b><c>1</c><c>2</c></b><a>a</a></root>'),
                true
            ],
        ];
        foreach ($set as $test) {
            echo '.';
            $c = new lhXMLEquality($test[0]);
            $r = $c->validate($test[1]);
            if ($r != $test[2]) {
                throw new Exception(print_r([
                    'awaiting' => $test[2],
                    'got' => $r
                ]));
            }
        }
    }

    protected function _test_data() {
        return [
            'validate' => '_testValidate',
            'getValid' => '_test_skip_',
            'getResult' => '_test_skip_',
            'setResult' => '_test_skip_',
            'moreInfo' => '_test_skip_',
            'addErrorInfo' => '_test_skip_',
        ];
    }
}
