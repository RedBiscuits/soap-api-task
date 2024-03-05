<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\CreateCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Http\Services\CountrySoapService;
use App\Jobs\TriggerWebhookJob;
use App\Models\Country;
use App\Models\Log;
use Illuminate\Http\Request;
use SoapClient;
use SoapFault;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Country::byLanguage(request()->get('language'))->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCountryRequest $request)
    {
        // Utilizing functional programming to save memory
        // data is propagated over function stack calls at the same registers
        // less cache misses :D
        return $this->respondCreated(Country::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return $this->respondOk($country);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->validated());
        return $this->respondOk($country);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return $this->respondNoContent();
    }

    public function webhook()
    {
        Log::chunk(100, function ($logs) {
            $logs->each(function ($log) {
                TriggerWebhookJob::dispatch($log);
            });
        });
    }

    public function run_service()
    {
        $service = new CountrySoapService();
        $response = $service->run();

        return response($response, 200)->header('Content-Type', 'text/xml');
    }

    public function invokeSoapMethod(Request $request)
    {
        ini_set('max_execution_time', 3000);

        // Define the SOAP server URL
        $serverUrl = 'http://localhost:8000/api/countries/soap';

        // Define the SOAP request parameters
        $requestParams = array(
            'callback_url' => 'http://example.com/callback'
        );

        try {
            // Create a new SoapClient instance
            $client = new SoapClient(null, array(
                'location' => $serverUrl,
                'uri' => 'urn:countryService',
                'trace' => 1
            ));

            // Call the getCountries method with the parameters
            $response = $client->__soapCall('getCountries', array($requestParams));

            // Output the response
            var_dump($response);
        } catch (SoapFault $fault) {
            // Handle SOAP faults
            echo "Error: " . $fault->getMessage();
        }
    }
}
