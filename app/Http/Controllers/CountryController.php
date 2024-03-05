<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\CreateCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Jobs\TriggerWebhookJob;
use App\Models\Country;
use App\Models\Log;
use Illuminate\Http\Request;

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
}
