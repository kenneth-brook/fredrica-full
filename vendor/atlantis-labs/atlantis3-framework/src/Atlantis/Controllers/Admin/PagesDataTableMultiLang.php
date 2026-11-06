<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Models\Page;
use Illuminate\Support\Facades\DB;
use Atlantis\Helpers\Tools;
use Atlantis\Models\Repositories\PageRepository;

class PagesDataTableMultiLang implements \Atlantis\Helpers\Interfaces\DataTableInterface
{

    public function __construct()
    {

        if (\Auth::check() === false) {

            return response()->json([]);
        }
        if (auth()->user() != NULL) {
            \App::setLocale(auth()->user()->language);
        }
    }

    public function columns()
    {

        return [
            [
                'title' => '<span class="fa fa-check-square-o select-all"></span>',
                'class-th' => 'checkbox no-sort',
                'class-td' => 'checkbox',
                'key' => 'checkbox',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => trans('admin::views.ID'),
                'class-th' => '', // class for <th>
                'class-td' => 'id', // class for <td>
                'key' => 'id', // db column name
                'order' => [
                    'sorting' => TRUE, // only one column have TRUE
                    'order' => 'desc'
                ]
            ],
            [
                'title' => trans('admin::views.Name'),
                'class-th' => '',
                'class-td' => 'name',
                'key' => 'name',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => trans('admin::views.Url'),
                'class-th' => '',
                'class-td' => 'url',
                'key' => 'url',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => trans('admin::views.Template'),
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'template',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => trans('admin::views.Language'),
                'class-th' => 'no-sort',
                'class-td' => 'template-class name',
                'key' => 'lang',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'DESC'
                ]
            ],
            [
                'title' => trans('admin::views.Updated at'),
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'updated_at',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ]
        ];
    }

    /**
     * Fill array or return empty.
     *
     * @return array
     */
    public function bulkActions()
    {

        return [
            'url' => 'admin/pages/bulk-action',
            'actions' => [
                [
                    'name' => trans('admin::views.Delete'),
                    'key' => 'bulk_delete'
                ],
                [
                    'name' => trans('admin::views.Deactivate'),
                    'key' => 'bulk_deactivate'
                ],
                [
                    'name' => trans('admin::views.Activate'),
                    'key' => 'bulk_activate'
                ]
            ]
        ];
    }

    public function getData(\Illuminate\Http\Request $request)
    {

        $lang = config('atlantis.default_language');
        $model = DB::table('pages')
            ->select('pages.*', 'pages_versions.version_id')
            ->join('pages_versions', function ($join) use($lang) {
                $join->on('pages.id', '=', 'pages_versions.page_id')
                    ->where('pages_versions.active', '=', 1)
                    ->where('pages_versions.language', '=', $lang);
            });
        /*
         * SEARCH
         */
        if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
            $search = $request->get('search')['value'];

            $model->where('pages.id', 'LIKE', '%' . $search . '%');
            $model->orWhere('pages.name', 'LIKE', '%' . $search . '%');
            $model->orWhere('pages.url', 'LIKE', '%' . $search . '%');
            $model->orWhere('pages.template', 'LIKE', '%' . $search . '%');
        }

        $model->having('pages.status', '!=', 5);

        /*
         * Count filtered data without LIMIT and OFFSET
         */
        $modelWhitoutOffset = $model;
        $count = count($modelWhitoutOffset->get());

        /*
         * OFFSET and LIMIT
         */
        $model->take($request->get('length'));
        $model->skip($request->get('start'));

        /*
         * ORDER BY
         */
        if (isset($request->get('order')[0]['column']) && isset($request->get('order')[0]['dir'])) {

            $column = $request->get('order')[0]['column'];
            $dir = $request->get('order')[0]['dir'];
            $columns = $request->get('columns');

            if ($columns[$column]['data'] == "version") {
                $model->orderBy("pages_versions.version_id", $dir);
            } else {
                $model->orderBy("pages." . $columns[$column]['data'], $dir);
            }

        }

        /*
         * Get filtered data
         */
        $modelWithOffset = $model->get();

        $data = array();
       
        foreach ($modelWithOffset as $k => $obj) {
            $data[$k] = [
                'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
                'id' => $obj->id,
                'name' => $this->nameTd($obj, $lang),
                'url' => $obj->url,
                'template' => $obj->template,
                'lang' => $this->langTd($obj),
                'updated_at' => $obj->updated_at
            ];
        }

        return response()->json([
            'drow' => $request->get('draw'),
            'recordsTotal' => Page::where('status', '!=', 5)->get()->count(),
            'recordsFiltered' => $count,
            'data' => $data
        ]);
    }

    private function langTd($obj)
    {   

        $defLang = config('atlantis.default_language');
        $langsedit = '';
        $aLangs = \Tools::getThemeLanguages();
        $active_versions = PageRepository::getActiveVersionPerLang($obj->id);

        
        foreach($aLangs as $k => $l) {
            //admin/pages/edit/{{ $oPage->id }}/" + $(this).find(':selected').attr('data-active-version') + '/' + $(this).val()
            if(isset($active_versions[$k]) && $l != $defLang ){

            $langsedit .=' <a class="top" data-tooltip title="' . trans('admin::views.Edit'). ' ' .trans('admin::languages.'.$l).'" href="admin/pages/edit/' .$obj->id . '/' . $active_versions[$k] .'/'. $k . '">[' . $l . "]</a> ";
            }
            
        }
         return $langsedit;
    }

    private function nameTd($obj, $lang)
    {

        $status = '';

        if ($obj->status == 0) {
            $status = 'disabled';
        } else if ($obj->status == 1) {
            $status = 'active';
        } else if ($obj->status == 2) {
            $status = 'dev';
        } else if ($obj->status == 5) {
            $status = 'disabled';
        }

        if ($obj->url == '/') {
            $url = '';
        } else {
            $url = $obj->url;
        }

        $protected = '';

        if ($obj->protected == 1) {
            $protected = ' <i class="icon icon-Key" aria-hidden="true"></i>';
        }

        return '<span class="tags hidden">tags</span>
                    <a class="item" data-status="' . $status . '" href="admin/pages/edit/' . $obj->id . '">' . $obj->name . $protected . '</a>
                    <span class="actions">
                      <a data-tooltip data-alt-text="'.trans('admin::views.Edit Page').'" title="'.trans('admin::views.Edit Page').'" href="admin/pages/edit/' . $obj->id . '" class="icon icon-Edit top"></a>
                      <a data-tooltip data-alt-text="'.trans('admin::views.Preview Page').'" title="'.trans('admin::views.Preview Page').'" target="blank" href="' . $url . '" class="icon icon-Export top"></a>                      
                      <a data-open="clonePage' . $obj->id . '" data-tooltip data-alt-text="'.trans('admin::views.Clone Page').'" title="'.trans('admin::views.Clone Page').'" class="icon icon-Files top"></a>
                      <a data-open="deletePage' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" data-alt-text="'.trans('admin::views.Delete Page').'" title="'.trans('admin::views.Delete Page').'" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deletePage' . $obj->id, trans('admin::views.Delete Page'), trans('admin::views.Are you sure you want to delete', ['object' =>  $obj->name]), trans('admin::views.Delete'), 'admin/pages/delete-page/' . $obj->id) .
            \Atlantis\Helpers\Modal::setClonePage('clonePage' . $obj->id, 'admin/pages/clone-page/' . $obj->id . '/' . $lang, $obj->name . '-clone', $obj->url);
    }

    /**
     * Add class to <table></table> tag
     *
     */
    public function tableClass()
    {
        return NULL;
    }

}
