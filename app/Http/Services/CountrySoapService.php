<?php

namespace App\Http\Services;

use App\Models\Country;
use Exception;
use nusoap_server;

class CountrySoapService
{
    protected $server;

    public function __construct()
    {
        $this->server = new nusoap_server();

        $this->server->configureWSDL('CountryService', 'http://localhost:8000/country.wsdl');
        $this->server->wsdl->schemaTargetNamespace = 'urn:countryService';

        $this->server->register(
            'getCountries',
            ['callback_url' => 'xsd:string'],
            ['countries' => 'xsd:string'],
            'urn:countryService',
            'urn:countryService#getCountries',
            'rpc',
            'encoded',
            'Returns a list of countries'
        );
    }

    public function getCountries($callback_url = '')
    {
        // Fetch countries from the database
        $countries = Country::all();

        // Return the list of countries in the desired format
        return $countries->toArray();
    }

    public function run()
    {
        try {
            $this->server->service(file_get_contents("php://input"));
        } catch (Exception $e) {
            return $this->server->fault('Server Error', $e->getMessage());
        }
    }


}
