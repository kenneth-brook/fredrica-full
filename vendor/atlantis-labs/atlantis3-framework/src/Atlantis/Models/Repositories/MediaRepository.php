<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Media;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Atlantis\Models\Repositories\TagRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MediaRepository {

    public static function getAll() {
        return Media::all();
    }

    public static function getImage($id) {

        return Media::find($id);
    }

    public static function search($search) {

        return Media::where('id', 'LIKE', '%' . $search . '%')
            ->orWhere('original_filename', 'LIKE', '%' . $search . '%')
            ->orWhere('original_filename', 'LIKE', '%' . $search . '%')
            ->orWhere('caption', 'LIKE', '%' . $search . '%')
            ->orWhere('credit', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->orWhere('type', 'LIKE', '%' . $search . '%')
            ->orWhere('alt', 'LIKE', '%' . $search . '%')
            ->orWhere('tablet_name', 'LIKE', '%' . $search . '%')
            ->orWhere('phone_name', 'LIKE', '%' . $search . '%')
            ->orWhere('thumbnail', 'LIKE', '%' . $search . '%')
            ->get();
    }

    public static function latestEdited($limit) {

        return Media::take($limit)
            ->orderBy('updated_at', 'DESC')
            ->get();
    }

    public static function addMedia($data, $filename) {

        $filePath = config('atlantis.user_media_upload') . $filename;
        $filename_desktop = '';
        $filename_tablet = '';
        $filename_phone = '';
        $filename_thumbnail = '';
        $mimeType = File::mimeType($filePath);
        $smartCrop = isset($data['smartcrop']) ? $data['smartcrop'] : FALSE;

        if (getimagesize($filePath) === FALSE) {
            //save file
        } else {

            //save or resize image
            if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.responsive_images'))) {
                //resize responsive image
                $aResize = config('atlantis.responsive_images')[$data['resize']];

                $desktopCrop = isset($aResize['desktop']['crop']) ? $aResize['desktop']['crop'] : FALSE;
                $tabletCrop = isset($aResize['tablet']['crop']) ? $aResize['tablet']['crop'] : FALSE;
                $phoneCrop = isset($aResize['phone']['crop']) ? $aResize['phone']['crop'] : FALSE;
                $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

                $filename_desktop = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_desktop', $aResize['desktop']['width'], $aResize['desktop']['height'], $desktopCrop);
                $filename_tablet = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_tablet', $aResize['tablet']['width'], $aResize['tablet']['height'], $tabletCrop);
                $filename_phone = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_phone', $aResize['phone']['width'], $aResize['phone']['height'], $phoneCrop);
                $filename_thumbnail = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop, $smartCrop);

                unlink($filePath);
            } else if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.static_images'))) {
                //resize static image
                $aResize = config('atlantis.static_images')[$data['resize']];

                $fullsizeCrop = isset($aResize['fullsize']['crop']) ? $aResize['fullsize']['crop'] : FALSE;
                $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

                $filename_desktop = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_desktop', $aResize['fullsize']['width'], $aResize['fullsize']['height'], $fullsizeCrop);
                $filename_thumbnail = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop, $smartCrop);

                unlink($filePath);
            } else {
                //save image
                $filename_thumbnail = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_thumbnail', 200, 150, TRUE, $smartCrop);
            }
        }

        if (!empty($filename_desktop)) {
            $filename = $filename_desktop;
        }

        $fileSize = File::size(config('atlantis.user_media_upload') . $filename);

        if (!empty($data['resize'])) {
            $resize[$data['resize']] = isset($aResize) ? $aResize : array();
        } else {
            $resize = NULL;
        }

        $newData = [
            'filename' => isset($data['filename']) ? $data['filename'] : '',
            'original_filename' => $filename,
            'tablet_name' => $filename_tablet,
            'phone_name' => $filename_phone,
            'thumbnail' => $filename_thumbnail,
            'filesize' => $fileSize,
            'type' => $mimeType,
            'caption' => isset($data['caption']) ? $data['caption'] : '',
            'credit' => isset($data['credit']) ? $data['credit'] : '',
            'description' => isset($data['description']) ? $data['description'] : '',
            'alt' => isset($data['alt']) ? $data['alt'] : '',
            'weight' => isset($data['weight']) ? $data['weight'] : '',
            'css' => isset($data['css']) ? $data['css'] : '',
            'anchor_link' => isset($data['anchor_link']) ? $data['anchor_link'] : '',
            'resize' => serialize($resize)
        ];

        $model = Media::create($newData);

        $evntData = [
            'media' => $model,
            'data' => $data
        ];
        \Event::fire('media.created', [$evntData]);

        $tags = isset($data['tags']) ? $data['tags'] : '';

        TagRepository::addTagsWithDelimiter(',', $tags, $model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA);

        $eventData = [
            'added' => [
                'original_filename' => $filename,
                'tablet_name' => $filename_tablet,
                'phone_name' => $filename_phone,
                'thumbnail' => $filename_thumbnail
            ]
        ];

        /** Fire the file.uploaded event * */
        \Event::fire('file.uploaded', [$eventData]);

        self::updateFileAfterEvents($model);

        return $model;
    }


    /*Update file version only thumb, medium or small after manual recrop or separate upload*/
    public static function editMediaVersion($id, $data, $filename) {

        $model = Media::find($id);

        $user_media_upload = config('atlantis.user_media_upload');

        $filePath = $user_media_upload . $filename;

        $version = isset($data['version']) ? $data['version'] : FALSE;

        $cropData = isset($data['cropData']) ? $data['cropData'] : FALSE;


        if ($model != NULL) {

            $mimeType = File::mimeType($filePath);

            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            $existExt = pathinfo($user_media_upload . $model->original_filename, PATHINFO_EXTENSION);

            if ($mimeType != $model->type || $ext != $existExt) {

                unlink($filePath);

                return response()->json([
                    'jsonrpc' => 2.0,
                    'error' => 'The file extension is not the same.'
                ]);
            }

            if (getimagesize($filePath) === FALSE) {
                //save file
            } else {

                //save or resize image
                if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.responsive_images'))) {
                    //resize responsive image
                    $aResize = config('atlantis.responsive_images')[$data['resize']];

                    $crop = isset($aResize[$version]['crop']) ? $aResize[$version]['crop'] : FALSE;

                    $filename_new = self::resizeImage($user_media_upload, $filename, '_'.$version, $aResize[$version]['width'], $aResize[$version]['height'], $crop, $cropData, TRUE);

                } else if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.static_images'))) {
                    //resize static image
                    $aResize = config('atlantis.static_images')[$data['resize']];

                    $crop = isset($aResize[$version]['crop']) ? $aResize[$version]['crop'] : FALSE;

                    $filename_new = self::resizeImage($user_media_upload, $filename, '_'.$version, $aResize[$version]['width'], $aResize[$version]['height'], $crop, $cropData, TRUE);

                } else {
                    //save image
                    $filename_new = self::resizeImage($user_media_upload, $filename, '_thumbnail', 200, 150, TRUE, $cropData);
                }
            }

            $eventData = array();
            $eventData['updated']['tablet_name'] = $filename_new;

            /** Fire the file.uploaded event * */
            \Event::fire('file.uploaded', [$eventData]);

            self::updateFileAfterEvents($model);

        }
        else {

            return response()->json([
                'jsonrpc' => 2.0,
                'error' => 'Oops something wrong.'
            ]);
        }

    }

    public static function editMediaWithFile($id, $data, $filename) {


        $model = Media::find($id);

        $user_media_upload = config('atlantis.user_media_upload');

        $filePath = $user_media_upload . $filename;

        $smartCrop = isset($data['smartcrop']) ? $data['smartcrop'] : FALSE;

        if ($model != NULL) {

            $filename_desktop = '';
            $filename_tablet = '';
            $filename_phone = '';
            $filename_thumbnail = '';
            $mimeType = File::mimeType($filePath);

            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            $existExt = pathinfo($user_media_upload . $model->original_filename, PATHINFO_EXTENSION);

            if ($mimeType != $model->type || $ext != $existExt) {

                unlink($filePath);

                return response()->json([
                    'jsonrpc' => 2.0,
                    'error' => 'The file extension is not the same.'
                ]);
            }

            if (getimagesize($filePath) === FALSE) {
                //save file
            } else {

                //save or resize image
                if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.responsive_images'))) {
                    //resize responsive image
                    $aResize = config('atlantis.responsive_images')[$data['resize']];

                    $desktopCrop = isset($aResize['desktop']['crop']) ? $aResize['desktop']['crop'] : FALSE;
                    $tabletCrop = isset($aResize['tablet']['crop']) ? $aResize['tablet']['crop'] : FALSE;
                    $phoneCrop = isset($aResize['phone']['crop']) ? $aResize['phone']['crop'] : FALSE;
                    $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

                    $filename_desktop = self::resizeImage($user_media_upload, $filename, '_desktop', $aResize['desktop']['width'], $aResize['desktop']['height'], $desktopCrop);
                    $filename_tablet = self::resizeImage($user_media_upload, $filename, '_tablet', $aResize['tablet']['width'], $aResize['tablet']['height'], $tabletCrop);
                    $filename_phone = self::resizeImage($user_media_upload, $filename, '_phone', $aResize['phone']['width'], $aResize['phone']['height'], $phoneCrop);
                    $filename_thumbnail = self::resizeImage($user_media_upload, $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop, $smartCrop);

                    unlink($filePath);
                } else if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.static_images'))) {
                    //resize static image
                    $aResize = config('atlantis.static_images')[$data['resize']];

                    $fullsizeCrop = isset($aResize['fullsize']['crop']) ? $aResize['fullsize']['crop'] : FALSE;
                    $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

                    $filename_desktop = self::resizeImage($user_media_upload, $filename, '_desktop', $aResize['fullsize']['width'], $aResize['fullsize']['height'], $fullsizeCrop);
                    $filename_thumbnail = self::resizeImage($user_media_upload, $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop, $smartCrop);

                    unlink($filePath);
                } else {
                    //save image
                    $filename_thumbnail = self::resizeImage($user_media_upload, $filename, '_thumbnail', 200, 150, TRUE, $smartCrop);
                }
            }

            if (!empty($filename_desktop)) {
                $filename = $filename_desktop;
            }

            $fileSize = File::size($user_media_upload . $filename);

            $eventData = array();

            if (!empty($model->original_filename)) {
                if (Storage::has($user_media_upload . $model->original_filename)) {
                    \Storage::delete($user_media_upload . $model->original_filename);
                }
                if (!empty($filename)) {
                    \Storage::move($user_media_upload . $filename, $user_media_upload . $model->original_filename);
                    $filename = $model->original_filename;
                    $eventData['updated']['original_filename'] = $filename;
                } else {
                    $eventData['removed']['original_filename'] = $model->original_filename;
                }
            } else {
                $eventData['added']['original_filename'] = $filename;
            }

            if (!empty($model->tablet_name)) {
                if (Storage::has($user_media_upload . $model->tablet_name)) {
                    \Storage::delete($user_media_upload . $model->tablet_name);
                }
                if (!empty($filename_tablet)) {
                    \Storage::move($user_media_upload . $filename_tablet, $user_media_upload . $model->tablet_name);
                    $filename_tablet = $model->tablet_name;
                    $eventData['updated']['tablet_name'] = $filename_tablet;
                } else {
                    $eventData['removed']['tablet_name'] = $model->tablet_name;
                }
            } else {
                $eventData['added']['tablet_name'] = $filename_tablet;
            }

            if (!empty($model->phone_name)) {
                if (Storage::has($user_media_upload . $model->phone_name)) {
                    \Storage::delete($user_media_upload . $model->phone_name);
                }
                if (!empty($filename_phone)) {
                    \Storage::move($user_media_upload . $filename_phone, $user_media_upload . $model->phone_name);
                    $filename_phone = $model->phone_name;
                    $eventData['updated']['phone_name'] = $filename_phone;
                } else {
                    $eventData['removed']['phone_name'] = $model->phone_name;
                }
            } else {
                $eventData['added']['phone_name'] = $filename_phone;
            }

            if (!empty($model->thumbnail)) {
                if (Storage::has($user_media_upload . $model->thumbnail)) {
                    \Storage::delete($user_media_upload . $model->thumbnail);
                }
                if (!empty($filename_thumbnail)) {
                    \Storage::move($user_media_upload . $filename_thumbnail, $user_media_upload . $model->thumbnail);
                    $filename_thumbnail = $model->thumbnail;
                    $eventData['updated']['thumbnail'] = $filename_thumbnail;
                } else {
                    $eventData['removed']['thumbnail'] = $model->thumbnail;
                }
            } else {
                $eventData['added']['thumbnail'] = $filename_thumbnail;
            }

            if (!empty($data['resize'])) {
                $resize[$data['resize']] = isset($aResize) ? $aResize : array();
            } else {
                $resize = NULL;
            }

            $newData = [
                'filename' => $data['filename'],
                'original_filename' => $filename,
                'tablet_name' => $filename_tablet,
                'phone_name' => $filename_phone,
                'thumbnail' => $filename_thumbnail,
                'filesize' => $fileSize,
                'type' => $mimeType,
                'caption' => $data['caption'],
                'credit' => $data['credit'],
                'description' => $data['description'],
                'alt' => $data['alt'],
                'weight' => $data['weight'],
                'css' => $data['css'],
                'anchor_link' => $data['anchor_link'],
                'resize' => serialize($resize)
            ];

            $model->update($newData);

            $evntData = [
                'media' => $model,
                'data' => $data
            ];
            \Event::fire('media.edited', [$evntData]);

            TagRepository::updateTagsWithDelimiter(',', $data['tags'], $model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA);

            /** Fire the file.uploaded event * */
            \Event::fire('file.uploaded', [$eventData]);

            self::updateFileAfterEvents($model);

            return response()->json([
                'jsonrpc' => '2.0',
                'target_name' => $filename,
                'id' => $id
            ]);
        } else {
            unlink($filePath);

            return response()->json([
                'jsonrpc' => 2.0,
                'error' => 'Oops something wrong.'
            ]);
        }
    }

    private static function updateFileAfterEvents($model) {
        $mediaPath = config('atlantis.user_media_upload');
        if (config('atlantis.use_amazon_s3') && config('atlantis.delete_local_file')) {
            $size = Storage::disk('s3')->size($mediaPath . $model->original_filename);
            $mimeType = $model->type;
        } else {

            $size = Storage::disk('local')->size($mediaPath . $model->original_filename);
            $mimeType = File::mimeType($mediaPath . $model->original_filename);
        }

        if ($model->filesize != $size || $model->type != $mimeType) {

            $model->update([
                'filesize' => $size,
                'type' => $mimeType
            ]);
        }
    }

    public static function editMedia($id, $data) {

        $model = Media::find($id);

        if ($model != NULL) {

            $newData = [
                'filename' => $data['filename'],
                'caption' => $data['caption'],
                'credit' => $data['credit'],
                'description' => $data['description'],
                'alt' => $data['alt'],
                'weight' => $data['weight'],
                'css' => $data['css'],
                'anchor_link' => $data['anchor_link']
            ];

            $model->update($newData);

            $evntData = [
                'media' => $model,
                'data' => $data
            ];
            \Event::fire('media.edited', [$evntData]);

            TagRepository::updateTagsWithDelimiter(',', $data['tags'], $model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA);
        }
    }

    /**
     * @param $id
     * @param $sTags
     */
    public static function addTagsToImages($id, $sTags)
    {
        TagRepository::addTagsWithDelimiter(',', $sTags, $id, \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA);
    }

    /**
     *
     * resize and save new image
     *
     * @param type $uploadPath
     * @param type $filename
     * @param type $sufix
     * @param type $width
     * @param type $height
     * @param $smartcrop crop data object. It may come from smartCrop or manualCrop
     * @param type bool $replaceSingleVersion. If replacing single vers remove suffix from original file name
     * @return string
     */
    public static function resizeImage($uploadPath, $filename, $sufix, $width, $height, $crop = FALSE, $cropData = FALSE, $replaceSingleVersion = FALSE) {

        // Diagnostic override for large image processing.
        $previousMemoryLimit = ini_get('memory_limit');
        $memoryLimitChanged = ini_set('memory_limit', '512M');

        error_log('ATLANTIS resizeImage START');
        error_log('ATLANTIS resizeImage file: ' . $uploadPath . $filename);
        error_log('ATLANTIS resizeImage target: ' . $width . 'x' . $height . ' suffix=' . $sufix);
        error_log('ATLANTIS resizeImage memory before: ' . $previousMemoryLimit);
        error_log('ATLANTIS resizeImage ini_set result: ' . var_export($memoryLimitChanged, TRUE));
        error_log('ATLANTIS resizeImage memory after: ' . ini_get('memory_limit'));
        error_log('ATLANTIS resizeImage usage before getimagesize: ' . memory_get_usage(TRUE));
        error_log('ATLANTIS resizeImage peak before getimagesize: ' . memory_get_peak_usage(TRUE));

        $imageSize = getimagesize($uploadPath . $filename);

        error_log('ATLANTIS resizeImage getimagesize PASSED: ' . $imageSize[0] . 'x' . $imageSize[1]);

        $fileSizeWidth = $imageSize[0];
        $fileSizeHeight = $imageSize[1];

        $name = pathinfo($uploadPath . $filename, PATHINFO_FILENAME);
        $ext = pathinfo($uploadPath . $filename, PATHINFO_EXTENSION);

        /*
        *remove _desktop suffix from the file. We always use _descktop version for recrop
        * TODO: We have to store both original_filename and _desktop name for media uploaded with
        * resizer.
        */
        if($replaceSingleVersion) {
            $name =  substr($name, 0 , -strlen('_desktop'));
        }

        $filenameWithSufix = $name . $sufix . '.' . $ext;

        error_log('ATLANTIS resizeImage about to Image::make');
        error_log('ATLANTIS resizeImage usage before Image::make: ' . memory_get_usage(TRUE));

        $file = Image::make($uploadPath . $filename);

        error_log('ATLANTIS resizeImage Image::make PASSED');
        error_log('ATLANTIS resizeImage usage after Image::make: ' . memory_get_usage(TRUE));
        error_log('ATLANTIS resizeImage peak after Image::make: ' . memory_get_peak_usage(TRUE));

        if (($cropData && $crop) || ($cropData && $replaceSingleVersion)) {
            error_log('ATLANTIS resizeImage using cropData path');
            $file->crop($cropData['width'], $cropData['height'], $cropData['x'], $cropData['y']);
            error_log('ATLANTIS resizeImage crop PASSED; about to resize');
            $file->resize($width, $height);
            error_log('ATLANTIS resizeImage resize PASSED');
        } else {
            error_log('ATLANTIS resizeImage using normal resize path');
            if (($width / $height) > ($fileSizeWidth / $fileSizeHeight)) {
                error_log('ATLANTIS resizeImage about to widen');
                $file->widen($width);
                error_log('ATLANTIS resizeImage widen PASSED');
            } else if (($width / $height) < ($fileSizeWidth / $fileSizeHeight)) {
                error_log('ATLANTIS resizeImage about to heighten');
                $file->heighten($height);
                error_log('ATLANTIS resizeImage heighten PASSED');
            } else {
                error_log('ATLANTIS resizeImage about to resize');
                $file->resize($width, $height);
                error_log('ATLANTIS resizeImage resize PASSED');
            }
            if ($crop) {
                error_log('ATLANTIS resizeImage about to crop');
                $file->crop($width, $height);
                error_log('ATLANTIS resizeImage crop PASSED');
            }
        }

        error_log('ATLANTIS resizeImage about to save: ' . $uploadPath . $filenameWithSufix);
        error_log('ATLANTIS resizeImage usage before save: ' . memory_get_usage(TRUE));
        error_log('ATLANTIS resizeImage peak before save: ' . memory_get_peak_usage(TRUE));

        $file->save($uploadPath . $filenameWithSufix);

        error_log('ATLANTIS resizeImage save PASSED');
        error_log('ATLANTIS resizeImage usage after save: ' . memory_get_usage(TRUE));
        error_log('ATLANTIS resizeImage peak after save: ' . memory_get_peak_usage(TRUE));
        error_log('ATLANTIS resizeImage END');

        return $filenameWithSufix;
    }


    public static function deleteMedia($id) {

        $model = Media::find($id);

        if ($model != NULL) {

            $user_media_upload = config('atlantis.user_media_upload');

            $old_original_filename = $model->original_filename;
            $old_tablet_name = $model->tablet_name;
            $old_phone_name = $model->phone_name;
            $old_thumbnail = $model->thumbnail;

            $original_file_path = $user_media_upload . $old_original_filename;
            $tablet_file_path = $user_media_upload . $old_tablet_name;
            $phone_file_path = $user_media_upload . $old_phone_name;
            $thumb_file_path = $user_media_upload . $old_thumbnail;

            \Event::fire('media.deleted', [$model]);
            $model->delete();

            TagRepository::deleteTag(\Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA, $id);

            //delete local files
            if (!empty($old_original_filename) && Storage::disk('local')->has($original_file_path)) {
                Storage::disk('local')->delete($original_file_path);
            }
            if (!empty($old_tablet_name) && Storage::disk('local')->has($tablet_file_path)) {
                Storage::disk('local')->delete($tablet_file_path);
            }
            if (!empty($old_phone_name) && Storage::disk('local')->has($phone_file_path)) {
                Storage::disk('local')->delete($phone_file_path);
            }
            if (!empty($old_thumbnail) && Storage::disk('local')->has($thumb_file_path)) {
                Storage::disk('local')->delete($thumb_file_path);
            }

            if (config('atlantis.use_amazon_s3')) {
                //delete amazon s3 files
                if (!empty($old_original_filename)) {
                    Storage::disk('s3')->delete($original_file_path);
                }
                if (!empty($old_tablet_name)) {
                    Storage::disk('s3')->delete($tablet_file_path);
                }
                if (!empty($old_phone_name)) {
                    Storage::disk('s3')->delete($phone_file_path);
                }
                if (!empty($old_thumbnail)) {
                    Storage::disk('s3')->delete($thumb_file_path);
                }
            }
        }
    }

    public static function getImagesByGallery($gallery_id) {

        $gallery = GalleryRepository::getGallery($gallery_id);

        if ($gallery != NULL) {

            $aImgsIds = array_filter(explode(',', $gallery->images));

            //$oImages = Media::whereIn('id', $aImgsIds)->orderByRaw("FIND_IN_SET('id','$gallery->images')")->get();

            if (empty($gallery->images)) {
                return array();
            }

            $oImages = DB::select(DB::raw("select * from media where id in ($gallery->images) order by find_in_set(id, '$gallery->images')"));

            return $oImages;
        } else {
            return NULL;
        }
    }

    public static function getImagesWhereIn($col, $values) {

        return Media::whereIn($col, $values)->get();
    }

    /**
     *
     * @param string $filename
     * @return Media
     */
    public static function findByName($filename) {

        return \Atlantis\Helpers\Cache\AtlantisCache::rememberQuery('mediaFindByName', [$filename], function() use ($filename) {

            return Media::where('filename', '=', $filename)
                ->orWhere('original_filename', '=', $filename)
                ->orWhere('tablet_name', '=', $filename)
                ->orWhere('phone_name', '=', $filename)
                ->first();
        });



    }

}
