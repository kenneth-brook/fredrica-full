<?php

namespace Module\Lots\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;
use Illuminate\Http\Request;
use Module\Lots\Models\Repositories\LotsRepository as LotsRepository;

class LotsAdminController extends AdminModulesController
{

    /**
     * @var LotsRepository
     */
    public $oLotsRepository;

    /**
     * LotsAdminController constructor.
     * @param LotsRepository $oLotsRepository
     */
    public function __construct(LotsRepository $oLotsRepository)
    {
        $this->oLotsRepository = $oLotsRepository;
        parent::__construct(\Config::get('lots.setup'));
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex($id = null)
    {
        return view("lots-admin::admin/index");
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLotsList()
    {
        return view("lots-admin::admin/index");
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddLots()
    {

        return view("lots-admin::admin/add_lots");
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postAddLots(Request $request)
    {
        $aData = $request->all();
        $validator = $this->oLotsRepository->validateLots($aData);
        if (!$validator->fails()) {
            
            if (!empty($aData['imgs'])) {
                $aData['imgs'] = implode(",", $aData['imgs']);
            }

            //Create new Lots
            $this->oLotsRepository->createLots($aData);

            return redirect('/admin/modules/lots/lots-list');

        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param null $iId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEditLots($iId = null)
    {
        $oLot = $this->oLotsRepository->getLotsById($iId);
       
        if ($oLot) {    

            return view("lots-admin::admin/edit_lots", $oLot);

        } else {
            return redirect('/admin/modules/lots/lots-list');
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postEditLots(Request $request)
    {
        $aData = $request->all();
        
        $validator = $this->oLotsRepository->validateLots($aData);
    
        if (!$validator->fails()) {
           
            if (!empty($aData['imgs'])) {
                $aData['imgs'] = implode(",", $aData['imgs']);
            }

            //Create new Lots
            $this->oLotsRepository->updateLots($aData['iLotsId'], $aData);
 
           \Session::flash('success', 'Lot was updated');

            return redirect('/admin/modules/lots/lots-list');
        } else {
            //Show errors
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param null $iId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getRemoveLots($iId = null)
    {
        $this->oLotsRepository->removeLots($iId);
        return redirect('/admin/modules/lots/lots-list');
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
                    $this->getRemoveLots($id);
                }
                \Session::flash('success', 'Lots were deleted');
            }
        }

        return redirect()->back();
    }

}
