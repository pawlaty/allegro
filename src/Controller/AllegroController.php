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
        $allegro = new Allegro();

        $allegro_categories = $allegro->GetCategories($this->allegro_token['access_token']);

        $this->set(compact('allegro_categories'));
    }

    /**
     * funkcja majaca odnaleźć przedmiot po słowach
     * niestety w budowie
     * proszę traktować jakby jej nie było
     * (narazie)
     */
    public function finditem($item=null)
    {
        $allegro = new Allegro();
        $allegroItems = null;

        if($this->request->is('get'))
        {

           // if($allegro->execute($this->request->getData()))
           // {
                $items = $this->request->getData('itemss');
                echo $items;
                //$items = implode(' ',$item);

                $allegroItems = $allegro->FindItem($items);
                ///$allegro->setData(['allegroItems'=>$allegro->FindItem($items)]);
           /* }
            else
            {
                $this->Flash->error('There is no allegro->execute->(this->request->getdata()');
                $allegro->setData(['allegroItems'=>null,
                                    'items'=>['książę','harry']]);
                //$allegroItems = null;
            }*/
        }
        $this->set(compact('items'));
        $this->set('allegro',$allegro);
        $this->set(compact('allegroItems'));
    }



}
