<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Delivered;
use App\Document;
use App\Employee;
use App\Serviceschedule;
use Spatie\Permission\Models\Role;
use App\Trainingschedules;
use App\User;
Use Auth;

class AdtestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $authorize_url = "https://login.microsoftonline.com/1a53e6b6-7562-4b9f-83d0-e71a8891667e/saml2";
    private $token_url = " https://sts.windows.net/1a53e6b6-7562-4b9f-83d0-e71a8891667e/";
    private $callback_uri = "https://g3gpa.localhost/adtest/callback";
    private $test_api_url = "<<your API>>";
    private $client_id = "<<client_id>>";
    private $client_secret = "<<client_secret>>";
    
    public function __construct()
    {
        
        //	callback URL specified when the application was defined--has to match what the application says
        
        
        //	client (application) credentials - located at apim.byu.edu
        
        
        
        if (isset($_POST["authorization_code"])) {
            //	what to do if there's an authorization code
            $access_token = $this->getAccessToken($_POST["authorization_code"]);
            $resource = $this->getResource($access_token);
            echo $resource;
        } elseif (isset($_GET["code"])) {
            $access_token = $this->getAccessToken($_GET["code"]);
            $resource = $this->getResource($access_token);
            echo $resource;
        } else {
            //	what to do if there's no authorization code
            $this->getAuthorizationCode();
        }
    }

    /**
     * Show the application dashboard.
     * https://developer.byu.edu/docs/consume-api/use-api/oauth-20/oauth-20-php-sample-code
     * https://www.youtube.com/watch?v=IbJt7tr8kL0
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index()
    {
        echo "login";
    }
    public function callback()
    {
        echo "callback";
    }
    public function logoff()
    {
        echo "logoff";
    }






    private function getAuthorizationCode() {
        //die('getAuthorizationCode');
        //global $authorize_url, $client_id, $callback_uri;

        //$this->authorize_url = "https://api.byu.edu/authorize";
        //$token_url = "https://api.byu.edu/token";
    
        $authorization_redirect_url = $this->authorize_url . "?response_type=code&client_id=" . $this->client_id . "&redirect_uri=" . $this->callback_uri . "&scope=openid";
    
        header("Location: " . $authorization_redirect_url);
        die();
        //	if you don't want to redirect
    //    echo "Go <a href='$authorization_redirect_url'>here</a>, copy the code, and paste it into the box below.<br /><form action=" . $_SERVER["PHP_SELF"] . " method = 'post'><input type='text' name='authorization_code' /><br /><input type='submit'></form>";
    }


    private function getAccessToken($authorization_code) {
        global $token_url, $client_id, $client_secret, $callback_uri;
    
        $authorization = base64_encode("$client_id:$client_secret");
        $header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");
        $content = "grant_type=authorization_code&code=$authorization_code&redirect_uri=$callback_uri";
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->token_url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    
        if ($response === false) {
            echo "Failed";
            echo curl_error($curl);
            echo "Failed";
        } elseif (json_decode($response)->error) {
            echo "Error:<br />";
            echo $authorization_code;
            echo $response;
        }
    
        return json_decode($response)->access_token;
    }

    private function getResource($access_token) {
        
    
        $header = array("Authorization: Bearer {$access_token}");
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->test_api_url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    
        return json_decode($response, true);
    }


}
