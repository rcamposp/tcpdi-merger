<?php

    require_once("src/Merger.php");

    $m = new Merger(true);
    $m->addFromFile("example/A.pdf");
    $m->addFromFile("example/lorem.pdf");
    $m->addFromFile("example/PDF17.pdf");
    $m->addFromFile("example/PDF16.pdf");
    $m->merge();
    $m->save("example/merged-document.pdf");

?>
