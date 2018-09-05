<?php

class CsvReader
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getContent()
    {
        $fileContent = [];
        $success = false;
        $message = 'An error occured!';

        $t0 = round(microtime(true) * 1000);

        $handle = fopen($this->path, "r");

        $key = 0;
        $header = [];

        if (file_exists($this->path)) {
            if ($handle) {
                while (($line = fgets($handle)) !== false) {

                    $explodedLine = explode(',', $line);

                    if ($key === 0) {
                        $header = $explodedLine;
                    } else {
                        $row = [];
                        foreach ($header as $key => $value) {
                            $row[trim($value)] = trim($explodedLine[$key]);
                        }
                        $fileContent[] = $row;
                    }
                    $key++;
                }
                fclose($handle);
                $success = true;
                $message = 'OK';
            }
        } else {
            $message = 'File not found';
        }

        $t1 = round(microtime(true) * 1000);

        $timeToRunMs = $t1 - $t0;

        $response = array(
            'success' => $success,
            'message' => $message,
            "timeMs" => $timeToRunMs,
            'data' => $fileContent,
        );

        return $response;
    }
}