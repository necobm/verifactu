<?php
namespace jdgOpenCode\verifactu;

class SoapClientDebugger extends \SoapClient {
    public $lastRequest;

    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
        $this->lastRequest = $request;
        return ''; // Prevent actual request execution
    }
}