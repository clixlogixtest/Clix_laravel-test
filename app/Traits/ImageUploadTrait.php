<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait ImageUploadTrait
{
	public function saveImage(Request $request, $path = null, $old_image = null)
	{
		if (! file_exists(public_path('uploads'))) 
		{
            mkdir(public_path('uploads'), 0777);
        }

		if($request->hasFile('image'))
        {
            // Get file from request
            $file = $request->file('image');

            // Get filename with extension
            $filenameWithExt = $file->getClientOriginalName();

            // Get file name
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Remove unwanted characters
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);

            // Get the original image extension
            $extension = $file->getClientOriginalExtension();

            // Create unique file name
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            $destinationPath = public_path() . '/uploads/' . $path . '/';
            $file->move($destinationPath, $fileNameToStore);

            if($old_image != null) 
            {
                @unlink(public_path().'/uploads/'. $path . '/' .$old_image);
            }
        }
        else
        {
            $fileNameToStore=$old_image;
        }

        return $fileNameToStore;
	}

    public function unlinkImage($path, $old_image = null)
    {
        if($old_image != null) 
        {
            @unlink(public_path().'/uploads/'. $path . '/' .$old_image);
        }
    }
}