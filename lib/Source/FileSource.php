<?php
namespace WordSetCount\Source;
use WordSetCount\Source\Source;

include __dir__ . "/Source.php";

class FileSource implements Source {
    
    public function setFile($filename) {

        if(!file_exists($filename)) throw new Exception("File $filename doesnt exists.");

        $this->fileHandler = fopen($filename, "r");

    }

    public function next() {

         $sentence = trim(fgets($this->fileHandler));

         if($sentence) return $sentence;
         else return false;

    }

}