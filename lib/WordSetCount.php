<?php
namespace WordSetCount;

class WordSetCount {
  
    protected $source;
    protected $storage;

    public function setStorage($storage) 
    {

        $this->storage = $storage;

    }

    public function setSource($source) 
    {

        $this->source = $source;

    }

    public function count() 
    {

        while($sentence = $this->source->next()) {

            $this->countSentence($sentence);

        }

    }

    public function countSentence($sentence) {

        $setOfSets = $this->getSetOfSets($sentence);

        foreach($setOfSets as $sets) {

            foreach($sets as $set) {

                $this->storage->store($set);

            }

        }



    }

    public function getSetOfSets($phrase) 
    {

        $sets = array(
            2 => array(),
            3 => array(),
            4 => array()
        );

        $tokens = $this->tokenize($phrase);
        $count = count($tokens);
  
        for($i = 0; $i < $count - 1; $i++) {

            $set = $tokens[$i] . " " . $tokens[$i+1];
            $sets[2][] = $set;
            if($i + 2 < $count) {

                $set = $set . " " . $tokens[$i + 2];
                $sets[3][] = $set;


            }
            if($i + 3 < $count) {

                $set = $set . " " . $tokens[$i + 3];
                $sets[4][] = $set;

            }

        }

        return $sets;

    }

    public function tokenize($phrase) 
    {

        $phrase = $this->sanitize($phrase);
        $phrase = trim(preg_replace("/  /", " ", $phrase), " ");
        
        $rsp = explode(" ", $phrase);
        
        return $rsp;
    }

    public function sanitize($phrase) 
    {

        return strtolower(preg_replace("/['\.,\"]/", " ", $phrase));

    }

}