<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\OrderRequest;
use App\Services\CurrencyServices;

class OrderController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyServices $currencyService)
    {
        $this->currencyService = $currencyService;
    }
    // POST api/orders 檢查訂單的必要欄位以及是否為指定型態
    // form validation 將訂單資料送到service做訂單格式檢查與轉換
    // service format checking & transform 訂單格式檢查與轉換(loop) 
    // 返回檢查與轉換結果(response) -> API回應結果

    // 訂單格式檢查與轉換 
    // name檢查 
    // 1.包含非英文字母 丟出400 name contains non-English charactors
    // 2.每個單字首字母非大寫 丟出400 Name is not capitalized

    // price檢查
    // 1.訂單金額超過2000 丟出400 Price is over 2000

    // currency檢查
    // 1.貨幣格式若非 TWD 或 USD - Currency format is wrong
    // 2.當貨幣為 USD 時，需修改 price 金額乘上匯率31元，並且將 currency 改為TWD
    public function store(OrderRequest $request): JsonResponse
    {
        // 獲取幣別和價格
        $currency = $request->currency;
        $price = $request->price;

        // 使用 CurrencyConvertService 進行幣別轉換
        $convertedData = $this->currencyService->convertCurrency($currency, $price);
        // 轉換訂單資料格式
        $orderData = [
            'id' => $request->id,
            'name' => $request->name,
            'address' => [
                'city' => $request->address['city'],
                'district' => $request->address['district'],
                'street' => $request->address['street'],
            ],
            'price' => $convertedData['price'],
            'currency' => $convertedData['currency'],
        ];

        // 這裡可以將訂單資料保存到資料庫，這裡簡化為回傳
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $orderData,
        ], 201);
    }
}
