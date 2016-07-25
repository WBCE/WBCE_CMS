<?php 
/**
@file 
@brief Contains the new Password class that encrypt(hash) checks and generates passwords.  
*/
/**
@brief New Password class that encrypt checks and generates passwords.
    This is a static class to be easyly available everywhere. 
*/
class Password {

/**
Uses the new password_hash function (PHP 5.5) if available . 
Otherwise it uses the old md5 variant. So if you want secure passwords get PHP 5.5

@param string $sPassword The actual password to encrypt 
@return string $sHash The encrypted(hashed) Password 
*/
public static function Encrypt ($sPassword){


    return $sHash
}


/**
Checks if a Password Hash  Matches the password String

@return returns True|False|"needs rehash" 
*/
public static function Check ($Password, $sHash){


}

/**
    Fetches USer PWhash from DB and compares to recent given password 
    does Automatic rehash if new hash functions available.  
    
    Rehash can be forbidden by WB_FORBID_REHASH_PW to true. 
    
*/
public static function CheckUser ($sPassword, $sUserId){


}


/**
Generates Human readable/memorizable  Password 
at least they are far better than sl7jg.ior3ei:o 
Examples:(WB_PW_BLOCKS=2)  
ifog3-Enez5
oguz2+xuna7
woki9+hehi2
Apan9+Adad3
Sebi3-Godi6
ihun1+dunu3

Yes i know it is less secure but its still far better than most 
passwords people would choose for themselfes.  
If you want more secure ones simmply add an additional block.  
faxu8-ebip3+Eded8  

*/
public static function Generate() {

        //validate Input
        if (defined('WB_PW_BLOCKS')) $iBlocks=WB_PW_BLOCKS;
        else $iBlocks=2
        $iBlocks=(int)$iBlocks;
        if ($iBlocks < 1) $iBlocks=1;

        $sPassword="";

        //Set defaults
        if (defined('WB_PW_DIGITS')) $sDigits=WB_PW_DIGITS;
        else $sDigits='123456789';

        if (defined('WB_PW_CONSONANTS')) $sConsonants=WB_PW_CONSONANTS;
        else $sConsonants = 'bdfghkmnprstvwxzBDFGHKMNPRSTVWXZ';

        if (defined('WB_PW_VOWELS')) $sVowels=WB_PW_VOWELS;
        else $sVowels='aeiouAEIOU';

        if (defined('WB_PW_SEPARATORS')) $sSeparators=WB_PW_SEPARATORS;
        else $sSeparators = '+-';

        //Generate Arrays
        $aDigits = str_split($sDigits, 1);
        $aConsonants = str_split($sConsonants, 1);
        $aVowels = str_split($sVowels, 1);
        $aSeparators = str_split($sSeparators, 1);

        // create PW blocks
        for($i=0; $i<$iBlocks; $i++) {

        $aBlock = array();

            if (rand (0,1) !=0) {
                $aBlock[] = $aConsonants[array_rand($aConsonants)] . strtolower( $aVowels[array_rand($aVowels)]. $aConsonants[array_rand($aConsonants)] . $aVowels[array_rand($aVowels)]);
            } else {
                $aBlock[] = $aVowels[array_rand($aVowels)] . strtolower( $aConsonants[array_rand($aConsonants)]. $aVowels[array_rand($aVowels)] . $aConsonants[array_rand($aConsonants)]);
            }

            $aBlock[] = $aDigits[array_rand($aDigits)];

            //shuffle($aBlock);
            $aBlock[] = $aSeparators[array_rand($aSeparators)];
            $sPassword.= implode('', $aBlock);
        }


        $sPassword=substr($sPassword,0,-1);

        return $sPassword;
    }

/**
    Generates tempoary password and stores it in Temp password fiels of the user 

*/
public static function GenerateTemp($iUserId){


}

//////////////////////////
///////  DB functions seperated for easy conversion to Readbean or something else 
/////////////////////////////

private static function FetchHash($iUserId){
    global $database;
    

}
private static function StoreHash($UserId, $sHash){
    global $database;
    
    
}
private static function StoreTemp($UserId, $sHash){
    global $database;

    
} 
