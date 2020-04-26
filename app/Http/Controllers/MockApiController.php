<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MockApiController extends Controller
{
    public function index(Request $request)
    {
    	$responseWeb = [
    		"country_code" => "GE",
		    "country_name" => "Abkhazia",
		    "region_name" => "Abkhazia",
		    "city_name" => "Abkhazia",
		    "latitude" => "00.00000",
		    "longitude" => "00.0000",
		    "zip_code" => "00000",
		    "time_zone" => "+00:00",
		    "isp" => "localhost",
		    "domain" => "offer.gogameplay.local",
		    "net_speed" => "DSL",
		    "idd_code" => "000",
		    "area_code" => "000",
		    "weather_station_code" => "UPXX0000",
		    "weather_station_name" => "Abkhazia",
		    "mcc" => "-",
		    "mnc" => "-",
		    "mobile_brand" => "-",
		    "elevation" => "181",
		    "usage_type" => "COM",
    		"credits_consumed" =>18
    	];

    	$responseMobile = [
		    "country_code" => "GE",
		    "country_name" => "Abkhazia",
		    "region_name" => "Abkhazia",
		    "city_name" => "Abkhazia",
		    "latitude" => "00.00000",
		    "longitude" => "00.00000",
		    "zip_code" => "00000",
		    "time_zone" => "+00:00",
		    "isp" => "localhost",
		    "domain" => "mobile.gogameplay.local",
		    "net_speed" => "DSL",
		    "idd_code" => "000",
		    "area_code" => "00000",
		    "weather_station_code" => "UPXX0000",
		    "weather_station_name" => "Abkhazia",
		    "mcc" => "255",
		    "mnc" => "01",
		    "mobile_brand" => "Vodafone",
		    "elevation" => "213",
		    "usage_type" => "MOB",
		    "credits_consumed" => 18
    	];

    	switch ($request->ip) {
    		case '1.1.1.1':
    			return response()->json($responseWeb, 200);
    			break;
    		case '1.1.1.2':
    			return response()->json($responseWeb, 200);
    			break;
    		case '1.1.1.3':
    			return response()->json($responseWeb, 200);
    			break;
    		case '1.1.1.4':
    			return response()->json($responseWeb, 200);
    			break;
    		case '1.1.1.5':
    			return response()->json($responseWeb, 200);
    			break;
    		case '1.1.1.6':
    			return response()->json($responseMobile, 200);
    			break;
    		default:
    			# code...
    			break;
    	}
    }
}
