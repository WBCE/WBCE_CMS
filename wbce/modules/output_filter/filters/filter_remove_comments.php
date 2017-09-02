<?php
class filterRemoveComments {
    /*Not used yet*/
    //public function __construct() {}

    /**
        @brief Eine Erweiterung für Preg Match die auch negative Auschlüsse erlaubt

        @param $regex der Reguläre ausdruck 
        @param $test Der zu durchsuchende Text 
        @param $neg_bracket      welche Klammer(Teil) des Ausdruckes als Negativer Auschluss funktionieren soll 
        @param $result_bracket   Welche Klammer(Teil) des Ausdruckes als Ergebniss zurück geliefert werden soll 

        @code

        $text="Jetzt ist hier noch mehr Text Set Unt auch hier kommt noch was ";
        $regex= "/hier .*Set.*?(Value|ualue|Hello)?.*(auch).*noch/i";

        $result=ext_match ($regex, $text, 1, 0 );
        var_dump($result);
        echo "\n\n";
        @endcode
    */
    protected function Match ($regex, $text, $neg_bracket, $result_bracket ){
        
        preg_match ($regex, $text, $matches);
        
        //var_dump($matches);
        
        if (!empty($matches[$neg_bracket])) return false;
        if (empty($matches[$result_bracket])) return false;
    
        return $matches[$result_bracket];
    }

    
    
    
    /**
        @brief Replacement for preg_match_all but allows to select a negative value
    
        @param $regex The actual regex 
        @param $test The Haystack
        @param $neg_regex The negative regex , this one may not be in the result  
        @param $neg_bracket The bracket (number) which the neg-regex should be applied 
        
        @return array The full returnarray of preg_match_all but whithout the ones 
        matched on the negative expression
    */
    protected function MatchAll ($regex="", $text="",  $neg_regex="", $neg_bracket=0){
        
        if (
            empty($regex) OR
            empty( $text) OR
            empty($neg_regex)
        ) return false;
        
        preg_match_all ($regex, $text, $matches);
        
        //var_dump($matches);
        
        foreach ($matches[0] as $key=>$match){	
            if (preg_match($neg_regex, $matches[$neg_bracket][$key])) {
                //echo "unset\n"; 
                foreach ($matches as $mkey => $mrow) 
		    {unset ($matches[$mkey][$key]);}
            }
        }

        if (empty($matches)) return false;
    
        return $matches;
    }
    
    public function RunFilter($content){
    
        $resultat=$this->MatchAll(
            $regex="/<!--(.*)-->/sU", 
            $content, 
            $neg_regex="/(<script|\[if )/si", 
            $neg_bracket=1
        );
    
        foreach ($resultat[0] as $search) {
            $content=str_replace($search, "", $content );
        }
        
        return $content;
    }
}


