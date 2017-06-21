<?php 
/**
    @file randomgen.php
    @brief Just a collection of security helper functions stacked in a simple class. 
*/ 
/**
    @brief Just a collection of secure random generation stuff.  
*/ 

class RandomGen {

    private $alphabet;
    private $alphabetLength;


/**
    @brief Setting default values
*/
    public function __construct()
    { 
        // Setting default Alphabet
        $this->setAlphabet(
                implode(range('a', 'z'))
            . implode(range('A', 'Z'))
            . implode(range(0, 9))
        );   
    }

    

/**
    @brief setting a custom alphabet
    @param string $alphabet
*/
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
    }

    
    
/**
    @brief Generate a text token based on defined alphabet.   
    @param int $length
    @return string
*/
    public function TextToken($length)
    {
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $randomKey = $this->Integer(0, $this->alphabetLength);
            $token .= $this->alphabet[$randomKey];
        }

        return $token;
    }

/**
    @brief Get a more or less encryption save random integer.
    @param int $min
    @param int $max
    @return int
*/
    public function Integer($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            // Not so random...
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int) ($log / 8) + 1;

        // Length in bits.
        $bits = (int) $log + 1;

        // Set all lower bits to 1.
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex($this->random_pseudo_bytes_wrapper($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;

        } while ($rnd >= $range);

        return ($min + $rnd);
    }

    /**
        @brief Fallback function as sometimes 'openssl_random_pseudo_bytes' does not function_exists
    
        @param int $numBytes Number of bytes , same as in  'openssl_random_pseudo_bytes'
        @return string String of bytes.  
    */
    function random_pseudo_bytes_wrapper($numBytes) {
    if (function_exists('openssl_random_pseudo_bytes') || is_callable('openssl_random_pseudo_bytes') ) {
        //use openssl random pseudo bytes
        return openssl_random_pseudo_bytes($numBytes);
    } else if (function_exists('mt_rand')) {
        //fall back to less secure Mersenne-Twister mt_rand()
        $tmp='';
        for ($i=0; $i < $numBytes; $i++) {
            $tmp.=chr(mt_rand(0, 255));
        }
        return $tmp;
    } else {
        //fall back to least secure php rand()
        $tmp='';
        for ($i=0; $i < $numBytes; $i++) {
            $tmp.=chr(rand(0, 255));
        }
        return $tmp;
    }
}
    
}
