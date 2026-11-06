<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Models\Media;
use Illuminate\Support\Facades\DB;

class MediaDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface
{

    /**
     * MediaDataTable constructor.
     */
    public function __construct()
    {
        if (\Auth::check() === false) {

            return response()->json([]);
        }
        if (auth()->user() != NULL) {
            \App::setLocale(auth()->user()->language);
        }
    }

    /**
     * @return array
     */
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
                'title' => 'ID',
                'class-th' => '', // class for <th>
                'class-td' => 'id', // class for <td>
                'key' => 'id', // db column name
                'order' => [
                    'sorting' => TRUE, // only one column have TRUE
                    'order' => 'desc'
                ]
            ],
            [
                'title' => trans('admin::views.Thumbnail'),
                'class-th' => 'thumb',
                'class-td' => 'table-tmb',
                'key' => 'thumbnail',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => trans('admin::views.Name'),
                'class-th' => '',
                'class-td' => 'name',
                'key' => 'filename',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => trans('admin::views.Type'),
                'class-th' => '',
                'class-td' => 'url',
                'key' => 'type',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => trans('admin::views.Size'),
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'filesize',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
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
            'url' => 'admin/media/bulk-action-media',
            'actions' => [
                [
                    'name' => trans('admin::views.Delete'),
                    'key' => 'bulk_delete'
                ],
                [
                    'name' => trans('admin::views.Add tags'),
                    'key' => 'bulk_tags'
                ],
                [
                    'name' => trans('admin::views.Add to existing gallery'),
                    'key' => 'bulk_to_gallery'
                ],
                [
                    'name' => trans('admin::views.Add to new gallery'),
                    'key' => 'bulk_new_gallery'
                ]
            ]
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(\Illuminate\Http\Request $request)
    {
        $model = DB::table('media');
        /*
         * SEARCH
         */
        if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
            $search = $request->get('search')['value'];

            $oTags = \Atlantis\Models\Repositories\TagRepository::findByTag(AdminController::$_ID_MEDIA, $search);
            $aMediaTagIDs = array();
            foreach ($oTags as $t) {
                $aMediaTagIDs[] = $t->resource_id;
            }

            $model->where('id', 'LIKE', '%' . $search . '%');
            $model->orWhere('filename', 'LIKE', '%' . $search . '%');
            $model->orWhere('original_filename', 'LIKE', '%' . $search . '%');
            $model->orWhere('type', 'LIKE', '%' . $search . '%');
            $model->orWhere('filesize', 'LIKE', '%' . $search . '%');
            $model->orWhereIn('id', $aMediaTagIDs);
        }

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

            $model->orderBy($columns[$column]['data'], $dir);
        }

        /*
         * Get filtered data
         */
        $modelWithOffset = $model->get();

        $data = array();

        $filePath = \Atlantis\Helpers\Tools::getFilePath();

        $aGalleries = \Atlantis\Models\Repositories\GalleryRepository::getAllGalleriesForSelect();

        foreach ($modelWithOffset as $k => $obj) {

            if (empty($obj->filename)) {
                $name = $obj->original_filename;
            } else {
                $name = $obj->filename;
            }

            $data[$k] = [
                'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
                'id' => $obj->id,
                'thumbnail' => $this->thumbnailTd($obj, $filePath, $name),
                'filename' => $this->nameTd($obj, $aGalleries),
                'type' => $obj->type,
                'filesize' => \Atlantis\Helpers\Tools::formatBytes($obj->filesize),
                'updated_at' => $obj->updated_at
            ];
        }

        return response()->json([
            'drow' => $request->get('draw'),
            'recordsTotal' => Media::count(),
            'recordsFiltered' => $count,
            'data' => $data
        ]);
    }

    /**
     * @param $obj
     * @param $aGalleries
     * @return string
     */
    private function nameTd($obj, $aGalleries)
    {

        if (empty($obj->filename)) {
            $name = $obj->original_filename;
        } else {
            $name = $obj->filename;
        }

        if (!empty($obj->thumbnail)) {
            $addToGalleryLink = '<a data-open="addToGallery' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="'.trans('admin::views.Add to gallery').'" class="icon icon-Picture top "></a>';
            $addToGalleryModal = \Atlantis\Helpers\Modal::addImgToGallery('addToGallery' . $obj->id, $obj->id, $name, $aGalleries);
        } else {
            $addToGalleryLink = '';
            $addToGalleryModal = '';
        }

        return '<a class="item" href="admin/media/media-edit/' . $obj->id . '">' . $name . '</a>
                    <span class="actions">
                      <a data-tooltip data-alt-text="'.trans('admin::views.Edit File').'" title="'.trans('admin::views.Edit File').'" href="admin/media/media-edit/' . $obj->id . '" class="icon icon-Edit top"></a> ' .
            $addToGalleryLink .
            '<a data-open="deleteMedia' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" data-alt-text="'.trans('admin::views.Delete File').'" title="'.trans('admin::views.Delete File').'" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deleteMedia' . $obj->id, trans('admin::views.Delete File'), trans('admin::view.Are you sure you want to delete forever', ['object' => $name]), trans('admin::views.Delete'), 'admin/media/media-delete/' . $obj->id) .
            $addToGalleryModal;
    }

    /**
     * @param $obj
     * @param $filePath
     * @param $name
     * @return string
     */
    private function thumbnailTd($obj, $filePath, $name)
    {
         $tags = \Atlantis\Models\Repositories\TagRepository::getTagsByResourceID(AdminController::$_ID_MEDIA, $obj->id);
        $aTags = array();
        foreach ($tags as $tag) {
            $aTags[] = $tag->tag;
        }
        if (count($aTags)){   
        $tagsIcon  = '<a class="tags icon icon-Tag top" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="' . implode(', ', $aTags) . '"></a>';
        }
        else{
            $tagsIcon  = '';
        }
        if (!empty($obj->thumbnail)) {
            return '<a href="admin/media/media-edit/' . $obj->id . '"><img src="' . $filePath . $obj->thumbnail . '"></a>'.
            '<span class="actions">'.$tagsIcon.'<a data-open="fullscreen' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="'.trans('admin::views.View Full Image').'" class="icon icon-FullScreen top "></a></span>' .
            '<div class="reveal large text-center fixed" id="fullscreen'.$obj->id.'" data-reveal>
            <p>'.$name.'</p>
            <img data-src="'. $filePath . $obj->original_filename . '">
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>';
        } else {
            return '<span data-name="' . $name . '" class="icon icon-File"></span>';
        }
    }

    /**
     * @return null
     */
    public function tableClass()
    {
        return NULl;
    }
}
