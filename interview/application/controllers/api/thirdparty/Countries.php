<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';
require APPPATH . '/libraries/REST_Controller.php';

use Guzzle\Http\Client;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use GuzzleHttp\Stream\StreamInterface;
use GuzzleHttp\ClientInterface;
use Restserver\Libraries\REST_Controller;

class Countries extends REST_Controller {

    private $client;
    private $headers;
    private $status;

    function __construct() {
        parent::__construct();
        $this->load->model('Countries_model');

        $this->client = new GuzzleHttp\Client(['base_uri' => 'http://apilayer.net/api/list?access_key=5edd8e9d7f9c2c026a4c59bc18ff44d8']);
    }

    public function countries_get() {
        $response = $this->client->request('GET', '');
        $countries = json_decode($response->getBody());


        $countries = (array) $countries;

        $countries['currencies'] = (array) $countries['currencies'];

        foreach ($countries['currencies'] as $key => $value) {
            $data['cntry_code'] = $key;
            $data['cntry_name'] = $value;

            $results = $this->Countries_model->getCountryByCode($data);

            if (count($results['countries']) == 0) {
                $this->Countries_model->insertCountry($data);
            }
        }

        $output['status'] = TRUE;
        $output['message'] = 'Successully updated countries using 3rd party API';

        $this->response($output, REST_Controller::HTTP_OK);
    }

}
