<?php

namespace App\Traits;

use GrahamCampbell\ResultType\Success;

trait imageUploade{

    public function uploadeImage($request, $fileNameInRequest, $path)
    {
        $response  = [
            'success' => false,
            'imageUrl' => null,
            'message' => null,
        ];
        try{    
            if($request->hasFile($fileNameInRequest)){
                $request->photo->store($path);
                $response['imageUrl'] = asset('storage/'.$path.'/'.$request->photo->hashName());
                $response['success'] = true;
                $response['message'] = 'Photo stored Successfully';
                return $response;
            }

        }catch(\Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();

            return $response;
        }
    }

}