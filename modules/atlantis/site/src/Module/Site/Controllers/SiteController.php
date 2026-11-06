<?php

 namespace Module\Site\Controllers;

/*
 * Controller: Site
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class SiteController extends Controller
{


  public function __construct()
  {


  }

  public function map()
  {

  		$data['listings'] = DB::table('listings')->where('active', '=', '1')->get();
  		$data['lots'] = DB::table('lots')->where('sold', '=', '0')->get();
  		
    	return \View::make('site::map' , $data );

  }

}
