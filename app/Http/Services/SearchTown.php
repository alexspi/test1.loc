<?php

namespace App\Http\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class SearchTown
{

    /**
     * @param $name
     * @return mixed
     * @throws GuzzleException
     */
    public function getlist($name)
    {
        $url = "https://autocomplete.travelpayouts.com/places2?locale=ru&types[]=city&term=" . $name;
        $list = new GuzzleClient();
        $response = $list->request('GET', $url);

        return json_decode($response->getBody(), true);
    }


}