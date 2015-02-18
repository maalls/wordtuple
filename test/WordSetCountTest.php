<?php

include __dir__ . "/../lib/WordSetCount.php";
include __dir__ . "/../lib/Source/FileSource.php";
include __dir__ . "/../lib/Storage/MongoDbStorage.php";

use WordSetCount\WordSetCount;
use WordSetCount\Storage\MongoDbStorage;
use WordSetCount\Source\FileSource;
class WordSetFinderTest extends PHPUnit_Framework_TestCase 
{
  


    public function testSanitize()
    {
     /*   
        $source = new CsvSource();
        $source->setFile(__dir__ . "/data/sample.csv");

        $count = new WordSetCount();   
        $count->setSource($source);
        $this->assertTrue(true, $count->count());
*/
        $count = new WordSetCount();

        $this->assertEquals("he s quite  happy   hope it will last  really ", $count->sanitize("He's quite \"happy\", hope it will last. Really."));
    }

    public function testTokenize() 
    {

        $count = new WordSetCount();

        $test = "He's quite happy, hope it will last. Really.";
        $expected = array("he", "s", "quite", "happy", "hope", "it", "will", "last", "really");

        $this->assertEquals($expected, $count->tokenize($test));

    }

    public function testGetSets()
    {

        $count = new WordSetCount();

        $test = "He's quite happy, hope it will last.";

        $expected = array(
            2 => array("he s", "s quite", "quite happy", "happy hope", "hope it", "it will", "will last"),
            3 => array("he s quite", "s quite happy", "quite happy hope", "happy hope it", "hope it will", "it will last"),
            4 => array("he s quite happy", "s quite happy hope", "quite happy hope it", "happy hope it will", "hope it will last")
        );

        $this->assertEquals($expected, $count->getSetOfSets($test));

    }

    public function testCountSentence() {

        $count = new WordSetCount();
        $m = new MongoClient();
        $db = $m->test_word_tuple;
        $db->tuple->remove();
        $collection = $db->tuple;
        $storage = new MongoDbStorage();
        $storage->setDbCollection($collection);
        $count->setStorage($storage);

        $count->countSentence("He's quite happy, hope it will last.");
        $count->countSentence("Regarding this weather, I hope it will last forever.");
        $count->countSentence("I think it will last 10 min.");

        $cursor = $collection->find(array("tuple" => "quite"));
        $this->assertEquals(0, $cursor->count());

        $cursor = $collection->find(array("tuple" => "quite happy"));
        $row = $cursor->getNext();
        $this->assertEquals($row["count"], 1);

        $cursor = $collection->find(array("tuple" => "hope it will last"));
        $row = $cursor->getNext();
        $this->assertEquals($row["count"], 2);

        $cursor = $collection->find(array("tuple" => "it will last"));
        $row = $cursor->getNext();
        $this->assertEquals($row["count"], 3);

    }

    public function testCount() {

        $count = new WordSetCount();
        $m = new MongoClient();
        $db = $m->test_word_tuple;
        $db->tuple->remove();
        $collection = $db->tuple;
        $storage = new MongoDbStorage();
        $storage->setDbCollection($collection);
        $count->setStorage($storage);

        $source = new FileSource();
        $source->setFile(__dir__ . "/data/sample.csv");

        $count->setSource($source);

        $count->count();

        $expected = array(
            "he likes this car" => 1,
            "likes this" => 2, 
            "eat something" => 4, 
            "should go eat something" => 3
            );


        foreach($expected as $sentence => $count) {
         

            $cursor = $collection->find(array("tuple" => $sentence));
            $row = $cursor->getNext();
            $this->assertEquals($count, $row["count"]);

        }

    }


    protected function createMongoDbStorage() {

        $m = new MongoClient();
        $db = $m->test_word_tuple;
        $db->tuple->remove();
        $collection = $db->tuple;
        $storage = new MongoDbStorage();
        $storage->setDbCollection($collection);

        return $storage;

    }

}