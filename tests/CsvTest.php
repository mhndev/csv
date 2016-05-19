<?php

/*
 * This file is part of mhndev/csv.
 *
 * (c) Majid Abdolhosseini <majid8911303@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */


namespace mhndev\csv;

use org\bovigo\vfs\vfsStream;

class CsvTest extends \PHPUnit_Framework_TestCase
{


    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup("rootDirectory");
    }


    public function testCsvToArrayUsingGenerator()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10]];

        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);

        $resultArrayIterator = $csv->csvToArrayUsingGenerator($filename);

        $resultArray = [];

        foreach ($resultArrayIterator as $array){
            $resultArray[] = $array;
        }

        $this->assertTrue($sampleArray == $resultArray);
    }



    public function testDeleteOneLineById()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10],[11,12,13,14,15]];

        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);
        $csv->deleteOneLineById($filename, 1);

        $resultArray = $csv->csvToArray($filename);
        unset($sampleArray[1]);

        $this->assertTrue($sampleArray == $resultArray);
    }


    public function testDeleteLineBy()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10]];
        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);
        $csv->deleteLineBy($filename, [2=>8]);

        $resultArray = $csv->csvToArray($filename);
        unset($sampleArray[1]);

        $this->assertTrue($sampleArray == $resultArray);
    }



    public function testUpdateOneLineById()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10]];

        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);
        $csv->updateOneLineById($filename, 1 , [11,12,13,14,15]);

        $resultArray = $csv->csvToArray($filename);
        $expectedArray = [[1,2,3,4,5],[11,12,13,14,15]];

        $this->assertTrue($expectedArray == $resultArray);
    }


    public function testUpdateLineBy()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10],[6,'hamid',8,9,'majid']];

        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);
        $csv->updateLineBy($filename, [2=>8] , [11,12,13,14,15]);

        $resultArray = $csv->csvToArray($filename);
        $expectedArray = [[1,2,3,4,5],[11,12,13,14,15],[11,12,13,14,15]];

        $this->assertTrue($expectedArray == $resultArray);
    }


    public function testAddLine()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10]];

        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);
        $csv->addLine($filename, [11,12,13,14,15]);

        $resultArray = $csv->csvToArray($filename);


        $expectedArray = [[1,2,3,4,5],[6,7,8,9,10],[11,12,13,14,15]];

        $this->assertTrue($expectedArray == $resultArray);
    }


    public function testCsvToArray()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10]];

        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);

        $resultArray = $csv->csvToArray($filename);

        $this->assertTrue($sampleArray == $resultArray);
    }


    public function testArrayToCsv()
    {
        $csv = new Csv();
        $sampleArray = [[1,2,3,4,5],[6,7,8,9,10]];

        $filename = vfsStream::url("rootDirectory").DIRECTORY_SEPARATOR."test.csv";

        $csv->arrayToCsv($sampleArray, $filename);

        $resultArray = $csv->csvToArray($filename);

        $this->assertTrue($sampleArray == $resultArray);
    }


}
