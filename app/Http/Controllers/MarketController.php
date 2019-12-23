<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Market;

class MarketController extends Controller
{
    public function all()
    {
        try {
            $markets = Market::all();
            $data = [];
            foreach ($markets as $market)
            {
                $data[]['name'] = $market['name'];
            }
            return response()->json([
                'status' => 'success',
                'msg' => 'Successfully.',
                'data' => $data
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