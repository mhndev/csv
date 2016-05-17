<?php

namespace mhndev\csv;

class Csv
{

    protected $csvArray = [];
    const LINE_NUMBER = 'line_number';
    const LINE_ID = 'line_id';

    /**
     * @param $filename
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
     * @param $filename
     * @param $type
     * @param $id
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
     * @param $filename
     * @param $type
     * @param $id
     * @param $data
     * @param string $delimiter
     */
    public function updateLineBy($filename, $type, $id , $data, $delimiter = ',')
    {
        $array = $this->csvToArrayUsingGenerator($filename, $delimiter);


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
    }


    /**
     * @param $filename
     * @param array $data
     */
    public function addLine($filename, array $data)
    {
        $handle = fopen($filename, "a");
        fputcsv($handle, $data);
        fclose($handle);
    }

    /**
     * @param string $filename
     * @param string $delimiter
     * @return array|bool
     */
    public function csvToArray($filename, $delimiter = ',')
    {
        if(!empty($this->csvArray[$filename]))
            return $this->csvArray[$filename];

        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
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
