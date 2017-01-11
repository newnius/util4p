<?php
  class Random
  {
    public static function randomInt($min, $max)
    {
      $range = $max - $min;
      if ($range < 1) return $min; // not so random...
      $log = ceil(log($range, 2));
      $bytes = (int) ($log / 8) + 1; // length in bytes
      $bits = (int) $log + 1; // length in bits
      $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
      do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
      } while ($rnd >= $range);
      return $min + $rnd;
    }
  
		/*
		 * generate random string of length $length
		 * level: 1-only numbers, 2-plus letters(upper and lower), 3- plus special chars
		 */
    public static function randomString($length, $level=2)
    {
      $token = '';
      $codeAlphabet = '0123456789';
      if($level > 1){
				$codeAlphabet.= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      	$codeAlphabet.= 'abcdefghijklmnopqrstuvwxyz';
			}
			if($level > 2)
				$codeAlphabet.= '+-*/?!%`~@#^&(){}';

      $max = strlen($codeAlphabet) - 1;
      for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[Random::randomInt(0, $max)];
      }
      return $token;
    }
  }

?>
