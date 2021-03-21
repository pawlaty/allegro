<?php

namespace App\Model\Allegro;

use Cake\Http\Client;
use Cake\Http\Client\Request;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class Allegro extends Form
{
    /*
    protected function _buildSchema(Schema $schema):_buildSchema
    {
        return $schema->addFleld('name','string')=>addField('email'['type'=>'string'])=>addField('body',['type'=>'text']);
    }
    */
    /*
    public function validationDefault(Validator $validator):Validator
    {
        $validator->minLength('name',10)->email('email');
        return $validator;
    }
    */

    protected function _execute(array $data):bool
    {
        //send the email
        return true;
    }


    public function GetToken()
        {
        $http = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $response = $http->get('https://allegro.pl.allegrosandbox.pl/auth/oauth/token?grant_type=client_credentials',[],
                                ['auth'=> ['username' => '3fe9b1c100c143b393c1281fd91361d3',
                                            'password'=>'HxkKsIfSoP74VKJWaoXI6NBMQU9fIPwPiOI1otFhwK7ywokXxYuHwIeIzxhUCFEC'
                                          ]]);
        $result = $response->getJson();
       // return $result['access_token'];
        return $result;
    }

    public function FindItem($words){
        /*
        if(!is_array($words)){
            exit('wrong type parametrs');
        }*/

       // $words_str = implode(' ',$words);
        $http = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $response = $http->get("https://api.allegro.pl/offers/listing?phrase={$words}&searchMode=REGULAR&fallback=true&include=-all&include=filters",[],
                               ['auth'=> ['username' => '3fe9b1c100c143b393c1281fd91361d3',
                                          'password'=>'HxkKsIfSoP74VKJWaoXI6NBMQU9fIPwPiOI1otFhwK7ywokXxYuHwIeIzxhUCFEC'
                                         ]]);
        $result = $response->getJson();

        return $result;

    }

    public function GetCategories(String $token):array
    {   // $getCategoriesUrl = "https://api.allegro.pl/allegrosandbox.pl/order/events";
        //$getCategoriesUrl   = "https://api.allegro.pl.allegrosandbox.pl/offers/listing";
        $getCategoriesUrl = "https://api.allegro.pl.allegrosandbox.pl/sale/categories";

        //$bearer = 'Bearer '.$token;

        $http = new Client();         //'Content-Type' => 'application/json']]);
        //$http->addHeaders(['Authorization'=>$bearer, "Accept"=>"application/vnd.allegro.public.v1+json"]);

        $response = $http->get($getCategoriesUrl,[],['headers' => ['Title'=>'my title','Authorization'=>'Bearer '.$token, "Accept"=>"application/vnd.allegro.public.v1+json"]]);
/*
Ten problem pojawia się tylko w tym zasobie czy też w innych zasobach? Prosimy o podanie nam identyfikatora śledzenia z połączenia, jeśli istnieje problem. Wyślij również do nas (możesz to zrobić za pomocą naszego formularza kontaktowego ) cURL z zapytaniem i odpowiedzią.

Jeśli wyślę żądanie do testowego API ...

Masz na myśli Allegro Sandbox? W pierwszej kolejności należy zmienić adres zasobu na https://api.allegro.pl.allegrosandbox.pl/ . To jest adres zasobów w Sandbox.

Powinieneś także użyć poświadczeń również z Sandbox . Jakich danych logowania użyłeś w tej prośbie?

*/
        $result = array();
        $result['responseJSON'] = $response->getJson();
        $result['code'] = $response->getStatusCode();
        $result['headers'] = $response->getHeaders();
/*
        $ch = curl_init($getCategoriesUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            "Authorization: Bearer $token",
                            "Accept: application/vnd.allegro.public.v1+json"
        ]);

        $maincategoriesresult = curl_exec($ch);
        $result_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       // $result_message = curl_getinfo($ch, CURLINFO_HTTP_MESSAGE);

        curl_close($ch);
*/
        if($response === false ||  $result['code'] !== 200)
        {
            echo "code: ". $result['code']."<br>";
            echo "message: <br>";

            echo "<pre>";
            print_r($result['headers']);
            echo "</pre>";
            echo 'result its:';
            echo "<pre>";
            print_r($result['responseJSON']);
            echo "</pre>";

            exit();
        }
/*      $categories_list = json_decode($maincategoriesresult);          */

        //return (object)$result['responseJSON'];
        return $result['responseJSON'];

    }
}
