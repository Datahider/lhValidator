<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lhSenseExtractor
 *
 * @author user
 */
class lhSenseExtractor extends lhAbstractValidator {
    const DEBUG_LEVEL = 0;
    const MINHIT = 90;
    
    private $aiml;
    private $minhit;


    public function __construct($text = null) {
        parent::__construct($text);
    }
    
    public function setMind($file_or_xml) {
        $this->aiml = new lhAIML($file_or_xml);
        $xml = $this->aiml->getAiml();
        $this->minhit = isset($xml['minhit']) ? $xml['minhit'] : self::MINHIT;
    }

    public function getValid() {
        if (isset($this->more_info['sense'])) {
            return $this->more_info['sense'];
        } 
        $this->validate();
        return $this->more_info['sense'];
    }

    public function validate($text = null) {
        $this->more_info = [];
        $this->more_info['prefixes'] = [];
        if ( $text === null ) {
            $text = $this->text;
        } else {
            $text = trim($text);
            $this->text = $text;
        }
        
        $this->recursiveValidation($text);
        return $this->more_info['sense'] != '';
    }
    
    private function lexemValidation($text) {
        $senteces = lhTextConv::splitSentences($text);
        if (count($senteces)) {
            $lexems = lhTextConv::split(array_shift($senteces));
            $this->recursiveLexemValidation($lexems);
        }
    }

    private function recursiveLexemValidation($lexem_array) {
        $match_level = -1;
        $best_match_pos = 0;
        $lexems = count($lexem_array);
    }

    private function recursiveValidation($text) {
        $this->log(__CLASS__.'->'.__FUNCTION__);
        $this->log("\$text=". $text, 15);
        $text = preg_replace("/(^[;:?.,!\s]+)/u", '', $text);
        $len = mb_strlen(substr($text, 0, 200));
        $match_level = -1;
        $best_match_pos = 0;
        
        $xml = $this->aiml->getAiml();
        $min_hit_ratio = isset($xml['minhit']) ? $xml['minhit'] : 90;
        
        for($i=$len; $i>0; $i--) {
            $matches = $this->aiml->bestMatches(mb_substr($text, 0, $i), '', $min_hit_ratio);
            foreach ($matches as $percentage=>$match) {
                if ($match['match_level'] > $match_level) {
                    $match_level = $match['match_level'];
                    $match_category = $match[1];
                    $best_match_pos = $i;
                }
                break;
            }
        }
        if ($match_level == -1) {
            $raw_sense = $text;
            $raw_sense = preg_replace("/^\W+/u", '', $raw_sense);
            $this->more_info['sense'] = mb_strtoupper(mb_substr($raw_sense, 0, 1)). mb_substr($raw_sense, 1);
            return;
        } else {
            $prefix_text = preg_replace("/(^[^a-zA-ZА-Яа-я0-9]+|[^a-zA-ZА-Яа-я0-9]+$)/u", '', mb_substr($text, 0, $best_match_pos));
            $this->log('$match_level='.$match_level, 20);
            $this->log('$prefix_text='.$prefix_text, 20);
            $this->log('$match_category:', 20);
            $this->log($match_category, 20);
            $this->more_info['prefixes'][] = [
                'category' => $match_category,
                'text' => $prefix_text
            ];
            $this->recursiveValidation(mb_substr($text, $best_match_pos));
            return;
        }
        
    }

    protected function testValidate($text) {
        $this->validate($text);
        $prefixes = [];
        foreach ($this->more_info['prefixes'] as $prefix) {
            $prefixes[] = [ 'text' => $prefix['text'], 'category' => (string)$prefix['category']['name']];
        }
        return [$this->getValid(), $prefixes];
    }

    protected function _test_data() {
        return [
            'addErrorInfo' => '_test_skip_',
            'setMind' => '_test_skip_',
            'setResult' => '_test_skip_',
            'getResult' => '_test_skip_',
            'moreInfo' => '_test_skip_',
            'validate' => '_test_skip_',
            'getValid' => '_test_skip_',
            'testValidate' => [
                ["Добрый день,  у меня ничего не работает", ["У меня ничего не работает", [
                    [ 'category' => '%GREETING%', 'text' => "Добрый день" ],
                ]]],
                ["У меня ничего не работает", ["У меня ничего не работает", []]],
                ["Приветствую! Все сломалось", ["Все сломалось", [
                    [ 'category' => '%GREETING%', 'text' => "Приветствую" ],
                ]]],
                ["Привет! Все сломалось", ["Все сломалось", [
                    [ 'category' => '%GREETING%', 'text' => "Привет" ],
                ]]],
                ["Доброе время суток!!! У нас не работает удаленка", ["У нас не работает удаленка", [
                    [ 'category' => '%GREETING%', 'text' => "Доброе время суток" ],
                ]]],
                ["Здрасьте! Все сломалось", ["Все сломалось", [
                    [ 'category' => '%GREETING%', 'text' => "Здрасьте" ],
                ]]],
                ["Добрый день,просьба подключить этот номер тел 89032286081,чтобы", ["Просьба подключить этот номер тел 89032286081,чтобы", [
                    [ 'category' => '%GREETING%', 'text' => "Добрый день" ],
                ]]],
                ["Привет. Не могу дозвониться до удаленки", ["Не могу дозвониться до удаленки", [
                    [ 'category' => '%GREETING%', 'text' => "Привет" ],
                ]]],
                ["Петя, привет! При проведении поступлений ошибка:", ["При проведении поступлений ошибка:", [
                    [ 'category' => '%PETER%', 'text' => "Петя" ],
                    [ 'category' => '%GREETING%', 'text' => "привет" ],
                ]]],
                ["Добрый день!", ["", [
                    [ 'category' => '%GREETING%', 'text' => "Добрый день" ],
                ]]],
                ["Доброе утро! У меня на сервере не приходят письма на почту ", ["У меня на сервере не приходят письма на почту", [
                    [ 'category' => '%GREETING%', 'text' => "Доброе утро" ],
                ]]],
                ["Петь, можно номер удалёнки подключить к моему телефону?", ["Можно номер удалёнки подключить к моему телефону?", [
                    [ 'category' => '%PETER%', 'text' => "Петь" ],
                ]]],
                ["Привет ещё раз. Я забрала домой моноблок Лёши Зозули. По ..", ["Я забрала домой моноблок Лёши Зозули. По ..", [
                    [ 'category' => '%GREETING%', 'text' => "Привет ещё раз" ],
                ]]],
                ["Петь, постоянно вылетают удаленки, почему?", ["Постоянно вылетают удаленки, почему?", [
                    [ 'category' => '%PETER%', 'text' => "Петь" ],
                ]]],
                ["Пётр ещё вопрос sveta@relotti.ru не видит отправленых пи ..", ["Sveta@relotti.ru не видит отправленых пи ..", [
                    [ 'category' => '%PETER%', 'text' => "Пётр" ],
                    [ 'category' => '%EXCESS%', 'text' => "ещё вопрос" ],
                ]]],
                ["Доброе утро", ["", [
                    [ 'category' => '%GREETING%', 'text' => "Доброе утро" ],
                ]]],
                ["Привет! Не работает номер удаленки", ["Не работает номер удаленки", [
                    [ 'category' => '%GREETING%', 'text' => "Привет" ],
                ]]],
                ["СРОЧНО! Не работает интеграция со Сдэком", ["Не работает интеграция со Сдэком", [
                    [ 'category' => '%EXCESS%', 'text' => "СРОЧНО" ],
                ]]],
                ["Доброе утро! Не могу начать работу, вот что пишети", ["Не могу начать работу, вот что пишети", [
                    [ 'category' => '%GREETING%', 'text' => "Доброе утро" ],
                ]]],
                ["Добрый день. Настроил почту у себя в профиле C:\Users\То ...", ["Настроил почту у себя в профиле C:\Users\То ...", [
                    [ 'category' => '%GREETING%', 'text' => "Добрый день" ],
                ]]],
                ["Доброе утро!при звонке на удаленку говорит женщина", ["При звонке на удаленку говорит женщина", [
                    [ 'category' => '%GREETING%', 'text' => "Доброе утро" ],
                ]]],
                ["Добрый день. Это Степан компания Стройтовары. Игорь сказал", ["Это Степан компания Стройтовары. Игорь сказал", [
                    [ 'category' => '%GREETING%', 'text' => "Добрый день" ],
                ]]],
                ["Петь привет. Не работает почта man103. Утром все нормально было.", ["Не работает почта man103. Утром все нормально было.", [
                    [ 'category' => '%PETER%', 'text' => "Петь" ],
                    [ 'category' => '%GREETING%', 'text' => "привет" ],
                ]]],
                ["Добрый день!)прошу установить удаленку на компьютер) удаленк", ["Прошу установить удаленку на компьютер) удаленк", [
                    [ 'category' => '%GREETING%', 'text' => "Добрый день" ],
                ]]],
                ["Rdp30", ["", [
                    [ 'category' => '%PROMO%', 'text' => "Rdp30" ],
                ]]],
                ["Это рассылка типа сделанного заказа.", ["Это рассылка типа сделанного заказа.", []]],
            ]
        ];
    }
}
