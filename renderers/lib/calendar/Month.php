<?php

namespace renderers\lib\calendar;

class Month
{
    
    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    private $month;
    private $year;

    /**
     * @param int $month Le mois entre 1 et 12
     * @param int $year L'année
     */
    public function __construct(int $month = null, int $year = null) 
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

        $this->month = $month;
        $this->year = $year;
    }

    public function toString()
    {
        return $this->months[$this->month - 1].' '.$this->year;
    }

    public function getWeeks()
    {
       $start = $this->getStartingDay();
       $end = (clone $start)->modify('+1 month -1 day');
       $startWeek = intval($start->format('W'));
       $endWeek = intval($end->format('W'));
       if ($endWeek === 1) {
           $endWeek = intval((clone $end)->modify('-7 days')->format('W')) + 1;
       }
       $weeks = $endWeek - $startWeek + 1;
       if ($weeks < 0) {
           $weeks = intval($end->format('W'));
       }

       return $weeks;
    }

    public function getStartingDay()
    {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

    public function inMonth(\DateTime $date)
    {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    public function nextMonth()
    {
        $month = $this->month + 1;
        $year = $this->year;
        if ($month > 12) {
            $month = 1;
            $year += 1;
        }

        return new Month($month, $year);
    }

    public function previousMonth()
    {
        $month = $this->month - 1;
        $year = $this->year;
        if ($month < 1) {
            $month = 12;
            $year -= 1;
        }

        return new Month($month, $year);
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }


    public function render() {

    }

}