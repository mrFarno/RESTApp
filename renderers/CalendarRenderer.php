<?php

namespace renderers;

use renderers\lib\calendar\Month;
use renderers\lib\calendar\Week;

class CalendarRenderer extends BaseRenderer
{
    private $month;
    private $week;

    public function __construct() {
        parent::__construct();   
        $this->setDate();
    }

    public function options() {
        $this->output .= '';

        return $this;
    }

    public function monthly() {
        $start = $this->month->getStartingDay();
        $start = $start->format('N') === '1' ? $start : $this->month->getStartingDay()->modify('last monday');
        $end = (clone $start)->modify('+'.(6 + 7*($this->month->getWeeks()-1)).' days');
        $this->output .= '<div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1>'.$this->month->toString().'</h1>
                <table class="agenda-table agenda-table-'.$this->month->getWeeks().'weeks">';
        for ($i = 0; $i < $this->month->getWeeks(); $i++) {
            $this->output .= '<tr>';
            foreach($this->month->days as $k => $day) {
                $date = (clone $start)->modify("+" . ($k + $i * 7) . " days");               
                if ($k != 5 && $k != 6) {
                    $class = $this->month->inMonth($date) ? '' : 'agenda-othermonth';
                    $this->output .= '<td class="'.$class.'">';
                    if ($i === 0) {
                        $this->output .= ' <div class="agenda-weekday">'.$day.'</div>';
                    }
                    $is_today = date('Y-m-d') === $date->format('Y-m-d') ? 'current-day' : '' ;
                    $this->output .= '<div class="agenda-day '.$is_today.'"></div>
                    </td>';
                }
            }
            $this->output .= '</tr>';
        }
        $this->output .= '</table>';

        return $this;
    }

    public function weekly() {
        $monday = $this->week->getMonday();
        $start = $monday;
        $end = (clone $start)->modify('next sunday');
        $this->output .= '<div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1>'.$this->week->toString().'</h1>
                <table class="agenda-table agenda-table-weeks">';
        foreach($this->week->days as $k => $day) {
            $date = (clone $monday)->modify("+$k days");
            $eventsForDay = $events[$date->format('Y-m-d')] ?? [];  
            if ($k != 5 && $k != 6) {
                $class = $this->week->inMonth($date) ? '' : 'agenda-othermonth';
                $is_today = date('Y-m-d') === $date->format('Y-m-d') ? 'current-day' : '' ;
                $this->output .= '<td class="'.$class.'">
                <div class="agenda-weekday">'.$day.'</div>
                <div class="agenda-day '.$is_today.'"></div>
            </td>';
            }
        }
        $this->output .= '</table>';

        return $this;
    }

    public function setDate(int $day = null, int $month = null, int $year = null) {
        try {
            $this->month = new Month($month ?? null, $year ?? null);
            $this->week = new Week($day ?? null, $month ?? null, $year ?? null);
        } catch (\Exception $e) {
            $this->month = new Month();
            $this->week = new Week();
        }  
        return $this;  
    }
}