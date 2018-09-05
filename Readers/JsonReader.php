<?php

class JsonReader
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

        if(file_exists($this->path)) {
            try {
                $fileContent = json_decode(file_get_contents($this->path), TRUE);
                $success = true;
                $message = 'OK';
            } catch (Exception $e) {
                $message = $e;
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