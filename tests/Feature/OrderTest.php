<?php

namespace Tests\Feature;

use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_success()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'id' => 'A0000001',
                    'name' => 'Melody Holiday Inn',
                    'address' => [
                        'city' => 'taipei-city',
                        'district' => 'da-an-district',
                        'street' => 'fuxing-south-road',
                    ],
                    'price' => 205,
                    'currency' => 'TWD'
                ]);
    }

    public function test_success_currency_convert()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'USD'
        ]);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'id' => 'A0000001',
                    'name' => 'Melody Holiday Inn',
                    'address' => [
                        'city' => 'taipei-city',
                        'district' => 'da-an-district',
                        'street' => 'fuxing-south-road',
                    ],
                    'price' => 6355,
                    'currency' => 'TWD'
                ]);
    }

    public function test_id_is_required()
    {
        $response = $this->postJson('/api/orders', [
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['id'])
                ->assertJsonFragment([
                    'id' => [
                        'Id is required.',
                    ]
                ]);
    }
    
    public function test_id_must_be_string()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 12345678,  // 傳入非字串的 id
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['id'])
                ->assertJsonFragment([
                    'id' => [
                        'Id must be a valid string.',
                    ]
                ]);
    }

    public function test_id_must_be_8_characters()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A12345678',  // 傳入非字串的 id
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['id'])
                ->assertJsonFragment([
                    'id' => [
                        'Id must be exactly 8 characters long.',
                    ]
                ]);
    }

    public function test_name_is_required()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['name'])
                ->assertJsonFragment([
                    'name' => [
                        'Name is required.',
                    ]
                ]);
    }

    public function test_name_must_be_english_characters()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn2',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['name'])
                ->assertJsonFragment([
                    'name' => [
                        'Name contains non-English characters.',
                        'Name is not capitalized.',
                    ]
                ]);
    }

    public function test_name_must_be_Upper()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['name'])
                ->assertJsonFragment([
                    'name' => [
                        'Name is not capitalized.',
                    ]
                ]);
    }

    public function test_address_city_is_required()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['address.city'])
                ->assertJsonFragment([
                    'address.city' => [
                        'Address City is required.'
                    ]
                ]);
    }

    public function test_address_district_is_required()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['address.district'])
                ->assertJsonFragment([
                    'address.district' => [
                        'Address District is required.'
                    ]
                ]);
    }

    public function test_address_street_is_required()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
            ],
            'price' => 205,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['address.street'])
                ->assertJsonFragment([
                    'address.street' => [
                        'Address Street is required.'
                    ]
                ]);
    }

    public function test_price_is_required()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['price'])
                ->assertJsonFragment([
                    'price' => [
                        'Price is required.',
                    ]
                ]);
    }

    public function test_price_is_numeric()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 'A205',
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['price'])
                ->assertJsonFragment([
                    'price' => [
                        'Price must be a number.',
                    ]
                ]);
    }

    public function test_price_must_be_greater_than_0()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => -1,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['price'])
                ->assertJsonFragment([
                    'price' => [
                        'Price must be greater than 0.',
                    ]
                ]);
    }

    public function test_price_is_over_2000()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 2050,
            'currency' => 'TWD'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['price'])
                ->assertJsonFragment([
                    'price' => [
                        'Price is over 2000.',
                    ]
                ]);
    }

    public function test_currency_is_required()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['currency'])
                ->assertJsonFragment([
                    'currency' => [
                        'Currency is required.',
                    ]
                ]);
    }

    public function test_currency_must_be_either_TWD_or_USD()
    {
        $response = $this->postJson('/api/orders', [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => 205,
            'currency' => 'TEST'
        ]);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['currency'])
                ->assertJsonFragment([
                    'currency' => [
                        'Currency format is wrong.',
                    ]
                ]);
    }
}
