<?php

/**
 * 
 */

namespace Transport_Calc;


class TransportCalcMath
{
	//private $cars;;

	public static $cars = [
              ['weight' => 1.5, 'volume' => 10,	'price' => 22],
			  ['weight' =>  3.5,'volume' => 20, 'price' => 27],
			  ['weight' =>  5.5,'volume' =>	35,	'price' => 32],
			  ['weight' =>  9.5,'volume' => 50,	'price' => 55],
			  ['weight' =>  20,	'volume' => 82,	'price' => 65]];



    public static function calculate($distance, $weight, $volume, $refrigerator = false){

        $distance = round( strip_tags( $distance )) ;
        $weight   = strip_tags( $weight );
        $volume   = strip_tags( $volume );
        
    	
       if($distance < 0 || $weight < 0 || $volume < 0) { return 0; }

       $price = self::calcPriceByDispanseWeightVolume($distance, $weight, $volume);

       if($refrigerator) self::calcRefrigerator($price);

       return $price;

    }

    public static function getCarByWeightVolume($weight, $volume){

    	$countCars = count(self::$cars);

    	for ($i = 0; $i < $countCars; $i++) {
    		if(self::$cars[$i]['weight'] >= $weight &&
                self::$cars[$i]['volume'] >= $volume)
    	      { 
                return $i;
                break;
              }
    	}
        return -1;
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
