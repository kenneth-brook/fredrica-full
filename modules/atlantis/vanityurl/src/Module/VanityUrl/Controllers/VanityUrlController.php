<?php

namespace Module\VanityUrl\Controllers;

/*
 * Controller: VanityUrl
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;
use Module\VanityUrl\Models\Repositories\VanityUrlRepository;
use Illuminate\Support\Facades\URL;
class VanityUrlController extends Controller {

	use \Module\VanityUrl\Traits\VanityUrlTrait;

	public function __construct() {

	}

	public function redirect( $path) {

   //$requestUrl = URL::full();
		$result = VanityUrlRepository::findSourceURL($path);

		$query = request()->getQueryString();
		if ($query && strlen($query)) {
			$path = $path .  "?" . request()->getQueryString();

		}


		if ($result != NULL) {
			$url = $result->dest_url;
			if ($query && strlen($query)) { 
				$url .= '?' . $query;
			}
			return redirect($url, 301)->send();
		}
	}

}
