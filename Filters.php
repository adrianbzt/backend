<?php

class Filters {

private $supportedFilters;
private $receivedFilters;
private $toApplyFilters;

    function __construct($receivedFilters) {
        $this->supportedFilters = [
            "limit" => array(
                "type" => "general" //general: applied on the result
            ), 
            "offset" => array(
                "type" => "general"
            ), 
            "name"=> array(
                "type" => "custom" //general: applied on the data
            ), 
            "email"=> array(
                "type" => "custom"
            ), 
            "gender"=> array(
                "type" => "custom"
            )];
        $this->receivedFilters = $receivedFilters;
        $this->setFilters();
    }

    private function setFilters() {
        foreach($this->receivedFilters as $filterName => $filterValue) {
            if(!in_array($filterName, ['ver','api'])) {
                if(isset($this->supportedFilters[$filterName])) {
                    $type = $this->supportedFilters[$filterName]['type'];
                    $this->toApplyFilters[$type][$filterName] = $filterValue;
                } else {
                    $type = 'custom';
                    $this->toApplyFilters[$type][$filterName] = $filterValue;
                }
            }

        }
    }

    private function setFiltersOld() {
        foreach($this->supportedFilters as $filterName => $filterSpecs) {
            if(isset($this->receivedFilters[$filterName])) {
                $type = $filterSpecs['type'];
                $this->toApplyFilters[$type][$filterName] = $this->receivedFilters[$filterName];
            } else {
                $type = 'custom';
                $this->toApplyFilters[$type][$filterName] = false;
            }
        }
    }

    public function getFilters() {
        return $this->toApplyFilters;
    }
}