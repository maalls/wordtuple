<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);




include __dir__ . "/../lib/WordSetCount.php";
include __dir__ . "/../lib/Source/FileSource.php";
include __dir__ . "/../lib/Storage/MongoDbStorage.php";

use WordSetCount\WordSetCount;
use WordSetCount\Source\FileSource;
use WordSetCount\Storage\MongoDbStorage;

$storage = new MongoDbStorage();
$m = new MongoClient();
$db = $m->demo_wordtuple;
$collection = $db->tuple;

if(isset($_POST["clear"])) {

    $collection->remove();
    header("location: /");

}


if($_FILES) {

    
    $collection->remove();
    $storage->setDbCollection($collection);

    $source = new FileSource();

    $source->setFile($_FILES["file"]["tmp_name"]);

    $count = new WordSetCount();
    $count->setSource($source);
    $count->setStorage($storage);

    $count->count();

    header("location: /");

    

}

$cursor = $collection->find(array('$query' => array(), '$orderby' => array("count" => -1)));

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>WordTuple Demo</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container ">



        <div class="row text-center">
            <div class="col-lg-6 col-md-6 col-md-offset-3 col-ml-offset-3">
                <h1>Word Tuples Counter Demo</h1>
                <form method="post" enctype="multipart/form-data" class="form-inline center-block">
                    <div class="panel panel-default">
                            <div class="panel-heading">File upload (<a href="/sample.txt" download>click here to download a file sample</a>)</div>
                            <div class="panel-body">
                                <div class="form-group">
                             
                                    <input name="file" value="Upload a file" type="file" />
                                    <p class="help-block">The file should include 1 sentence per line.</p>

                                </div>

                                <button type="submit" class="btn btn-default" />Upload</button>
                            </div>

                    </div>
                </form>
            </div>
        </div>

        <?php if($cursor) : ?>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-md-offset-3 col-ml-offset-3">
                    <form style="display:inline;" method="post">
                        <div class="panel panel-default">
                            <div class="panel-heading">Database content <input type="submit" class="btn btn-default btn-sm" name="clear" value="Clear" onclick="return alert('sure ?')" /></div>
                            <div class="panel-body">
                            
                                <table class="table table-striped">
                                    
                                    <?php while($row = $cursor->getNext()) { ?>
                                        <?php if($row["count"] > 1) : ?>
                                            <tr>
                                                <td><?php echo $row["tuple"] ?></td><td><?php echo $row["count"] ?></td>
                                            </tr>
                                        <?php endif ?>
                                    <?php } ?>
                                    
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        <?php endif ?>

    
</div>
</body>
</html>