<?php

include __dir__ . "/../../lib/Storage/MongoDbStorage.php";

use WordSetCount\Storage\MongoDbStorage;

class MongoDbStorageTest extends PHPUnit_Framework_TestCase 
{

    public function testStore() {

        $m = new MongoClient();
        $db = $m->test_word_tuple;
        $db->tuple->remove();
        $collection = $db->tuple;

        $storage = new MongoDbStorage();
        $storage->setDbCollection($collection);

        $storage->store("hello toti");
        $storage->store("hello bibi");

        $cursor = $collection->find(array("tuple" => "hello toti"));
        $this->assertEquals(1, $cursor->count());
        $row = $cursor->getNext();
        $this->assertEquals($row["tuple"], "hello toti");
        $this->assertEquals($row["count"], "1");

        $cursor = $collection->find(array("tuple" => "hello bibi"));
        $this->assertEquals(1, $cursor->count());
        $row = $cursor->getNext();
        $this->assertEquals($row["tuple"], "hello bibi");
        $this->assertEquals($row["count"], "1");

        $storage->store("hello bibi");

        $cursor = $collection->find(array("tuple" => "hello toti"));
        $this->assertEquals(1, $cursor->count());
        $row = $cursor->getNext();
        $this->assertEquals($row["tuple"], "hello toti");
        $this->assertEquals($row["count"], "1");

        $cursor = $collection->find(array("tuple" => "hello bibi"));
        $this->assertEquals(1, $cursor->count());
        $row = $cursor->getNext();
        $this->assertEquals($row["tuple"], "hello bibi");
        $this->assertEquals($row["count"], "2");
        
        $db->tuple->remove();

    }


}