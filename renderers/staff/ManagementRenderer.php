<?php


namespace renderers\staff;


use renderers\BaseRenderer;

class ManagementRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'management';
    }

    public function links($day, $meal) {
        $this->set_day($day);
//        $this->output .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12">
//            <div class="row justify-content-between">
//            <img src="'.$GLOBALS['domain'].'/public/style/resources/icons/meals.png">
//                <a href="?page=meals&date='.$day.'">Repas</a>
//                <a href="?page=cleaning&date='.$day.'">Nettoyage et désinfection</a>
//                <a href="?page=production&date='.$day.'">Production</a>
//                <a href="?page=products&date='.$day.'">Marchandise</a>
//            </div>
//        </div>';
        $this->output .= '<br><br><div class="row">';
        $this->link('meals', $day, $meal)
                ->link('products', $day, $meal)
                ->link('production', $day, $meal);
        $this->output .= '</div>';
        $this->output .= '<br><div class="row">';
        $this->link('service', $day, $meal)
                ->link('cleaning', $day, $meal)
                ->link('biowaste', $day, $meal);
        $this->output .= '</div>';
        $this->output .= '<br><div class="row">';
        $this->link('nutrition', $day, $meal, false)
            ->link('events', $day, $meal)
            ->link('extraction', $day, $meal, false);
        $this->output .= '</div>';
        return $this;
    }

    private function link($page, $day, $meal, $clickable = true) {
        $trads = [
            'meals' => 'Vérifications pré-repas',
            'products' => 'Marchandises',
            'production' => 'Production',
            'cleaning' => 'Nettoyage et désinfection',
            'biowaste' => 'Suivi des biodéchets',
            'service' => 'Service',
            'nutrition' => 'Nutrition',
            'events' => 'Animation/Évenementiel',
            'extraction' => 'Extraction'
        ];
        $this->output .= '<div class="col-4">';
        $img = '<img class="icon-img" id="'.$page.'-page" src="'.$GLOBALS['domain'].'/public/style/resources/icons/'.$page.'.png"> 
            <p style="text-align: center; color: black; font-size: large;" for="'.$page.'-page">'.$trads[$page].'</p>';
        if ($clickable === true) {
            $this->output .= '<a href="?page='.$page.'&date='.$day.'&current-meal='.$meal.'">
            '.$img.'
            </a>';
        } else {
            $this->output .= $img;
        }
        $this->output .= '</div>';

        return $this;
    }

    private function set_day($day) {
        $this->from .= '&date='.$day;

        return $this;
    }
}