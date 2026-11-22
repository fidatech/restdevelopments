<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class ClientController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->datatable();
        }

        $clients = Client::all();
        return view('admin.clients.index', compact('clients'));
    }

    protected function datatable()
    {
        $query = Client::orderBy('order');

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('image', function ($c) {
                return '<img src="'.asset($c->image).'" height="50">';
            })
            ->addColumn('actions', function ($c) {
                return '
                    <a href="'.route('admin.clients.edit', $c).'" class="btn btn-sm btn-warning">Edit</a>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$c->id.'">Delete</button>
                ';
            })
            ->rawColumns(['image','actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.clients.form', ['client' => new Client()]);
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', compact('client'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'link'  => 'nullable|url',
            'order' => 'nullable|integer',
        ]);

        $client = new Client();
        $client->link  = $request->link;
        $client->order = $request->order ?? 0;

        if ($request->file('image')) {
            $client->image = $this->processImage($request->file('image'));
        }

        $client->save();

        return $request->ajax()
            ? response()->json(['success' => true])
            : redirect()->route('admin.clients.index')->with('success','Saved');
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'image' => 'nullable|image',
            'link'  => 'nullable|url',
            'order' => 'nullable|integer',
        ]);

        if ($request->file('image')) {
            if ($client->image && is_file(public_path($client->image))) {
                @unlink(public_path($client->image));
            }

            $client->image = $this->processImage($request->file('image'));
        }

        $client->link  = $request->link;
        $client->order = $request->order ?? 0;
        $client->save();

        return $request->ajax()
            ? response()->json(['success' => true])
            : redirect()->route('admin.clients.index')->with('success','Updated');
    }

    public function destroy(Client $client)
    {
        if ($client->image && is_file(public_path($client->image))) {
            @unlink(public_path($client->image));
        }

        $client->delete();
        return response()->json(['success' => true]);
    }

    /*───────────────────────────────────────────────
     | Updated Image Logic (public/uploads/clients)
     | - If >1 MB → compress to WEBP (80)
     | - If ≤1 MB → keep original format (90)
     ───────────────────────────────────────────────*/

    protected function processImage(\Illuminate\Http\UploadedFile $file): string
    {
        $dir = public_path('storage/uploads/clients');
        File::ensureDirectoryExists($dir, 0755, true);

        $origSize = $file->getSize(); // bytes
        $origExt  = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath())->cover(200, 200);

        // LARGE IMAGE → compress to WEBP
        if ($origSize > 1_000_000) {
            $name = 'client_'.uniqid().'_200x200.webp';
            file_put_contents($dir.'/'.$name, (string) $img->toWebp(80));
            return 'uploads/clients/'.$name;
        }

        // SMALL IMAGE → keep safe extension
        $ext = in_array($origExt, ['jpg','jpeg','png','webp']) ? $origExt : 'jpg';
        $name = 'client_'.uniqid().'_200x200.'.$ext;
        $abs  = $dir.'/'.$name;

        switch ($ext) {
            case 'png':
                file_put_contents($abs, (string) $img->toPng());
                break;
            case 'webp':
                file_put_contents($abs, (string) $img->toWebp(90));
                break;
            default:
                file_put_contents($abs, (string) $img->toJpeg(90));
        }

        return 'uploads/clients/'.$name;
    }
}
