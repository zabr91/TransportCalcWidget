<?php

/**
 * 
 */

namespace Transport_Calc;


class TransportCalcMath
{
	//private $cars;;
    private static $cars;

    public static function calculate($distance, $weight, $volume, $refrigerator = false){

        self::$cars = new BaseCustomData('tc_price');

        $distance = round( strip_tags( $distance )) ;
        $weight   = strip_tags( $weight );
        $volume   = strip_tags( $volume );
        
    	
       if($distance < 0 || $weight < 0 || $volume < 0) { return 0; }

       $price = self::calcPriceByDispanseWeightVolume($distance, $weight, $volume);

       if($refrigerator) self::calcRefrigerator($price);

       return $price;

    }

    public static function getCarByWeightVolume($weight, $volume){

        echo "GET CAR";

        wp_die();

    	/*$countCars = count(self::$cars);

    	for ($i = 0; $i < $countCars; $i++) {
    		if(self::$cars[$i]['weight'] >= $weight &&
                self::$cars[$i]['volume'] >= $volume)
    	      { 
                return $i;
                break;
              }
    	}
        return -1;*/
    }

    public static function calcPriceByDispanseWeightVolume($distance,$weight, $volume){

        $carNum = self::getCarByWeightVolume($weight, $volume);
        $result = self::$cars[$carNum]['price'] * $distance;
        if($distance < 200) $result = $result * 2;

        return $result;
    }

    public static function calcRefrigerator($price){
        return $price + ($price * 0.1); 
    }
	

}
