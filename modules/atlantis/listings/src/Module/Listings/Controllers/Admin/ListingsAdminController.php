<?php

namespace Module\Listings\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;
use Illuminate\Http\Request;
use Module\Listings\Models\Repositories\ListingsRepository as ListingsRepository;

class ListingsAdminController extends AdminModulesController
{

    /**
     * @var ListingsRepository
     */
    public $oListingsRepository;

    /**
     * ListingsAdminController constructor.
     * @param ListingsRepository $oListingsRepository
     */
    public function __construct(ListingsRepository $oListingsRepository)
    {
        $this->oListingsRepository = $oListingsRepository;
        parent::__construct(\Config::get('listings.setup'));
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex($id = null)
    {
        return view("listings-admin::admin/index");
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getListingsList()
    {
        return view("listings-admin::admin/index");
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddListings()
    {

        return view("listings-admin::admin/add_listings");
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postAddListings(Request $request)
    {
        $aData = $request->all();
        $validator = $this->oListingsRepository->validateListings($aData);
        
        if (!$validator->fails()) {

        	$maxWeight = 1 +  $this->oListingsRepository->getMaxWeight();

        	
            $aData['weight'] = $maxWeight;
            
            if (!empty($aData['images'])) {
                $aData['images'] = implode(",", $aData['images']);
            }

            //Create new Listings
            $this->oListingsRepository->createListings($aData);

            return redirect('/admin/modules/listings/listings-list');

        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param null $iId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEditListings($iId = null)
    {
        $oListing = $this->oListingsRepository->getListings($iId);
        //dd($oListing[0]);
        if ($oListing) {    

            return view("listings-admin::admin/edit_listings", $oListing[0]);

        } else {
            return redirect('/admin/modules/listings/listings-list');
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postEditListings(Request $request)
    {
        $aData = $request->all();
        $validator = $this->oListingsRepository->validateListings($aData);
    
        if (!$validator->fails()) {
           
            if (!empty($aData['images'])) {
                $aData['images'] = implode(",", $aData['images']);
            }

            //Create new Listings
            $this->oListingsRepository->updateListings($aData['iListingsId'], $aData);
 
           \Session::flash('success', 'Listing was updated');

            return redirect('/admin/modules/listings/listings-list');
        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param null $iId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getRemoveListings($iId = null)
    {
        $this->oListingsRepository->removeListings($iId);
        return redirect('/admin/modules/listings/listings-list');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postBulkAction(Request $request)
    {
        if ($request->get('bulk_action_ids') != NULL) {

            $aIDs = explode(',', $request->get('bulk_action_ids'));

            if ($request->get('action') == 'bulk_delete') {

                foreach ($aIDs as $id) {
                    $this->getRemoveListings($id);
                }
                \Session::flash('success', 'Listings were deleted');
            }
        }

        return redirect()->back();
    }

}
