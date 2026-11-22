<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class TeamController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }

    public function index()
    {
        $teams = Team::orderBy('order')->get();
        return view('admin.team.index', compact('teams'));
    }

    public function create()
    {
        $locales = config('app.locales', ['en', 'ps', 'fa', 'uz']);
        return view('admin.team.form', [
            'team'    => new Team(),
            'locales' => $locales
        ]);
    }

    public function store(Request $request)
    {
        $team = new Team();
        $team->phone     = $request->phone;
        $team->facebook  = $request->facebook;
        $team->twitter   = $request->twitter;
        $team->linkedin  = $request->linkedin;
        $team->instagram = $request->instagram;
        $team->order     = $request->order;

        if ($request->file('image')) {
            $team->image = $this->saveTeamImage($request->file('image'));
        }

        $team->save();

        foreach (config('app.locales', ['en', 'ps', 'fa', 'uz']) as $loc) {
            $team->translations()->create([
                'locale'   => $loc,
                'name'     => $request->input("translations.$loc.name"),
                'position' => $request->input("translations.$loc.position"),
            ]);
        }

        return redirect()->route('admin.team.index')->with('success', 'Created');
    }

    public function edit(Team $team)
    {
        $locales = config('app.locales', ['en', 'ps', 'fa', 'uz']);
        return view('admin.team.form', compact('team', 'locales'));
    }

    public function update(Request $request, Team $team)
    {
        $team->phone     = $request->phone;
        $team->facebook  = $request->facebook;
        $team->twitter   = $request->twitter;
        $team->linkedin  = $request->linkedin;
        $team->instagram = $request->instagram;
        $team->order     = $request->order;

        if ($request->file('image')) {
            if ($team->image && file_exists(public_path($team->image))) {
                unlink(public_path($team->image));
            }

            $team->image = $this->saveTeamImage($request->file('image'));
        }

        $team->save();

        foreach (config('app.locales', ['en', 'ps', 'fa', 'uz']) as $loc) {
            $team->translations()->updateOrCreate(
                ['locale' => $loc],
                [
                    'name'     => $request->input("translations.$loc.name"),
                    'position' => $request->input("translations.$loc.position"),
                ]
            );
        }

        return redirect()->route('admin.team.index')->with('success', 'Updated');
    }

    public function destroy(Team $team)
    {
        if ($team->image && file_exists(public_path($team->image))) {
            unlink(public_path($team->image));
        }

        $team->translations()->delete();
        $team->delete();

        return redirect()->back()->with('success', 'Deleted');
    }


    public function publicIndex()
    {
        $team = Team::with('translations')->orderBy('order')->get();
        return view('pages.team', compact('team'));
    }

    // =====================================================================
    //  IMAGE HANDLER — PUBLIC_PATH + 1MB RULE + KEEP ORIGINAL EXT < 1MB
    // =====================================================================
    protected function saveTeamImage(\Illuminate\Http\UploadedFile $file): string
    {
        $dir = public_path('storage/uploads/teams');

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $originalSize = $file->getSize();
        $ext = strtolower($file->getClientOriginalExtension());

        // process image (resize & crop)
        $img = $this->images->read($file->getRealPath())->cover(416, 550);

        // --------------------------------------------
        // SMALL (<1MB) → KEEP ORIGINAL EXTENSION
        // --------------------------------------------
        if ($originalSize < 1_000_000) {
            $ext = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';
            $filename = 'team_' . uniqid() . "_416x550.$ext";

            if ($ext === 'png') {
                $binary = (string) $img->toPng();
            } elseif ($ext === 'webp') {
                $binary = (string) $img->toWebp(90);
            } else {
                $binary = (string) $img->toJpeg(90);
            }

        } else {
            // --------------------------------------------
            // BIG (>=1MB) → CONVERT TO WEBP 80
            // --------------------------------------------
            $filename = 'team_' . uniqid() . "_416x550.webp";
            $binary = (string) $img->toWebp(80);
        }

        file_put_contents("$dir/$filename", $binary);

        return "uploads/teams/$filename";
    }
}
