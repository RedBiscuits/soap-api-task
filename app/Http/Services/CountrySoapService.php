<?php

namespace App\Http\Services;

use App\Models\Country;
use App\Models\Log;
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
            [],
            ['countries' => 'xsd:string'],
            'urn:countryService',
            'urn:countryService#getCountries',
            'rpc',
            'encoded',
            'Returns a list of countries'
        );

        $this->server->register(
            'updateCountry',
            ['id' => 'xsd:int', 'callback_url' => 'xsd:string', 'name' => 'tns:ArrayOfString', 'description' => 'tns:ArrayOfString'],
            ['country' => 'tns:ArrayOfString'],
            'urn:countryService',
            'urn:countryService#updateCountry',
            'rpc',
            'encoded',
            'Updates a country and returns the updated country object'
        );

    }

    public function getCountries()
    {
        // Fetch countries from the database
        $countries = Country::all();

        // Return the list of countries in the desired format
        return $countries->toArray();
    }

    public function updateCountry(int $id , string $callback_url , array $name , array $description ){
        $country = Country::find($id);
        $country->fill([
            'name' => $name,
            'description' => $description
        ]);

        Log::create([
            'old_values' => $country->getDirty(),
            'new_values' => [
                'name' => $name,
                'description' => $description
            ],
            'callback_url' => $callback_url
        ]);

        $country->save();

        return $country;
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
