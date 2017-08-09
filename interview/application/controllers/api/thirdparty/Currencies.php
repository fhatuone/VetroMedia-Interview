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

class Currencies extends REST_Controller {

    private $client;
    private $headers;
    private $status;

    function __construct() {
        parent::__construct();
        $this->load->model('Countries_model');
        $this->load->model('Currencies_model');

        
        $this->client = new GuzzleHttp\Client(['base_uri' => 'http://apilayer.net/api/live?access_key=2c4351546df148058b345f9f86efda64']);
    }

    public function currencies_get() {
        
        $response = $this->client->request('GET', '');
 
        $currencies = json_decode($response->getBody());

       
        foreach ($currencies->quotes as $key => $value) {
           
            $destination = str_replace($currencies->source, "", $key);

            $data['cntry_code'] = $destination;
            
            
            $results = $this->Countries_model->getCountryByCode($data);

            if (count($results['countries']) > 0) {
                if ( $results['countries'][0]->cntry_id > 0) {
                    $data['cntry_id'] = $results['countries'][0]->cntry_id;
                    $data['curr_amount'] = $value;
                    
                    
                    $latestCurrency = $this->Currencies_model->getCurrencyByCountryId($data);
                    
                    
                    if ( isset($latestCurrency['currencies'][0]->curr_id) ) {
                        
                        $data['curr_amount'] = (float) $data['curr_amount'];
                        $latestCurrency['currencies'][0]->curr_amount = (float) $latestCurrency['currencies'][0]->curr_amount;
                        
                        if ( $latestCurrency['currencies'][0]->curr_amount === $data['curr_amount'] ){                      
                            $data['curr_id'] = $latestCurrency['currencies'][0]->curr_id;
                            $this->Currencies_model->updateCurrency($data); 
                        } else {
                            $this->Currencies_model->insertCurrency($data);
                        }                       
                    } else {
                        $this->Currencies_model->insertCurrency($data); 
                    } 
                }
            }
        }

        redirect(base_url('/welcome'));
        
       
        $output['status'] = TRUE;
        $output['message'] = 'Successully updated currencies using 3rd party API';
        
        $this->response($output, REST_Controller::HTTP_OK);        
    }

}
