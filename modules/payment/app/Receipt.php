<?php
namespace app\modules\payment\app;
use app\modules\basket\models\Basket;

/**
 * Created by PhpStorm.
 * User: o.trushkov
 * Date: 20.02.18
 * Time: 10:26
 */
class Receipt
{

    public $customerContact;
    public $taxSystem;
    public $items;

    public function __construct($customerContact,$taxSystem=null,$items=[])
    {

        $this->customerContact = $customerContact;
        $this->taxSystem = $taxSystem;
        $this->items = $items;
    }


    public function setBasket(Basket $basket){

        $basket->loadBasket();
        $basket->loadModels();
/*{
        "quantity": 1.154,
        "price": {
            "amount": 300.23
        },
        "tax": 3,
        "text": "Зеленый чай \"Юн Ву\", кг"
    }*/
        foreach ( $basket->singleProducts as $item){
            if (! is_object($item['model'])) continue;
            $itemR['quantity'] = $item['count'];
            $itemR['price'] = [ 'amount'=>   $this->formatPrice(  $item['model']->curr_price) ] ;
            $itemR['tax'] = 1;
            $itemR['text'] = $this->formatText($item['model']->name);
            $this->items[] = $itemR;
        }
        foreach ($basket->prodMods as $item){
            if (! is_object($item['model'])) continue;
            $itemR['quantity'] = $item['count'];
            $itemR['price'] = [ 'amount'=> $this->formatPrice(  $item['model']->price_roz ) ];
            $itemR['tax'] = 1;
            $itemR['text'] = $this->formatText( $item['model']->parentProduct_r->name . ' ' . $item['model']->mod_name);
            $this->items[] = $itemR;
        }
        foreach ($basket->sets as $item){
            if (! is_object($item['model']) || ! count($item['set']->product_sets_r)  ) continue;
            foreach ($item['set']->product_sets_r as $product_set){
                $itemR['quantity'] =$item['count'];
                $itemR['price'] =  ['amount'=> $this->formatPrice($product_set->product_r->curr_price ) ];
                $itemR['tax'] = 1;
                $itemR['text'] = $this->formatText($product_set->product_r->name);
                $this->items[] = $itemR;
            }

        }


        foreach ($basket->kits as $item){

            if ( ! is_object($item['set'])  ) continue;

            foreach ($item['set']->productKits_r as $product_set){
                $itemR['quantity'] = $item['count'];
                $itemR['price'] = ['amount'=>$this->formatPrice($product_set->product_r->curr_price ) ];
                $itemR['tax'] = 1;
                $itemR['text'] =  $this->formatText( $product_set->product_r->name );
                $this->items[] = $itemR;
            }

            if (isset($item['dops'])){
                foreach ($item['dops']as $product){
                    $itemR['quantity'] = $item['count'];
                    $itemR['price'] = ['amount'=>$this->formatPrice($product->curr_price ) ];
                    $itemR['tax'] = 1;
                    $itemR['text'] = $this->formatText( $item['model']->name);
                    $this->items[] = $itemR;
                }
            }

        }
        // if (is_object($kit['set']))


    }
    public function makeReceipt(){
        $arr = [
            'customerContact'=>$this->customerContact,
            'taxSystem' => 1
        ];
        $arr['items'] = $this->items;
        return $arr;
    }

    public function formatText($text){
        if ( strlen( $text) > 128){
            $text = substr($text,0,128);
        }
        return $text;
    }

    public function formatPhone($phone){
        if ( strlen( $phone) > 64){
            $phone = substr($phone,0,64);
        }
        return '+'.(str_replawce(['(',')','-'],'',$phone));
    }

    private function formatPrice($price){
        return number_format((float)$price , 2, '.', '');
    }

}