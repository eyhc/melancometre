<?php

class Averager
{
    private $data;
    private $precision;

    public function __construct($precision)
    {
        $this->precision = $precision;
        return $this->reset();
    }

    public function add($value)
    {
        $data = $this->data;
        $data[] = $value;
        $this->data = $data;

        return $this;
    }

    public function getAverage() {
        $s = 0;
        $i = 0;
        foreach ($this->data as $value) {
            $s += $value;
            $i++;
        }

        if ($i == 0) {
            return false;
        }
        return round($s / $i, $this->precision);
    }

    public function reset() {
        $this->data = array();
        return $this;
    }
}
