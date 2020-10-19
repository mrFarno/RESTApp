<?php

namespace renderers\lib\calendar;

class Week
{

    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];


    private $day;
    private $month;
    private $year;


    public function __construct($day = null, $month = null, $year = null)
    {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }
        if ($year === null) {
            $year = intval(date('Y'));
        }
        if ($year < 1970) {
            throw new \Exception("L'année doit etre sup à 1970");
        }
        if ($day === null || $day < 1 || $day > cal_days_in_month(CAL_GREGORIAN, $month, $year)) {
            $day = intval(date('d'));
        }

        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }

    public function getMonday()
    {
        $monday = new \DateTime("{$this->year}-{$this->month}-{$this->day}");
        return $monday->format('N') === '1' ? $monday : $monday->modify('last monday');
    }

    public function getDays()
    {
        return cal_days_in_month(CAL_GREGORIAN, $this->getMonth(), $this->getYear()); 
    }

    public function nextWeek()
    {
        $date = $this->getMonday();
        $date = $date->modify('next monday');
        return new Week($date->format('d'), $date->format('m'), $date->format('Y'));
    }

    public function previousWeek()
    {
        $date = $this->getMonday();
        $date = $date->modify('last monday');
        return new Week($date->format('d'), $date->format('m'), $date->format('Y'));
    }

    public function inMonth(\DateTime $date)
    {
        return (new \DateTime("{$this->year}-{$this->month}-{$this->day}"))->format('Y-m') === $date->format('Y-m');
    }

    public function toString()
    {
        return $this->months[$this->month - 1].' '.$this->year;
    }


    public function getDay()
    {
        return intval($this->day);
    }
    
    public function getMonth()
    {
        return intval($this->month);
    }

    public function getYear()
    {
        return intval($this->year);
    }

}