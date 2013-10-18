<?php
class My_Filter_OptionalDecimal implements Zend_Filter_Interface
{
	
	
	//public function __construct($options = null){

		
	//}
//        public function filter($value)
//        {
//            // perform some transformation upon $value to arrive on $valueFiltered
//     
//            return $valueFiltered;
//        }
/**
 * Formats decimal to a whole number or with 2 decimals; 
 */
public function filter($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
 if (!is_numeric($number)){
 	return $number;
 }	
 //return 'ok';
 //new Zend_Locale_Format()	//return 'ok';
  //if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $newNumber = ($cents == 2 ? '0.00' : '0'); // output zero
      return $newNumber;
      //echo 'a';
    } 
    $locale = Zend_Registry::get('Zend_Locale'); 
    //else { // value
      if (floor($number) == $number) { // whole number
      	$precision = $cents == 2 ? 2 : 0;
        $newNumber = $number; //number_format($number, ($cents == 2 ? 2 : 0)); // format
        //echo 'b';
      } else { // cents
      	$precision = $cents == 0 ? 0 : 2;
        $newNumber = round($number, 2); // number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
        //echo 'c';
      } // integer or decimal
    //} // value
    	$newNumber = str_replace(".",",",$newNumber); //nl_BE locale, @todo:transform to specified locale  
      return Zend_Locale_Format::getNumber($newNumber,
                                        array('locale' => $locale,
                                              'precision' => $precision)
                                       );
    //return $newNumber;
}
 // } // numeric
 // return $number;
//} // formatMoney        
        /*
// formats money to a whole number or with 2 decimals; includes a dollar sign in front
function formatMoney($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0.00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
      } else { // cents
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
      } // integer or decimal
    } // value
    return '$'.$money;
  } // numeric
} // formatMoney  
*/      
        
        
        
    }