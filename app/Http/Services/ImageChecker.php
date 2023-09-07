<?php 
namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * 
 */
trait ImageChecker
{

    function uploadFile($fileString, $uploadDir=null)
    {
        try {

            if(count(explode(',', $fileString))<2)
                throw new Exception("Only supported base64String To Upload File!", 403);
                
            $encoded_string = explode(',', $fileString)[1];
            $imgdata        = base64_decode($encoded_string);
            $fileName       = $this->generateRandomString(5) . uniqid() . time() . '.jpg';
            $parentDir      = 'storage';

            if(!$uploadDir){
                $uploadDir = 'images/';
            }

            Storage::disk('public')->put($uploadDir. $fileName, $imgdata);

            return [ 'success'   => true, 'fileLocation'  => $parentDir.'/'. $uploadDir.$fileName ];

        } catch (\Exception $th) {
            return [
                'success'   => false,
                'msg'       => $th->getMessage(),
                'code'      => $th->getCode()
            ];
        }
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function deleteImage($oldPath=null, $disk = 'public'){

        if($oldPath && $disk){

            $formatTocheckLocation = preg_replace("/storage/im", "", $oldPath);
            if (Storage::disk($disk)->exists($formatTocheckLocation)) {
                Storage::disk($disk)->delete($formatTocheckLocation);
                
                return true;
            }

        }

        return false;
    }

    private function deleteGalleryImage($oldPath=null){
        if($oldPath){
            File::delete(public_path($oldPath));
            return true;
        }

        return false;
    }


    function galleryImageUploader($fileString, $uploadDir = null)
    {
        try {

            if(!isset($fileString->Base64))
                throw new Exception("Invalid File format!", 403);

            $imgdata = base64_decode($fileString->Base64);
            if(!$imgdata)
                throw new Exception("Only supported base64String To Upload File!", 403);

            $originName     = ($fileString->FileName) ?? null;
            $fileName       = uniqid() . time() . '.jpg';
            $parentDir      = 'storage';

            if (!$uploadDir) {
                $uploadDir = 'gallery/';
            }

            Storage::disk('public')->put($uploadDir . $fileName, $imgdata);

            return [
                'success'   => true,
                'msg'       => 'ok',
                'fileLocation'  => $parentDir . '/' . $uploadDir . $fileName
            ];

        } catch (\Exception $th) {
            return [
                'success'   => false,
                'msg'       => $th->getMessage(),
                'code'      => $th->getCode()
            ];
        }
    }


}
