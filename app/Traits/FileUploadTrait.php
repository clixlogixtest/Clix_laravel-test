<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait FileUploadTrait
{
	public function saveFile(Request $request, $path = null, $old_file = null)
	{
		if (! file_exists(public_path('uploads'))) 
		{
            mkdir(public_path('uploads'), 0777);
        }

		if($request->hasFile('file'))
        {
            // Get file from request
            $file = $request->file('file');

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

            if($old_file != null) 
            {
                @unlink(public_path().'/uploads/'. $path . '/' .$old_file);
            }
        }
        else
        {
            $fileNameToStore=$old_file;
        }

        return $fileNameToStore;
	}

    public function unlinkFile($path, $old_file = null)
    {
        if($old_file != null) 
        {
            @unlink(public_path().'/uploads/'. $path . '/' .$old_file);
        }
    }
}