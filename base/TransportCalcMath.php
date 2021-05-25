<?php

namespace TransportCalc;
/**
 * Class TransportCalcMath
 * @package TransportCalc
 */

class TransportCalcMath
{
	//private $cars;;
    private static $car;

    /**
     *
     * Calculate
     *
     * @param int $distance
     * @param int $weight
     * @param int $volume
     * @param null $options [persent *, min_price + ]
     * @return array return 3 parms (price, passingcargo, message)
     */
    public static function calculate($distance = 0, $weight = 0, $volume = 0, $options = null)  {

        if($distance < 0) return 0;

       $price = 0;

       $car = self::getCarPriceByParms($distance, $weight, $volume);
      
       $priceCalc = $distance * $car->price;


       $optionsSum = 0;
        if(isset($options))
        {
            foreach ($options as $value)
            {
                if( array_key_exists ( 'persent' , $value ))
                {
                    $optionsSum += $priceCalc * ($value["persent"] / 100); //Calc * ($value / 100);
                }

                if( array_key_exists ( 'min_price' , $value ))
                {
                    $optionsSum += $value["min_price"];
                }
            }

        }

       //$step = $options[0]["persent"] ;

       $price = [
       	"price" => self::roundPrice(round($priceCalc + $optionsSum, 0)),
       'passingcargo' => self::roundPrice(round(($priceCalc + $optionsSum) * 0.7, 0)),
       'message' => $car->msg
           ];

        unset($currentCar);

       return $price;

    }

    /**
     *
     * Get car by distance, weight, volume
     *
     * @param int $distance
     * @param int $weight
     * @param int $volume
     * @return mixed
     */

    public static function getCarPriceByParms($distance = 0, $weight = 0, $volume = 0)
    {
        $distance = round($distance);

        $currentCar = new BaseCustomData("tc_price");
        $conditionValue = [
            'distance' => $distance,
            'volume' => $volume,
            'weight' => $weight
        ];

        $car = $currentCar->get_by($conditionValue, '>=', 'distance, volume', 1);

        return $car[0];
    }

    public static function roundPrice($price){

        $newPrice = $price % 1000;
        $newPrice = $price - $newPrice;
        $newPrice += 1000;

        return $newPrice;

    }


}
