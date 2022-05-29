<?php

class Translator
{
    const FR = 'fr';
    const EN = 'en';
    const IT = 'it';

    private $lang;
    private $dictionary = array();

    public function __construct($lang = 'en')
    {
        $this->lang = $lang;
        $this->createDictonary();
    }

    public function getDictionary()
    {
        return $this->dictionary;
    }

    public function trans($value)
    {
        if (isset($this->dictionary[$this->lang][$value])) {
            return $this->dictionary[$this->lang][$value];
        }
        elseif (isset($this->dictionary[self::EN][$value])) {
            return $this->dictionary[self::EN][$value];
        }
        else {
            return $value;
        }
    }

    /**
     * @return translations of each part of $date delimited by a space
     */
    public function transDate($date)
    {
        $parts = explode(' ', $date);
        $date2 = "";
        foreach ($parts as $part) {
            if ($date2 !== "") {
                $date2 .= ' ';
            }
            $date2 .= $this->trans($part);
        }
        return  $date2;
    }

    public function transDates($dates) {
        $t = array();
        foreach ($dates as $d) {
            $t[] =  $this->transDate($d);
        }
        return $t;
    }

    private function createDictonary()
    {
        $dir = dirname(__FILE__) . '/../translations/';
        if (is_dir($dir) && ($dh = opendir($dir))) {
            while (($file = readdir($dh) ) !== false) {
                if($file != '.' && $file != '..'){
                    $file_content = file_get_contents($dir . $file);
                    if ($this->isJson($file_content)) {
                        $d = (array) json_decode($file_content);

                        if (!isset($this->dictionary[$d['lang']])) $this->dictionary[$d['lang']] = array();
                        $this->dictionary[$d['lang']] = array_merge($this->dictionary[$d['lang']], (array) $d['dictionary']);
                    }
                }
            }
            closedir($dh);
        }
    }

    private function isJson($string) {
        return
            is_string($string) &&
            (
                is_object(json_decode($string)) ||
                is_array(json_decode($string))
            )
        ;
    }
}
