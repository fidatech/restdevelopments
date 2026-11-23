<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectAmenity;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProjectAmenityController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function index(Request $request)
    {
        if ($request->datatable) {
            return $this->datatable();
        }

        return view('admin.amenities.index');
    }

    public function datatable()
    {
        $q = ProjectAmenity::orderBy('order');

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('title', fn($a) => $a->t()?->title)
            ->addColumn('image', fn($a) =>
                '<img src="'.media_url($a->image).'" height="50">'
            )
            ->addColumn('icon', fn($a) =>
                '<img src="'.media_url($a->icon).'" height="30" style="border-radius:6px;background:#000;margin-top:7px;">'
            )
            ->addColumn('actions', fn($a) =>
                view('admin.amenities._actions', ['a'=>$a])->render()
            )
            ->rawColumns(['image','icon','actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required','image','max:8192'],
            'icon'  => ['required','file','max:8192'],
            'title' => ['required','array'],
        ]);

        $imgPath  = $this->processImage($request->file('image'), 'uploads/amenities', 196, 240);
        $iconPath = $this->processIcon($request->file('icon'));

        $a = ProjectAmenity::create([
            'image'  => $imgPath,
            'icon'   => $iconPath,
            'order'  => $request->order ?? 0,
            'status' => true,
        ]);

        foreach ($request->title as $loc => $title) {
            $a->translations()->create(['locale'=>$loc, 'title'=>$title]);
        }

        return response()->json(['message'=>'Amenity saved']);
    }

    public function update(Request $request, ProjectAmenity $amenity)
    {
        $request->validate(['title'=>['required','array']]);

        if ($request->file('image')) {
            $this->deleteFile($amenity->image);
            $amenity->image = $this->processImage($request->file('image'),'uploads/amenities',196,240);
        }

        if ($request->file('icon')) {
            $this->deleteFile($amenity->icon);
            $amenity->icon = $this->processIcon($request->file('icon'));
        }

        $amenity->order = $request->order ?? $amenity->order;
        $amenity->save();

        foreach ($request->title as $loc=>$title) {
            $amenity->translations()->updateOrCreate(['locale'=>$loc], ['title'=>$title]);
        }

        return response()->json(['message'=>'Amenity updated']);
    }

    public function destroy(ProjectAmenity $amenity)
    {
        $this->deleteFile($amenity->image);
        $this->deleteFile($amenity->icon);

        $amenity->translations()->delete();
        $amenity->delete();

        return response()->json(['message'=>'Amenity deleted']);
    }

    /*───────────────────────────────────────────────
     | Updated Image System
     | - Images >1MB → WEBP (80)
     | - Images ≤1MB → original ext (90)
     | - Amenities resized with cover()
     ───────────────────────────────────────────────*/

    protected function processImage(\Illuminate\Http\UploadedFile $file, string $dir, int $width, int $height): string
    {
        $absDir = public_path($dir);
        File::ensureDirectoryExists($absDir, 0755, true);

        $origSize = $file->getSize();
        $origExt  = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath())->cover($width, $height);

        if ($origSize > 1_000_000) {
            $name = "amenity_" . uniqid() . "_{$width}x{$height}.webp";
            file_put_contents($absDir.'/'.$name, (string) $img->toWebp(80));
            return "$dir/$name";
        }

        $ext  = in_array($origExt, ['jpg','jpeg','png','webp']) ? $origExt : 'jpg';
        $name = "amenity_" . uniqid() . "_{$width}x{$height}.$ext";
        $abs  = $absDir.'/'.$name;

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

        return "$dir/$name";
    }

    /*───────────────────────────────────────────────
     | ICON handling — NO compression logic
     | Icons are usually SVG/PNG → store as-is
     ───────────────────────────────────────────────*/

    protected function processIcon(\Illuminate\Http\UploadedFile $file): string
    {
        $dir = public_path('storage/uploads/amenities');
        File::ensureDirectoryExists($dir, 0755, true);

        $origExt = strtolower($file->getClientOriginalExtension());
        $ext     = in_array($origExt, ['png','jpg','jpeg','svg','webp']) ? $origExt : 'png';

        $name = 'amenity_icon_' . uniqid() . '.' . $ext;
        $file->move($dir, $name);

        return "uploads/amenities/$name";
    }

    protected function deleteFile(?string $path): void
    {
        if ($path && is_file(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
