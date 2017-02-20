[![Build Status](https://travis-ci.org/mhndev/csv.svg?branch=1.3.1)](https://travis-ci.org/mhndev/csv)
<a href="https://packagist.org/packages/mhndev/csv"><img src="https://poser.pugx.org/mhndev/csv/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/mhndev/csv"><img src="https://poser.pugx.org/mhndev/csv/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/mhndev/csv"><img src="https://poser.pugx.org/mhndev/csv/license.svg" alt="License"></a>

## CSV

powerful and fully tested php library to work with csv files

### features :
#### convert an array to a csv file
#### convert a csv file to an array
#### convert a csv file to an array using php generators
#### delete a line from csv file by line number
#### delete multiple line from csv file by specific column value
#### update a line from a csv file by line number
#### update multiple line from a csv file by specific column value
#### find one line from a csv file by specific column value
#### find many line from a csv file by specific column value


## Sample Usage

```php

use mhndev\csv\Csv;

$csv = new Csv();
$sampleArray = [[1,2,3,4,5],[6,7,8,9,10]];
$filename ="/path/to/test.csv";
$csv->arrayToCsv($sampleArray, $filename);
$resultArrayIterator = $csv->csvToArrayUsingGenerator($filename);




$csv = new Csv();
$sampleArray = [[1,2,3,4,5],[6,7,8,9,10],[11,12,13,14,15]];
$filename ="/path/to/test.csv";
$csv->arrayToCsv($sampleArray, $filename);
$csv->deleteOneLineById($filename, 1);


$csv = new Csv();
$sampleArray = [[1,2,3,4,5],[6,7,8,9,10],[6,'hamid',8,9,'majid']];
$filename ="/path/to/test.csv";
$csv->arrayToCsv($sampleArray, $filename);
$csv->updateLineBy($filename, [2=>8] , [11,12,13,14,15]);

```
