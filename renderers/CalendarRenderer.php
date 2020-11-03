<?php

namespace renderers;

use renderers\lib\calendar\Month;
use renderers\lib\calendar\Week;

class CalendarRenderer extends BaseRenderer
{
    private $month;
    private $week;
    private $display;
    private $TODAY;

    public function __construct() {
        parent::__construct();   
        $this->setDate();
        $this->display = 'monthly';
        $this->TODAY = intval(date('Y'));
        $this->from = 'home';
    }

    public function options($display = 'monthly') {
        $this->display = $display;
        switch ($this->display) {
            case 'monthly':
                $title = '<h1>'.$this->month->toString().'</h1>';
                $target_display = 'weekly';
                $message = 'Afficher par semaines';
                $previous = '<button class="btn btn-light" type="button" onclick="calendar_settings('.$this->TODAY.', '.$this->month->previousMonth()->getMonth().', '.$this->month->previousMonth()->getYear().', \'monthly\')"><</button>';
                $next = '<button class="btn btn-light" type="button" onclick="calendar_settings('.$this->TODAY.', '.$this->month->nextMonth()->getMonth().', '.$this->month->nextMonth()->getYear().', \'monthly\')">></button>';
                break;
            
            default:
                $title = '<h1>'.$this->week->toString().'</h1>';
                $target_display = 'monthly';
                $message = 'Afficher par mois';
                $previous = '<button class="btn btn-light" type="button" onclick="calendar_settings('.$this->week->previousWeek()->getDay().', '.$this->week->previousWeek()->getMonth().', '.$this->week->previousWeek()->getYear().', \'weekly\')"><</button>';
                $next = '<button class="btn btn-light" type="button" onclick="calendar_settings('.$this->week->nextWeek()->getDay().', '.$this->week->nextWeek()->getMonth().', '.$this->week->nextWeek()->getYear().', \'weekly\')">></button>';
                break;
        }
        $this->output .= $title;
        if ($this->month->getMonth() !== intval(date('m'))) {
            $day = 1;
        } else {
            $day = $this->TODAY;
        }
        $this->output .= '<button class="btn btn-light" type="button" onclick="calendar_settings('.$day.', '.$this->month->getMonth().', '.$this->month->getYear().', \''.$target_display.'\')">'.$message.'</button>';
        $this->output .= $previous;
        $this->output .= $next;

        return $this;
    }

    public function monthly() {
        $this->display = 'monthly';
        $start = $this->month->getStartingDay();
        $start = $start->format('N') === '1' ? $start : $this->month->getStartingDay()->modify('last monday');
        $end = (clone $start)->modify('+'.(6 + 7*($this->month->getWeeks()-1)).' days');
        $this->output .= '<div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">';                
        $this->output .= '<table class="agenda-table agenda-table-'.$this->month->getWeeks().'weeks">';
        for ($i = 0; $i < $this->month->getWeeks(); $i++) {
            $this->output .= '<tr>';
            foreach($this->month->days as $k => $day) {
                $date = (clone $start)->modify("+" . ($k + $i * 7) . " days");
                //if ($k != 5 && $k != 6) {
                    $class = $this->month->inMonth($date) ? '' : 'agenda-othermonth';
                    $this->output .= '<td class="'.$class.'">
                    <button class="td-btn fnt_aw-btn" name="date" value="'.$date->format('Y-m-d').'">';
                    if ($i === 0) {
                        $this->output .= ' <div class="agenda-weekday">'.$day.'</div>';
                    }
                    $is_today = date('Y-m-d') === $date->format('Y-m-d') ? 'current-day' : '' ;
                    $this->output .= '<div class="agenda-day '.$is_today.'">'.$date->format('d').'</div>
                    </button>
                    </td>';
                //}
            }
            $this->output .= '</tr>';
        }
        $this->output .= '</table>';

        return $this;
    }

    public function weekly() {
        $this->display = 'weekly';
        $monday = $this->week->getMonday();
        $start = $monday;
        $end = (clone $start)->modify('next sunday');
        $this->output .= '<div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">';                
        $this->output .= '<table class="agenda-table agenda-table-weeks">';
        foreach($this->week->days as $k => $day) {
            $date = (clone $monday)->modify("+$k days");
            $eventsForDay = $events[$date->format('Y-m-d')] ?? [];  
            //if ($k != 5 && $k != 6) {
                $class = $this->week->inMonth($date) ? '' : 'agenda-othermonth';
                $is_today = date('Y-m-d') === $date->format('Y-m-d') ? 'current-day' : '' ;
                $this->output .= '<td class="'.$class.'">
                    <button class="td-btn fnt_aw-btn" name="date" value="'.$date->format('Y-m-d').'">
                <div class="agenda-weekday">'.$day.'</div>
                <div class="agenda-day '.$is_today.'">'.$date->format('d').'</div>
                </button>
            </td>';
            //}
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