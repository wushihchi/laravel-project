<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\CurrencyServices;

class CurrencyServicesTest extends TestCase
{
    protected $currencyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currencyService = new CurrencyServices();
    }
    public function test_convertCurrency_TWD(): void
    {
        $result = $this->currencyService->convertCurrency('TWD', 100);

        $this->assertEquals(100, $result['price']);
        $this->assertEquals('TWD', $result['currency']);
    }
    public function test_convertCurrency_USD(): void
    {
        $result = $this->currencyService->convertCurrency('USD', 100);

        $this->assertEquals(3100, $result['price']);
        $this->assertEquals('TWD', $result['currency']);
    }
}
