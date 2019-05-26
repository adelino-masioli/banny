<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Models\Coupon;
use App\Models\CouponItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use Goutte\Client;

class CouponController extends Controller
{

    public function all(Request $request)
    {
        $user = $request->user();
        $coupons =  Coupon::select('id', 'company', 'date', 'total', 'user_id', 'document', 'datetime', 'payment_method', 'url')->where('user_id', $user->id);

        if($coupons->count() > 0){
            return ['coupons'=>$coupons->get(), 'coupons_total'=>$coupons->count()];
        }else{
            return ['coupons_total'=>$coupons->count()];
        }
    }

    public function last(Request $request)
    {
        $user = $request->user();
        $coupons =  Coupon::select('id', 'company', 'date', 'total', 'user_id', 'document', 'datetime', 'payment_method', 'url')->where('user_id', $user->id)->limit(4);
 
        if($coupons->count() > 0){
            return ['coupons'=>$coupons->get(), 'coupons_total'=>$coupons->count()];
        }else{
            return ['coupons_total'=>$coupons->count()];
        }
    }

    public function detail($id, Request $request)
    {
        $user = $request->user();
        $coupon =  Coupon::select('id', 'company', 'date', 'total', 'user_id')->where('id', $id)->first();
        $items  =  CouponItem::select('id', 'description', 'amount', 'value', 'total', 'coupon_id')->where('coupon_id', $coupon->id)->get();
        $coupons_total =  Coupon::select('id')->where('status', 1)->count('id');

        return ['items'=>$items, 'coupons_total'=>$coupons_total];
    }


    //create coupon
    protected function store(Request $request)
    {
        $user = $request->user();
        try{
            if($request->cupom!=''){
                $url         = $request->cupom;
                $url_remove  = "https://www.sefaz.rs.gov.br/NFCE/NFCE-COM.aspx";
                $url_partial =  str_replace($url_remove, '', $url);
                $url_final   = 'https://www.sefaz.rs.gov.br/ASP/AAE_ROOT/NFE/SAT-WEB-NFE-NFC_QRCODE_1.asp'.$url_partial;

                if(Coupon::where('url', $url_final)->count() == 0){
                    $coupon = Coupon::create([
                        'url'      => $url_final,
                        'status'   => 1,
                        'user_id'  => $user->id,
                    ]);
                    $this->createTitle($coupon->id, $url_final);
                    $this->updateCaption($coupon->id, $url_final);
                    $this->updateFooter($coupon->id, $url_final);
                    $this->createItems($coupon->id, $url_final);
                }
            }
            return ['status'=>true];
        }catch(\Exception $e){
            return ['status'=>false];
        }
    }

    //create title
    public static function createTitle($id, $url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $output_title = $crawler->filter(".NFCCabecalho_SubTitulo")->extract("_text");

        $coupon = Coupon::findOrFail($id);

        $remove_keys = [1, 3, 4, 6, 7, 8];
        foreach($output_title as $key => $out){
            $coupon->update([
                'company'        => $output_title[0],
                'datetime'       => $output_title[2],
                'date'           => substr($output_title[2], -19,-8),
                'time'           => substr($output_title[2], -8),
                'key_access'     => $output_title[5],
                'protocol'       => $output_title[6],
            ]);
        }
    }

    //update caption
    public static function updateCaption($id, $url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $output_title = $crawler->filter(".NFCCabecalho_SubTitulo1")->extract("_text");

        $coupon = Coupon::findOrFail($id);

        $remove_keys = [1, 3, 4, 6, 7, 8];
        foreach($output_title as $key => $out){
            $coupon->update([
                'document'      => trim($output_title[0]),
                'address'       => trim($output_title[1])
            ]);
        }
    }

    //update footer
    public static function updateFooter($id, $url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $output_items = $crawler->filter("table tr .NFCDetalhe_Item")->extract('_text');
        //footer
        $remove_itens = ['Código', 'Descrição', 'Qtde', 'Un', 'Vl Unit', 'Vl Total'];
        $index = 0;
        $remove_array =  count($output_items);

        $coupon = Coupon::findOrFail($id);

        $remove_keys = [1, 3, 4, 6, 7, 8];
        foreach($output_items as $key => $out){
            if(!in_array($out, $remove_itens)){
                $minus = $remove_array - $index;
                if($minus == 8){
                    $coupon->update([
                        'total'          => trim($out)
                    ]);
                }
                if($minus == 6){
                    $coupon->update([
                        'discount'       => trim($out)
                    ]);
                }
                if($minus == 3){
                    $coupon->update([
                        'payment_method' => trim($out) == 'Outros' ? 'Dinheiro' :  trim($out)
                    ]);
                }
            }
            $index ++;
        }
    }

    //create itens
    public static function createItems($id, $url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

         //itens
        $output_items = $crawler->filter("table tr .NFCDetalhe_Item")->extract('_text');

        $remove_itens = ['Código', 'Descrição', 'Qtde', 'Un', 'Vl Unit', 'Vl Total'];
        $remove_array =  count($output_items);
        $index = 1;
        $result = array();

        foreach($output_items as $key => $out){

            if(!in_array($out, $remove_itens)){

                if($out=="Valor total R$"){
                    break;
                }

                //verify nodes
                if($index==1){
                    $code        = $output_items[$key];
                    $description = $output_items[$key+1];
                    $amount      = $output_items[$key+2];
                    $unit        = $output_items[$key+3];
                    $value       = $output_items[$key+4];
                    $total       = $output_items[$key+5];
                }

                //insert items
                if(CouponItem::where('coupon_id', $id)->where('code', $code)->count() == 0){
                    CouponItem::create([
                        'code'        => $code,
                        'description' => $description,
                        'amount'      => $amount,
                        'unit'        => $unit,
                        'value'       => Helpers::money_reverse($value),
                        'total'       => Helpers::money_reverse($total),
                        'coupon_id'   => $id,
                    ]);
                }

                if($index==6){
                    $index=1;
                }else{
                    $index++;
                }

            }

        }

        $sum_total = CouponItem::where('coupon_id', $id)->sum('total');
        $cupom_validate = Coupon::where('id', $id)->first();
        $cupom_validate->update(['total'=>$sum_total]);
        if($cupom_validate->total == 0.00){
            $cupom_validate->delete();
        }
    }
}
