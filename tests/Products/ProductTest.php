<?php

namespace Bestys\GooglePlay\Tests\Products;

use GuzzleHttp\Exception\GuzzleException;
use Bestys\GooglePlay\ClientFactory;
use Bestys\GooglePlay\Products\Product;
use Bestys\GooglePlay\Products\ProductPurchase;
use Bestys\GooglePlay\Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $client = ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $this->product = new Product(
            $client,
            'com.twigano.v2',
            'boost_profile',
            'pbehplldfhianpgebmleegak.AO-J1Ox7SK22iXuGeWyOVQ-iCokC4UNOqiVwObG4avOfGCovt7GbpA7ih9KdXr4yQTmQUOPQulMksyVmaTq3VR2-VlTss_Pyue6i6aFgBotxvf2oXyTFfww'
        );
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_get()
    {
        $this->product->get()->getPurchaseTime();
        $response = $this->product->get();
        $this->assertInstanceOf(ProductPurchase::class, $response);
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_acknowledge()
    {
        $this->product->acknowledge();
        $this->assertTrue($this->product->get()->getAcknowledgementState()->isAcknowledged());
    }
}
