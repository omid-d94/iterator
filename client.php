<?php

use DesignPattern\Iterator\CsvIterator;

/**
 * The client code.
 */


$csv = new DesignPattern\Iterator\CsvIterator(__DIR__ . '/cats.csv');
foreach ($csv as $key => $row) {
    print_r($row);
}


