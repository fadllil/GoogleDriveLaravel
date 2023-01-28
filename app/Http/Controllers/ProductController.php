<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $uploadGoogleDriveController;

    public function __construct(UploadGoogleDriveController $uploadGoogleDriveController)
    {
        $this->uploadGoogleDriveController = $uploadGoogleDriveController;
    }

    public function index()
    {
        $product = Product::get();

        return view('index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->file->extension());

        $nama_file = $request->nama . '.' . $request->file->extension();
        // dd($nama_file);
        // Proses Upload File Gdrive
        $folder_upload_drive = '1NkzAVRecOA6wxIGEtJ_5Qh5BPgRFqghA';
        // $upload_file = $this->uploadGoogleDriveController->store($request->foto_product[$loopFoto], $nama_file, $folder_upload_drive);

        $response = Storage::disk('google')->putFileAs($folder_upload_drive, $request->file, $nama_file);

        // dd($response);

        // Detail File
        $details = Storage::disk("google")->getMetadata($folder_upload_drive . '/' . $nama_file);
        // dd($details['path']);

        $file_id = explode('/', $details['path']);

        // dd($file_id);

        // $response = Storage::disk('google')->put($nama_file, file_get_contents($request->file));
        // Url view
        $url = Storage::disk("google")->url($folder_upload_drive . '/' . $nama_file);


        Product::create([
            'file_id' => $file_id[1],
            'nama_file' => $request->nama,
            'url_file' => $url
        ]);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        return view('edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $delete = Storage::disk('google')->delete($product->file_id);

        $folder_upload_drive = '1NkzAVRecOA6wxIGEtJ_5Qh5BPgRFqghA';
        $nama_file = $request->nama . '.' . $request->file->extension();

        $response = Storage::disk('google')->putFileAs($folder_upload_drive, $request->file, $nama_file);

        $details = Storage::disk("google")->getMetadata($folder_upload_drive . '/' . $nama_file);

        $file_id = explode('/', $details['path']);

        $url = Storage::disk("google")->url($folder_upload_drive . '/' . $nama_file);

        $product->update([
            'file_id' => $file_id[1],
            'nama_file' => $request->nama,
            'url_file' => $url
        ]);

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        $delete = Storage::disk('google')->delete($product->file_id);

        $product->delete();

        return redirect('/');
    }
}
