<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Google\Client;
use Google\Service\Drive;

class UploadGoogleDriveController extends Controller
{
    public function allFiles($folder)
    { {
            $all_files_folder = Storage::disk('google')->allFiles($folder);

            return (compact('all_files_folder'));
        }
    }

    public function allFolder($folder)
    { {
            $all_files_folder = Storage::disk('google')->allDirectories($folder);

            return (compact('all_files_folder'));
        }
    }

    public function detailFiles($folder)
    { {
            $details = Storage::disk("google")->getMetadata($folder);

            return $details;
        }
    }

    public function detailFolder($folder)
    { {
            $details = Storage::disk("google")->getMetadata($folder);
            // dump($details);

            return $details;
        }
    }

    public function downloadFiles($folder, $file)
    { {
            $url = Storage::disk("google")->url($folder . '/' . $file);
            return $url;

            // $data = Storage::disk("google")->get($file);

            // $headers = array(
            //     'Content-Type: application/pdf',
            // );

            // return response($data)->withHeaders([
            //     'Content-Type' => 'application/pdf',
            // ]);
        }
    }

    public function store($file, $nama_file, $folder)
    {
        $response = Storage::disk('google')->putFileAs($folder, $file, $nama_file);
        // Detail File
        // $details = Storage::disk("google")->getMetadata($folder . '/' . $nama_file);

        // $response = Storage::disk('google')->put($nama_file, file_get_contents($request->file));
        // Url view
        $url = Storage::disk("google")->url($folder . '/' . $nama_file);
        return $url;
    }

    public function storefolder($parent, $folder)
    { {
            $create_folder = Storage::disk('google')->makeDirectory($parent . '/' . $folder);
            $url = Storage::disk("google")->url($parent . '/' . $folder);
            // Show List Direktori Parent
            $show_list_directory = Storage::disk('google')->directories($parent);
            $ex = explode('folders/', $url);
            $id_folder_baru = $ex[1];
            // dump($parent);
            // dump($folder);
            // dump($url);
            // dump($create_folder);
            // dump($show_list_directory);
            // dump($ex);

            return (compact('id_folder_baru', 'url'));
        }
    }

    public function rename($parent, $id_lama, $newFolder)
    { {
            $rename = Storage::disk('google')->rename($parent . '/' . $id_lama, $parent . '/' . $newFolder);

            return $rename;
        }
    }

    public function destroy($folder, $file)
    {
        $delete = Storage::disk('google')->delete($folder . '/' . $file);

        // $url = Storage::disk("google")->url($folder . '/' . $nama_file);
        return $delete;
    }

    public function destroyFile($file)
    {
        $delete = Storage::disk('google')->delete($file);

        // $url = Storage::disk("google")->url($folder . '/' . $nama_file);
        return $delete;
    }

    public function destroyFolder($folder)
    {
        $delete = Storage::disk('google')->deleteDirectory($folder);

        return $delete;
    }

    public function view($folder, $nama_file)
    {
        $url = Storage::disk("google")->url($folder . '/' . $nama_file);
        return $url;
    }
}
