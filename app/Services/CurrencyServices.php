<?php

namespace App\Services;

class CurrencyServices
{
    public function convertCurrency(string $currency, float $price): array
    {
        if ($currency === 'USD') {
            // 將價格乘以 31 並改變幣別為 TWD
            $convertedPrice = $price * 31;
            return [
                'price' => $convertedPrice,
                'currency' => 'TWD',
            ];
        }

        // 如果不是 USD，則返回原始價格和幣別
        return [
            'price' => $price,
            'currency' => $currency,
        ];
    }
}
