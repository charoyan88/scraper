<?php
/**
 * Created by PhpStorm.
 * User: HelpComp
 * Date: 12/23/2019
 * Time: 6:42 PM
 */

namespace App\Http\Controllers;


use App\Market;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $search = $request['keyword'];
        $url = $url="http://www.amazon.com/s/ref=nb_sb_noss/280-5472358-5762825?url=search-alias%3Daps&field-keywords=$search/";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        $header[] = "Connection: keep-alive";
        $header[] = "Pragma: no-cache";
        $header[] = "Cache-Control: no-cache";
        $header[] = "Upgrade-Insecure-Requests: 1";
        $header[] = "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:51.0) Gecko/20100101 Firefox/51.0";
        $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";
        $header[] = "Accept-Encoding: gzip, deflate";
        $header[] = "Accept-Language: en-EN,ru;q=0.8,en-US;q=0.5,en;q=0.3";
        $header[] = "Cookie: PHPSESSID=6gr04a7dmpdft1ekojd3e7v9u2; referrer=typein; entry_page=http%3A%2F%2Fwww.yell.ru%2Fmoscow%2Fcom%2Fmosvet-mosvet-veterinarnaya-klinika_2028012%2F; edition=moscow; browserId=8zmBJaAVzRveB6dfAX8pry7uXVuHbP; _ym_uid=1477249415409822960; _ym_isad=2; _ga=GA1.2.1396808801.1477249416; _dc_gtm_UA-3064419-7=1";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $html = curl_exec($curl);
        curl_close($curl);

        $response = html_entity_decode($html);
        return response()->json([
            'status' => 'success',
            'msg' => 'Successfully.',
            'data' => $response
        ], 200);
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $insert = [];
        foreach ($data['images'] as $key=>$value)
        {
            $insert[$key]['image_url'] = $value;
            $insert[$key]['price'] = $data['prices'][$key];
            $insert[$key]['title'] = $data['descriptions'][$key];
            $insert[$key]['shop_id'] = Market::where('name','Amazon')->first()->id;
        }
        foreach ($insert as $item)
        {
            Product::updateOrCreate(['title'=>$item['title']],$item);
        }
        return response()->json([
            'status' => 'success',
            'msg' => 'Products has saved.',
            'data' => "Products has saved"
        ], 200);

    }
    public function all()
    {
        {
            try {
                $products = Product::all();
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Successfully.',
                    'data' => $products
                ], 200);
            } catch (\Exception $exception) {
                $data = $exception->getMessage();
                return response()->json([
                    'status' => 'Error',
                    'msg' => $data,
                ], 404);

            }
        }
    }
}