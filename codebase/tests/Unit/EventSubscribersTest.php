<?php

namespace App\Tests\Unit;

use App\EventSubscriber\CalculateDiscountPriceSubscriber;
use App\EventSubscriber\CalculatePriceSubscriber;
use App\EventSubscriber\CalculatePromotionalCodeDiscountPriceSubscriber;
use App\EventSubscriber\CreateOrderSubscriber;
use PHPUnit\Framework\TestCase;

class EventSubscribersTest extends TestCase
{
    public function testHandledEvents(): void
    {
        $this->assertSame(['discount_price.calculate' => 'onCalculateDiscountPrice'], CalculateDiscountPriceSubscriber::getSubscribedEvents());
        $this->assertSame(['price.calculate' => 'onCalculatePrice'], CalculatePriceSubscriber::getSubscribedEvents());
        $this->assertSame(['promotional_code.discount_price.calculate' => 'onCalculateDiscountPrice'], CalculatePromotionalCodeDiscountPriceSubscriber::getSubscribedEvents());
        $this->assertSame(['order.create' => 'onOrderCreate'], CreateOrderSubscriber::getSubscribedEvents());
    }
}
