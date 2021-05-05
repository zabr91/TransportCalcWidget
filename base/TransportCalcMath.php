<?php

namespace TransportCalc;

class TransportCalcMath
{
	//private $cars;;
    private static $car;

    public static function calculate($distance = 0, $weight = 0, $volume = 0, $options = null)  {

       $price = 0;

       $distance = round($distance);

       $currentCar = new BaseCustomData("tc_price");
       $conditionValue = [
        'distance' => $distance,
        'volume' => $volume,
        'weight' => $weight
       ];
       $car = $currentCar->get_by($conditionValue, '>=', 'distance, price', 1);

      
       $priceCalc = $distance * $car[0]->price;

       /*if($weight > 90) {
        $priceCalc = $priceCalc * 2;
       }*/

       $optionsSum = 0;

       foreach ($options as $value) {
         if( array_key_exists ( 'persent' , $value )) {

          $optionsSum += $priceCalc * ($value["persent"] / 100); //Calc * ($value / 100);
         }
       
        if( array_key_exists ( 'min_price' , $value )) {

          $optionsSum += $value["min_price"];
         }
       }
       //$step = $options[0]["persent"] ;

       $price = ["price" => $priceCalc + $optionsSum,
       'message' => $car[0]->msg
           ];

        unset($currentCar);

       return $price;

    }	

}
