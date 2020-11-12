<?php


namespace renderers;


class ProductsRenderer extends BaseRenderer
{
    public function __construct() {
        parent::__construct();
        $this->from = 'products';
    }

    public function products_modal($restaurant, $day) {
        $this->output .= '<div class="modal fade" aria-labelledby="manualModalLabel" id="products_modal" style="margin-bottom: 1rem"  tabindex="-1" role="dialog" aria-hidden="true">
            <div  class="modal-dialog modal-lg" role="document" id="formManual">
                <div class="modal-content" style="margin-top: 33%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manualModalLabel"><span id="eq_name-modal">Suivi des marchandises</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">  
                    <form method="POST" action="?page=products" enctype="multipart/form-data">
                    <div class="justify-content-center">
                        <label for="product-current-input">Nom : </label>
                        <input id="product-current-input" name="p_name" type="text">  
                    </div>  
                    <input type="hidden" name="p_id">                                     
                    </form>                                       
                    </div>
                </div>
            </div>
        </div>';
        return $this;
    }

    public function products_list($products) {
        $this->output .= '<h2 style="text-align: center;">Marchandise</h2><br>';
        $this->output .= '<div class="" style="    max-height: 70vh !important;
overflow-y: scroll !important;">
        <table class="table table-hover" style="">
                <th>Nom/réference</th>
                <th>Fournisseur</th>
                <th>Stock</th>
                <th>Aspect</th>
                <th>Température</th>
                <th>Renvoyé</th>
                <th>Photo</th>
                <th>Suivi</th>';
        foreach ($products as $product) {
            $sent = $product['p_sent_back'] == 1 ? 'Oui' : 'Non';
            $this->output .= '<tr>
            <td>'.$product['p_name'].'</td>
            <td>'.$product['p_provider'].'</td>                     
            <td>'.$product['p_stock'].'</td>                     
            <td>'.$product['p_aspect'].'</td>                     
            <td>'.$product['p_temperature'].'</td>                     
            <td>'.$sent.'</td>                                        
            <td></td>                                        
            <td>
                <button type="button" onclick="init_products_modal(\''.$product['p_id'].'\')" class="fnt_aw-btn" data-toggle="modal" data-target="#products_modal">
                    <i class="far fa-clipboard"></i>
                </button>
            </td>                                        
        </tr>';
        }
//        $this->output .= '<tr>
//            <td><input type="text" name="p_name" required></td>
//            <td><input type="text" name="p_provider"></td>
//            <td><input type="text" name="p_aspect"></td>
//            <td><input type="number" name="p_temperature"></td>
//            <td><input type="checkbox" name="p_sent_back"></td>
//            <td></td>
//            <td>
//            <button onclick="post_form(\'products\', \'meals\'); load_form(\'products\', \'meals\')" type="button" class="btn btn-outline-success width100">
//                +
//            </button></td>
//        </tr>';
//
//        $this->next_btn('products','guests');
//        $this->home('products');
        return $this;
    }
}