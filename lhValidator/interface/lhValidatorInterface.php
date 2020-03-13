<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of lhValidatorInterface
 *
 * @author user
 */
interface lhValidatorInterface {

    // validate($text=null) 
    // Проверяет валидность переданного текста или текста, 
    // переданного конструктору
    //
    public function validate($text=null);
    
    // moreInfo()
    // После вызова validate(...) - вызов moreInfo() возвращает ассоциативный 
    // массив с расширенной информацией о валидируемом тексте. В случае 
    // false - что конкретно не понравилось валидатору
    // true - что еще можно сказать о валидируемом тексте (например к какой
    // стране относится проверяемый телефон при валидации телефона, имя домена
    // при валидации электронной почты, варианты написания имени при валидации
    // имени и т.д.
    //
    public function moreInfo();
    
    public function getValid();
}
