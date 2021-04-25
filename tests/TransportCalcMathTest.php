<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Transport_Calc\TransportCalcMath;

final class TransportCalcMathTest extends TestCase
{
	public function testOneCar(): void
    {
        $this->assertEquals(
            TransportCalcMath::getCarByWeightVolume(1.2, 9),
            0
        );
    }

    public function testTwoCar(): void
    {
        $this->assertEquals(
            TransportCalcMath::getCarByWeightVolume(3.4, 19),
            1
        );
    }

    public function testMiniCar(): void
    {
        $this->assertEquals(
            TransportCalcMath::getCarByWeightVolume(1, 150),
            0
        );
    }

   public function testDistanseDoublePrice(): void
   {
   	$this->assertEquals(
            TransportCalcMath::calcPriceByDispanseWeightVolume(196, 1.2, 9),
                8624
        );
   }

   public function testDistanse(): void
   {
   	$this->assertEquals(
            TransportCalcMath::calcPriceByDispanseWeightVolume(202, 1.2, 9),
                4444
        );
   }

   public function testDistanseDoublePriceRefrigerator(): void
   {
   	$this->assertEquals(
		TransportCalcMath::calcRefrigerator(
            TransportCalcMath::calcPriceByDispanseWeightVolume(196, 1.2, 9)
        ),

                9486.4
        );
   }

   public function testDistanseDoublePriceRefrigeratorCalculator(): void
   {
   	$this->assertEquals(
		TransportCalcMath::calcRefrigerator(
            TransportCalcMath::calculate(196, 1.2, 9, true)
        ),

                9486.4
        );
   }
   
   public function testDista(): void
   {
    $this->assertEquals(
    TransportCalcMath::calcRefrigerator(
            TransportCalcMath::calculate(10, 50, 35)
        ),

                9486.4
        );
   }


}