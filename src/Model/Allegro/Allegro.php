<?php
/**
 *
 * By Pawlaty na podstawie dokumentacji cakephp 4.* oraz dokmumentacji ApiAllegro
 *
 *
 */

namespace App\Model\Allegro;

use Cake\Http\Client;
use Cake\Http\Client\Request;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;


/*
*Klasa odpowiedzailna za połączenie z Api alegro i podstawowe zapytania
*/


class Allegro extends Form
{
    private $result = array();
    /*
    *funkcja z dokumentacji -- niewykorzystana
    protected function _buildSchema(Schema $schema):_buildSchema
    {
        return $schema->addFleld('name','string')=>addField('email'['type'=>'string'])=>addField('body',['type'=>'text']);
    }
    */
    /*
    **funkcja z dokumentacji -- niewykorzystana
    public function validationDefault(Validator $validator):Validator
    {
        $validator->minLength('name',10)->email('email');
        return $validator;
    }
    */
    /*
    *funkcja z dokumentacji -- niewykorzystana
    */
    protected function _execute(array $data):bool
    {
        //send the email
        return true;
    }

    /*
    *sprawdza czy jest odpowiedz i czy statut jest 200
    *jezeli tak zwraca true i ustawia zmienne w tablicy asocjacyjnej
    *result na wartosci pobrane w zapytaniu
    */
    private function CheckResult($response):bool
    {

        $this->result['responseJSON'] = $response->getJson();
        $this->result['code'] = $response->getStatusCode();
        $this->result['headers'] = $response->getHeaders();

        if($response === false ||  $this->result['code'] !== 200)
        {
            echo "code: ". $this->result['code']."<br>";
            echo "message: <br>";

            echo "<pre>";
            print_r($this->result['headers']);
            echo "</pre>";
            echo 'result its:';
            echo "<pre>";
            print_r($this->result['responseJSON']);
            echo "</pre>";
            return false;
            exit();
        }
        return true;
    }

    /**
     * Sprawdza dane wejsciowe od urzytkownika do funkcji
     */
    private function myValidation(String $param):String
    {
        $param = trim($param);
        $param = stripcslashes($param);
        $param = htmlspecialchars($param);
        return $param;
    }

    /*
    *zwraca tokena po autoryzacji
    *
    */
    public function GetToken()
    {
        $http = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $response = $http->get('https://allegro.pl.allegrosandbox.pl/auth/oauth/token?grant_type=client_credentials',[],
                                ['auth'=> ['username' => '*************************1281fd91361d3',
                                            'password'=>'******************************I1otFhwK7ywokXxYuHwIeIzxhUCFEC'
                                          ]]);
        $this->result = $response->getJson();   //dostęp do pola obiektu JSON: $result['access_token'];

        return $this->result;
    }


    /*
    *Pobiera kategorie z sandboxa allegro i zwraca jako tablica
    */
    public function GetCategories(String $token):array
    {
        // $getCategoriesUrl = "https://api.allegro.pl/allegrosandbox.pl/order/events";
        //$getCategoriesUrl   = "https://api.allegro.pl.allegrosandbox.pl/offers/listing";
        $getCategoriesUrl = "https://api.allegro.pl.allegrosandbox.pl/sale/categories";

        $http = new Client();

        $response = $http->get($getCategoriesUrl,[],['headers' => ['Title'=>'my title','Authorization'=>'Bearer '.$token, "Accept"=>"application/vnd.allegro.public.v1+json"]]);

        $this->CheckResult($response);

        // rzutowanie  zwróconego obiektu na stdClass: (object)$result['responseJSON'];
        //zwrócenie tablicy wyników:
        return $this->result['responseJSON'];

    }

    /*
    *Znalezienie pozycji po slowach kluczowych
    */
    public function FindItem(String $token, String $words):array
    {
        $words = $this->myValidation($words);

        $getItemByWord = "https://api.allegro.pl.allegrosandbox.pl/offers/listing?phrase={$words}&searchMode=REGULAR&fallback=false&include=-all&include=filters";

        $http = new Client();

        $response = $http->get($getItemByWord,[],
                               ['headers' => ['Title'=>'my title','Authorization'=>'Bearer '.$token, "Accept"=>"application/vnd.allegro.public.v1+json"]]);

        $this->CheckResult($response);


        return $this->result['responseJSON'];

    }


}
