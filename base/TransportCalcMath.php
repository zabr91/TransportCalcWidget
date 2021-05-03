<?php

namespace TransportCalc;

class TransportCalcMath
{
	//private $cars;;
    private static $car;

    public static function calculate($distance = 0, $weight = 0, $volume = 0, $parms = null)  {

       $price = 0;

       $currentCar = new BaseCustomData("tc_price");
       $conditionValue = [
        'distance' => $distance,
        'weight' => $weight,
        'volume' => $volume
       ];
       $car = $currentCar->get_by($conditionValue, '>', 'distance');

      
       $priceCalc = $distance * $car[0]->price;

       if($weight > 90) {
        $priceCalc = $priceCalc * 2;
       }


       foreach ($parms as $parm) {
         # code...
       }

       $price = ["price" =>$distance * $car[0]->price,
       'msg' => $car[0]->msg];

       return $price;

    }	

}
