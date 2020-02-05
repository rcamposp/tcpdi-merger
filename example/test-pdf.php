<?php

    require 'vendor/autoload.php';

    use shihjay2\tcpdi_merger\MyTCPDI;
    use shihjay2\tcpdi_merger\Merger;

    $m = new Merger(true);
    $m->addFromFile("example/A.pdf");
    $m->addFromFile("example/lorem.pdf");
    $m->addFromFile("example/PDF17.pdf");
    $m->addFromFile("example/PDF16.pdf");
    $m->merge();
    $m->save("example/merged-document.pdf");

?>
