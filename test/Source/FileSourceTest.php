<?php

include __dir__ . "/../../lib/Source/FileSource.php";

use WordSetCount\Source\FileSource;

class FileSourceTest extends PHPUnit_Framework_TestCase 
{

    public function testNext() {

        $source = new FileSource();
        $source->setFile(__dir__ . "/../data/sample.csv");

        $sentences = array();

        while($sentence = $source->next()) {

            $sentences[] = $sentence;

        }

        $this->assertEquals(8, count($sentences));

        $this->assertEquals("He likes this car.", $sentences[0]);
        $this->assertEquals('"You should go eat something, you look hungry. "', $sentences[4]);


    }

}