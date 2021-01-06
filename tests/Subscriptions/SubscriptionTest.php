<?php

namespace Bestys\GooglePlay\Tests\Subscriptions;

use GuzzleHttp\Exception\GuzzleException;
use Bestys\GooglePlay\ClientFactory;
use Bestys\GooglePlay\Subscriptions\Subscription;
use Bestys\GooglePlay\Subscriptions\SubscriptionPurchase;
use Bestys\GooglePlay\Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * @var Subscription
     */
    private $subscription;

    protected function setUp(): void
    {
        parent::setUp();

        $client = ClientFactory::create([ClientFactory::SCOPE_ANDROID_PUBLISHER]);
        $packageName = 'com.twigano.v2';
        $subscriptionId = 'week_premium';
        $token = 'biakahdmblijpjncbijdicac.AO-J1OyEeH6JlhqDSPVIBxpQhG9iFDyz8cVLNx5uLjmLphKmKWjnKpA4d8Q3aDDGRMc-VxQ7IkPTgnTx37NcCWNBkSmxKi1nKAYmc3CyFw21pgHps4dQmJo';

        $this->subscription = new Subscription($client, $packageName, $subscriptionId, $token);
    }

    /**
     * @test
     */
    public function test_get_method()
    {
        $this->assertInstanceOf(SubscriptionPurchase::class, $this->subscription->get());
    }

    /**
     * @test
     * @throws GuzzleException
     */
    public function test_acknowledge()
    {
        $this->subscription->acknowledge();
        $this->assertTrue($this->subscription->get()->getAcknowledgementState()->isAcknowledged());
    }
}
