<?php

 namespace Module\Listings\Controllers;

/*
 * Controller: Listings
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;
use Module\Listings\Models\Repositories\ListingsRepository;
use Module\Listings\Models\Listings;

class ListingsController extends Controller
{

use \Module\Listings\Traits\ListingsTrait;
	

	/*<div data-pattern-func="Module:Listings@all"></div>*/
  public function all()
  {

       $aListings = ListingsRepository::getListings();

       $data['listings'] = $aListings;

       return \View::make('listings::all' ,  $data );

  }


  /*<div data-pattern-func="Module:Listings@all"></div>*/
  public function single()
  {
      $base_route = explode('/', \Route::input('page'));
      $url = end($base_route);

     
      //$aListings = ListingsRepository::getListings()->where('url', '=', $url)->first();

      $aListings = Listings::where('url', '=', $url)->first();
      
      if (!is_null($aListings)){

        $data['oListing'] = $aListings;
        return \View::make('listings::single' ,  $data );
      }
      else {
          return redirect('/st-simons-homes-for-sale');
      }


  }
  /*<div data-pattern-func="Module:Listings@related-X"></div>*/

   public function related($params = array())
  {   
       $limit = isset($params[0]) ? $params[0] : 1; 
       $aListings = ListingsRepository::getRandomListings($limit);

       $data['related'] = $aListings;      
       
       return \View::make('listings::related' ,  $data );

  }

  public function alfordLaneOne()
  {
      
      $aListings = ListingsRepository::getAlfordLaneListings()->where('listing_type', '=', 'Phase 1')->paginate();
      $data['listings'] = $aListings;

      if (count($aListings)){

      	return \View::make('listings::all' ,  $data );
      	
      }
      else {
      	return '<center><h3>Phase 1 <br>Sold Out</h3></center>';
      }

  }

  public function alfordLaneTwo()
  {
      
      $aListings = ListingsRepository::getAlfordLaneListings()->where('listing_type', '=', 'Phase 2')->paginate(15);
      $data['listings'] = $aListings;

      if (count($aListings)){

      	return \View::make('listings::all' ,  $data );
      	
      }
      else {
      	return '<center><h3>Phase 2 <br>Sold Out</h3></center>';
      }

  }

}
