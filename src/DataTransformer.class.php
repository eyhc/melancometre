<?php
require_once('Averager.class.php');

class DataTransformer
{
    private $data;
    private $labels = array();
    private $general_data = array();
    private $moral_data = array();
    private $energy_data = array();
    private $suicidal_ideas_data = array();

    public function __construct($states)
    {
        $this->data = $states;
        return $this->transform();
    }

    public function getTransformedData() {
        return array(
            'count' => count($this->data),
            'data_labels' => array(
                'g' => trans('General'),
                'm' => trans('Moral'),
                'e' => trans('Energy'),
                'si' => trans('Suicidal ideas')
            ),
            'labels' => $this->labels,
            'general' => $this->general_data,
            'moral' => $this->moral_data,
            'energy' => $this->energy_data,
            'suicidal_ideas' => $this->suicidal_ideas_data
        );
    }

    /* transform data for chart */
    private function transform()
    {
        $g_averager = new Averager(0);
        $m_averager = new Averager(0);
        $e_averager = new Averager(0);
        $si_averager = new Averager(0);

        foreach ($this->data as $state) {
            $dt = new DateTime($state['date']);
            $date = $dt->format('j M Y');
            if (!in_array($date, $this->labels)) {
                $this->labels[] = $date;
                $this->addValue('general', $g_averager, $state['general']);
                $this->addValue('moral', $m_averager, $state['moral']);
                $this->addValue('energy', $e_averager, $state['energy']);
                $this->addValue('si', $si_averager, $state['suicidal_ideas']);
            }
            else {
                $g_averager->add($state['general']);
                $m_averager->add($state['moral']);
                $e_averager->add($state['energy']);
                $si_averager->add($state['suicidal_ideas']);
            }
        }
        $this->addValueAtEnd('general', $g_averager);
        $this->addValueAtEnd('moral', $m_averager);
        $this->addValueAtEnd('energy', $e_averager);
        $this->addValueAtEnd('si', $si_averager);


        return $this;
    }

    /* repeated if else endif part */
    private function addValue($name, Averager $av, $add)
    {
        if ($av->getAverage() !== false) {
            $this->addValueOnList($name, $av->getAverage());
            $av->reset()->add($add);
        }
        else {
            $av->add($add);
        }
    }

    private function addValueAtEnd($name, Averager $av)
    {
        if ($av->getAverage() !== false) {
            $this->addValueOnList($name, $av->getAverage());
        }
    }

    private function addValueOnList($name, $value)
    {
        switch ($name) {
            case 'general': $this->general_data[] = $value; break;
            case 'moral': $this->moral_data[] = $value; break;
            case 'energy': $this->energy_data[] = $value; break;
            case 'si': $this->suicidal_ideas_data[] = $value; break;
        }
    }
}
