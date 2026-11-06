<?php

 namespace Module\Lots\Controllers;

/*
 * Controller: Lots
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;
use Module\Lots\Models\Repositories\LotsRepository;

class LotsController extends Controller
{

use \Module\Lots\Traits\LotsTrait;
	

	/*<div data-pattern-func="Module:Lots@all"></div>*/
  public function all()
  {

       $aLots = LotsRepository::getLots();

       $data['lots'] = $aLots;

       return \View::make('lots::all' ,  $data );

  }

  /*<div data-pattern-func="Module:Lots@related-X"></div>*/
public function related($params = array())
  {   
       $limit = isset($params[0]) ? $params[0] : 1; 
       $aLots = LotsRepository::getRandomLots($limit);

       $data['related'] = $aLots;      
       
       return \View::make('lots::related' ,  $data );

  }



  /*<div data-pattern-func="Module:Lots@all"></div>*/
  public function single()
  {
      $base_route = \Route::input('page');
      //dd($base_route);
      $title = explode("-", $base_route);
      $id = end($title);
  
      $aLots = LotsRepository::getLots($id);
      $data['oLot'] = $aLots[0];


      return \View::make('lots::single' ,  $data );

  }

}
