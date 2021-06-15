<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use TransportCalc\TransportCalcMath;

final class TransportCalcMathTest extends TestCase
{
	public function testSelectFirstCar(): void
    {
        $this->assertEquals(
            TransportCalcMath::getCarPriceByParms(300, 0.9,9)->price,
            22
        );
    }


    public function testSelectFirstCar2(): void
    {
        $this->assertEquals(
            TransportCalcMath::getCarPriceByParms(300, 1,10)->price,
            22
        );
    }

    public function testSelectTridCar(): void
    {
        $this->assertEquals(
            TransportCalcMath::getCarPriceByParms(300, 3,20)->price,
            32
        );
    }

    public function testSelectMaxCar(): void
    {
        $this->assertEquals(
            TransportCalcMath::getCarPriceByParms(300, 20,120)->price,
            90
        );
    }

    public function testSelectCarByTablble(): void
    {
        // МСК - СПБ (780км) 4 тонны 63 куба ?
        $this->assertEquals(
            TransportCalcMath::getCarPriceByParms(780, 4,63)->price,
            75
        );
    }


    public function testMSKtoSPB(): void
    {
        // МСК - СПБ (780км) 4 тонны 63 куба ?
        $this->assertEquals(
            TransportCalcMath::calculate(780, 4,63)["price"],
           59000// 58500
        );
    }


    public function testRoundPrice(): void
    {
        $this->assertEquals(
            TransportCalcMath::roundPrice(1999),
            2000
        );
    }

    public function testExtremum()
    {
        $this->assertEquals(
            TransportCalcMath::calculate(780, 21,121)["price"],
            0// 58500
        );
    }

}