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
    const LINE_NUMBER = 'line_number';
    const LINE_ID = 'line_id';

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
     * @param string $type
     * @param integer $id
     * @param string $delimiter
     */
    public function deleteOnLineBy($filename , $type , $id, $delimiter = ',')
    {
        $array = $this->csvToArray($filename, $delimiter);


        if($type == self::LINE_ID){
            foreach ($array as $key => $record){
                if($record[0] == $id)
                    unset($array[$key]);
            }
            $this->arrayToCsv($array, $filename);
            
        }elseif ($type == self::LINE_NUMBER){
            unset($array[$id]);

            $this->arrayToCsv($array, $filename);
        }else
            throw new \InvalidArgumentException;
    }


    /**
     * @param string $filename
     * @param string $type
     * @param integer $id
     * @param array $data
     * @param string $delimiter
     */
    public function updateLineBy($filename, $type, $id , array $data, $delimiter = ',')
    {
        $array = $this->csvToArray($filename, $delimiter);


        if($type == self::LINE_ID){
            foreach ($array as $key => $record){
                if($record[0] == $id)
                    $array[$key] = $data;
            }
            $this->arrayToCsv($array, $filename);

        }elseif ($type == self::LINE_NUMBER){
            $array[$id] = $data;

            $this->arrayToCsv($array, $filename);
        }else
            throw new \InvalidArgumentException;

        $this->csvArray[$filename] = $array;

    }


    /**
     * @param string $filename
     * @param array $data
     */
    public function addLine($filename, array $data)
    {
        $handle = fopen($filename, "a");
        fputcsv($handle, $data);
        fclose($handle);
    }

    /**
     *
     * Line Number starts from zero
     * @param string $filename
     * @param string $delimiter
     * @param bool $ignoreFirstRowAsHeader
     * @return array|bool
     */
    public function csvToArray($filename, $delimiter = ',', $ignoreFirstRowAsHeader = false)
    {
        if(!empty($this->csvArray[$filename]))
            return $this->csvArray[$filename];

        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            if($ignoreFirstRowAsHeader){
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
                {
                    if(!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
            }else{
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
                {
                    $data[] = $row;
                }
            }

            fclose($handle);
        }
        return $this->csvArray[$filename] = $data;
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

        fclose($fp);
    }

    
    
}
