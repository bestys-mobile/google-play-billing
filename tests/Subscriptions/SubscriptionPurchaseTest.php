<?php

namespace Bestys\GooglePlay\Tests\Subscriptions;

use Faker\Factory;
use Bestys\GooglePlay\Subscriptions\SubscriptionPurchase;
use Bestys\GooglePlay\Tests\TestCase;
use Bestys\GooglePlay\ValueObjects\Cancellation;
use Bestys\GooglePlay\ValueObjects\IntroductoryPriceInfo;
use Bestys\GooglePlay\ValueObjects\PromotionType;
use Bestys\GooglePlay\ValueObjects\SubscriptionPriceChange;

class SubscriptionPurchaseTest extends TestCase
{
    /**
     * @test
     */
    public function test_it_can_be_created_from_response_body()
    {
        $faker = Factory::create();
        $body = [
            'kind' => 'some_kind',
            'startTimeMillis' => $faker->unixTime,
            'expiryTimeMillis' => $faker->unixTime,
            'autoResumeTimeMillis' => null,
            'autoRenewing' => $faker->boolean,
            'priceCurrencyCode' => $faker->currencyCode,
            'introductoryPriceInfo' => null,
            'countryCode' => $faker->countryCode,
        ];

        $this->assertInstanceOf(SubscriptionPurchase::class, SubscriptionPurchase::fromResponseBody($body));
    }

    /**
     * @test
     */
    public function test_it_can_get_IntroductoryPriceInfo()
    {
        $faker = Factory::create();
        $body = [
            'kind' => 'some_kind',
            'introductoryPriceInfo' => [
                'introductoryPriceCurrencyCode' => $faker->currencyCode,
                'introductoryPriceAmountMicros' => $faker->numberBetween(),
                'introductoryPricePeriod' => $faker->randomElement(['P1W', 'P1M', 'P3M', 'P6M', 'P1Y']),
                'introductoryPriceCycles' => $faker->numberBetween(),
            ],
        ];
        $introductoryPriceInfo = SubscriptionPurchase::fromResponseBody($body)->getIntroductoryPriceInfo();
        $this->assertInstanceOf(IntroductoryPriceInfo::class, $introductoryPriceInfo);
    }

    /**
     * @test
     */
    public function test_it_can_get_PriceChange()
    {
        $faker = Factory::create();
        $body = [
            'kind' => 'some_kind',
            'priceChange' => [
                'newPrice' => [
                    'priceMicros' => $faker->numberBetween(),
                    'currency' => $faker->currencyCode,
                ],
                'state' => $faker->randomElement([0, 1]),
            ],
        ];

        $priceChange = SubscriptionPurchase::fromResponseBody($body)->getPriceChange();
        $this->assertInstanceOf(SubscriptionPriceChange::class, $priceChange);
    }

    /**
     * @test
     */
    public function test_it_can_get_cancellation()
    {
        $faker = Factory::create();
        $body = [
            'kind' => 'some_kind',
            'cancelReason' => $faker->randomElement([0, 1, 2, 3]),
            'userCancellationTimeMillis' => $faker->unixTime,
            'cancelSurveyResult' => [
                'cancelSurveyReason' => $faker->randomElement(range(0, 4)),
                'userInputCancelReason' => $faker->sentence,
            ],
        ];

        $cancellation = SubscriptionPurchase::fromResponseBody($body)->getCancellation();
        $this->assertInstanceOf(Cancellation::class, $cancellation);
    }

    /**
     * @test
     */
    public function test_it_can_get_promotion()
    {
        $faker = Factory::create();
        $body = [
            'kind' => 'some_kind',
            'promotionType' => rand(0, 1),
            'promotionCode' => $faker->word(),
        ];

        $promotion = SubscriptionPurchase::fromResponseBody($body)->getPromotionType();
        $this->assertInstanceOf(PromotionType::class, $promotion);
    }
}
