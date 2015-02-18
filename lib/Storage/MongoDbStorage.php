<?php
namespace WordSetCount\Storage;

use WordSetCount\Storage\Storage;

include __dir__ . "/Storage.php";

class MongoDbStorage {
    
    protected $collection;

    public function setDbCollection($collection) {

        $this->collection = $collection;

    }

    public function store($tuple) {

        $this->collection->update(

            array('tuple' => $tuple),
            array('$inc' => array("count" => 1)),
            array("upsert" => true)

        );

    }

}