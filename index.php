<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include dirname(__FILE__) . "/Readers/CsvReader.php";
include dirname(__FILE__) . "/Readers/JsonReader.php";
include dirname(__FILE__) . "/Filters.php";

class UsersApi
{

    private $content;
    private $result;
    private $settings;

    function __construct() {
        $this->settings = array(
            // 'users' or 'user'; default is 'users'
            "api" => isset($_GET['api']) ? $_GET['api'] : 'users',
            // version is used to determine the source; default is 1 (csv)
            "ver" => isset($_GET['ver']) ? $_GET['ver'] : 1,
        );

        // filters that can be applied to the result
        $filters = new Filters($_GET);
        $this->settings['filters'] = $filters->getFilters();

        // echo '<pre>';
        // print_r($this->settings);
        // die; 

    }

    public function run()
    {

        $this->content = $this->getContent();

        $this->result = $this->applyFilters();

        echo json_encode($this->result, JSON_PRETTY_PRINT);
    }


    private function getContent()
    {
        switch ($this->settings['ver']) {
            case 1:
                $csvReader = new CsvReader((__DIR__) . '/sources/testtakers.csv');
                return $csvReader->getContent();
                break;
            case 2:
                $jsonReader = new JsonReader((__DIR__) . '/sources/testtakers.json');
                return $jsonReader->getContent();
                break;
            default:
                break;
        }

    }

    private function applyFilters() {

        $this->applyCustomFilters();
        $this->applyGeneralFilters();


                echo '<pre>';
                print_r($this->content['data']);
                die; 
 
    }

    private function applyCustomFilters() {
        $filteredValues = [];

        if(!empty($this->settings['filters']['custom']) && !empty($this->content['data'])) {
            foreach($this->content['data'] as $line) {


                foreach($this->settings['filters']['custom'] as $filterName => $filterValue) {
                    $expected[$filterName] = $filterValue;
                    $found[$filterName] = $line[$filterName];
                }

                if(empty(array_diff($found, $expected))) {
                    $filteredValues[] = $line;
                }
               

                ;
            }
            $this->content['data'] = $filteredValues;
        }

        
    }

    private function applyGeneralFilters() {
        if(!empty($this->settings['filters']['general']) && !empty($this->content['data'])) {

            if(isset($this->settings['filters']['general']['offset'])) {
                $offset = $this->settings['filters']['general']['offset'];
            } else {
                $offset = 0;
            }

            if(isset($this->settings['filters']['general']['limit'])) {
                $limit = $this->settings['filters']['general']['limit'];
            } else {
                $limit = NULL;
            }

            $this->content['data'] = array_slice($this->content['data'], $offset, $limit,false);
        }
    }

}

$user = new UsersApi();
$user->run();