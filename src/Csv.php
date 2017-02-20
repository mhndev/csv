<?php
/*
 * This file is part of mhndev/csv.
 *
 * (c) Majid Abdolhosseini <majid8911303@gmail.com>
 */
namespace mhndev\csv;
/**
 * Class Csv
 * @package mhndev\csv
 * 
 * Csv Manipulation
 * This class helps you read , write , add lines , delete lines , ...from a csv file.
 */
class Csv
{

    protected $csvArray = [];

    /**
     * @param string $filename
     * @param string $delimiter
     * @return \Generator
     */
    public function csvToArrayUsingGenerator($filename, $delimiter = ',')
    {
        $handle = fopen($filename, "r");

        while (!feof($handle)) {
            yield fgetcsv($handle, 1000, $delimiter);
        }

        fclose($handle);
    }



    /**
     * @param string $filename
     * @param integer $id
     * @param string $delimiter
     */
    public function deleteOneLineById($filename , $id, $delimiter = ',')
    {
        $array = $this->csvToArray($filename, 0, null, $delimiter);
        unset($array[$id]);

        $this->arrayToCsv($array, $filename);
        $this->csvArray[$filename] = $array;
    }


    /**
     * @param $filename
     * @param $criteria
     * @param string $delimiter
     */
    public function deleteLineBy($filename , $criteria, $delimiter = ',')
    {
        $array = $this->csvToArray($filename, 0, null, $delimiter);

        $criteria_key = array_keys($criteria)[0];
        $criteria_value = $criteria[$criteria_key];


        foreach ($array as $key => $record){
            if($record[$criteria_key] == $criteria_value) {
                unset($array[$key]);
            }
        }

        $this->arrayToCsv($array, $filename);
        $this->csvArray[$filename] = $array;
    }


    /**
     * @param string $filename
     * @param integer $id
     * @param array $data
     * @param string $delimiter
     */
    public function updateOneLineById($filename, $id , array $data, $delimiter = ',')
    {
        $array = $this->csvToArray($filename, 0, null, $delimiter);

        $array[$id] = $data;

        $this->arrayToCsv($array, $filename);

        $this->csvArray[$filename] = $array;
    }

    /**
     * @param string $filename
     * @param array $criteria
     * @param array $data
     * @param string $delimiter
     */
    public function updateLineBy($filename , $criteria, array $data, $delimiter = ',')
    {
        $array = $this->csvToArray($filename, 0, null, $delimiter);

        $criteria_key = array_keys($criteria)[0];
        $criteria_value = $criteria[$criteria_key];

        foreach ($array as $key => $record){
            if($record[$criteria_key] == $criteria_value) {
                $array[$key] = $data;
            }
        }

        $this->csvArray[$filename] = $array;

        $this->arrayToCsv($array, $filename);
    }


    /**
     * @param string $filename
     * @param array $data
     */
    public function addLine($filename, array $data)
    {
        $array = $this->csvToArray($filename);

        $array[] = $data;
        $this->csvArray[$filename] = $array;

        $handle = fopen($filename, "a");
        fputcsv($handle, $data);
        fclose($handle);
    }

    /**
     *
     * Line Number starts from zero
     * @param string $filename
     * @param int $offset
     * @param null $count
     * @param string $delimiter
     * @param bool $ignoreFirstRowAsHeader
     * @return array|bool
     */
    public function csvToArray($filename, $offset = 0, $count = null, $delimiter = ',', $ignoreFirstRowAsHeader = false)
    {
        $result = [];

        if(!empty($this->csvArray[$filename]))
            $result = $this->csvArray[$filename];

        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = null;

        $data = array();


        if (($handle = fopen($filename, 'r')) !== FALSE) {

            if($ignoreFirstRowAsHeader){
                $header = fgetcsv($handle, 1000, $delimiter);

                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    $data[] = array_combine($header, $row);
                }

            }

            else{

                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    $data[] = $row;
                }
            }

            $result = $data;

            fclose($handle);

            $this->csvArray[$filename] = $data;
        }

        if(empty($count)){
            $count = count($result);
        }


        $result = array_slice($result, $offset, $count);


        return $result;
    }


    /**
     * @param mixed  $data
     * @param string $filename
     * @param string $delimiter
     */
    public function arrayToCsv($data, $filename, $delimiter = ',')
    {
        $fp = fopen($filename, 'w');

        foreach ($data as $fields) {
            if(!is_array($fields))
                throw new \InvalidArgumentException;

            fputcsv($fp, $fields, $delimiter);
        }

        $this->csvArray[$filename] = $data;

        fclose($fp);
    }

    /**
     * @param string $filename
     * @param array $criteria
     * @return bool|array
     */
    public function findOneBy($filename, array $criteria)
    {
        $array = $this->csvToArray($filename);

        $criteria_key = array_keys($criteria)[0];
        $criteria_value = $criteria[$criteria_key];

        foreach ($array as $key => $record){
            if($record[$criteria_key] == $criteria_value) {
                return $record;
            }
        }

        return false;
    }


    /**
     * @param string $filename
     * @param array $criteria
     * @return bool|array
     */
    public function findManyBy($filename, array $criteria)
    {
        $array = $this->csvToArray($filename);

        $criteria_key = array_keys($criteria)[0];
        $criteria_value = $criteria[$criteria_key];

        $result = [];

        foreach ($array as $key => $record){
            if($record[$criteria_key] == $criteria_value) {
                $result[] =  $record;
            }
        }

        return empty($result) ? false : $result;
    }

    /**
     *
     */
    public function deleteCache()
    {
        $this->csvArray = [];
    }

    /**
     * @return array
     */
    public function getCsvArray()
    {
        return $this->csvArray;
    }


}
