<?php

/**
 * Allegro Controller
 * By Pawlaty na podstawie dokumentacji cakephp 4.* oraz dokmumentacji ApiAllegro
 */

declare(strict_types=1);
namespace App\Controller;
use App\Model\Allegro\Allegro;

/**
 * @method \App\Model\Entity\Allegro[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AllegroController extends AppController
{

    private $allegro_token;
    private $allegro;

    /**
     * 1) tworzymy obiekt modelu allegro
     * 2) pobieramy do zmiennej token z modelu allegro
     */
    public function initialize():void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->allegro = new Allegro();
        $this->allegro_token = $this->allegro->GetToken();
    }

    /**
     * w metodzie index przesylamy tablice kategorii z allegro sandbox api do zmiennej
     * a nastepnie ustawiamy ta zmienną w widoku index
     */
    public function index()
    {
        $allegro_categories = $this->allegro->GetCategories($this->allegro_token['access_token']);

        $this->set(compact('allegro_categories'));
    }

    /**
     * funkcja majaca odnaleźć przedmiot po słowach
     *
     */
    public function finditem($itemss='harry')
    {
        $this->request->allowMethod('get');
        $allegroItems = null;

        if($this->request->is('get') && $this->request->getQuery('itemss') !== null)
        {
                $itemss = $this->request->getQuery('itemss');
                $allegroItems = $this->allegro->FindItem($this->allegro_token['access_token'],$itemss);

        }
        else
        {
                $itemss = 'harry ksiaze';
                $allegroItems = $this->allegro->FindItem($this->allegro_token['access_token'],$itemss);
        }

        $this->set(compact('itemss'));
        $this->set('allegro',$this->allegro);
        $this->set(compact('allegroItems'));
    }



}
