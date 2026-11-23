<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class BlogController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }


    public function ckeditorUpload(Request $request)
{
    $request->validate([
        'upload' => ['required', 'image', 'max:8192'], // 8MB
    ]);

    $file = $request->file('upload');

    $dir = public_path('storage/uploads/blogs/editor');
    File::ensureDirectoryExists($dir, 0755, true);

    $img = $this->images->read($file->getRealPath());

    // Resize large images to avoid huge payload + huge files
    $maxWidth = 1600;
    if ($img->width() > $maxWidth) {
        $img = $img->scale(width: $maxWidth);
    }

    $name = 'blog_editor_' . uniqid() . '.webp';
    file_put_contents($dir . '/' . $name, (string) $img->toWebp(82));

    $publicPath = "storage/uploads/blogs/editor/$name";

    return response()->json([
        'uploaded' => 1,
        'fileName' => $name,
        'url'      => asset($publicPath),
    ]);
}

    /**
     * Store editor images in: public/storage/uploads/blogs/editor/
     * Return path like: storage/uploads/blogs/editor/xxx.webp
     */
    protected function processEditorImage(UploadedFile $file, int $maxWidth = 1600): string
    {
        $dir = public_path('storage/uploads/blogs/editor');
        File::ensureDirectoryExists($dir, 0755, true);

        $img = $this->images->read($file->getRealPath());

        // Resize only if wider than maxWidth (keep aspect ratio)
        if ($img->width() > $maxWidth) {
            $img = $img->scale(width: $maxWidth);
        }

        // Always save as WEBP to keep it small
        $name = "blog_editor_" . uniqid() . ".webp";
        file_put_contents($dir . '/' . $name, (string) $img->toWebp(82));

        return "storage/uploads/blogs/editor/$name";
    }

    public function index(Request $request)
    {
        if ($request->has('datatable')) {
            return $this->datatable();
        }

        $categories = BlogCategory::orderBy('order')->get();
        return view('admin.blogs.index', compact('categories'));
    }

    public function datatable()
    {
        $q = Blog::with(['translations', 'category.translations'])
            ->orderBy('order');

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('thumb', function ($b) {
                return $b->thumb_small
                    ? '<img src="' . media_url($b->thumb_small) . '" style="height:40px;">'
                    : '';
            })
            ->addColumn('title', fn($b) => $b->t()?->title)
            ->addColumn('category', fn($b) => $b->category?->t()?->title)
            ->addColumn('actions', fn($b) => view('admin.blogs._actions', ['b' => $b])->render())
            ->rawColumns(['thumb', 'actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_category_id'     => ['required', 'exists:blog_categories,id'],
            'translations.*.title' => ['required'],
            'image'                => ['required', 'image', 'max:8192'],
        ]);

        $thumbSmall = $this->processImage($request->file('image'), 416, 300);
        $thumbLarge = $this->processImage($request->file('image'), 856, 490);

        $defaultLocale = app()->getFallbackLocale();
        $slug = Str::slug($request->translations[$defaultLocale]['title']);

        $blog = Blog::create([
            'blog_category_id' => $request->blog_category_id,
            'slug'             => $slug,
            'thumb_small'      => $thumbSmall,
            'thumb_large'      => $thumbLarge,
            'status'           => $request->status ?? 1,
            'order'            => $request->order ?? 0,
        ]);

        foreach ($request->translations as $loc => $t) {
            $blog->translations()->create([
                'locale'  => $loc,
                'title'   => $t['title'] ?? null,
                'excerpt' => $t['excerpt'] ?? null,
                'content' => $t['content'] ?? null,
            ]);
        }

        return response()->json(['message' => 'Blog created']);
    }

    public function edit(Blog $blog)
    {
        if (request()->ajax()) {
            $blog->load(['translations']);
            return response()->json([
                'id'         => $blog->id,
                'blog_category_id' => $blog->blog_category_id,
                'status'     => $blog->status,
                'order'      => $blog->order,
                'thumb_small' => $blog->thumb_small,
                'translations' => $blog->translations->map(fn($t) => [
                    'locale'  => $t->locale,
                    'title'   => $t->title,
                    'excerpt' => $t->excerpt,
                    'content' => $t->content,
                ]),
            ]);
        }

        $categories = BlogCategory::orderBy('order')->get();
        return view('admin.blogs.form', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'blog_category_id'     => ['required', 'exists:blog_categories,id'],
            'translations.*.title' => ['required'],
            'image'                => ['nullable', 'image', 'max:8192'],
        ]);

        if ($request->file('image')) {
            $this->deleteFile($blog->thumb_small);
            $this->deleteFile($blog->thumb_large);

            $blog->thumb_small = $this->processImage($request->file('image'), 416, 300);
            $blog->thumb_large = $this->processImage($request->file('image'), 856, 490);
        }

        $blog->blog_category_id = $request->blog_category_id;
        $blog->status           = $request->status ?? 1;
        $blog->order            = $request->order ?? 0;
        $blog->save();

        foreach ($request->translations as $loc => $t) {
            $blog->translations()->updateOrCreate(
                ['locale' => $loc],
                [
                    'title'   => $t['title'] ?? null,
                    'excerpt' => $t['excerpt'] ?? null,
                    'content' => $t['content'] ?? null,
                ]
            );
        }

        return response()->json(['message' => 'Blog updated']);
    }

    public function destroy(Blog $blog)
    {
        $this->deleteFile($blog->thumb_small);
        $this->deleteFile($blog->thumb_large);

        $blog->translations()->delete();
        $blog->delete();

        return response()->json(['message' => 'Blog deleted']);
    }

    /*───────────────────────────────────────────────
     | New Image System (public/uploads/blogs)
     ───────────────────────────────────────────────*/

    protected function processImage(\Illuminate\Http\UploadedFile $file, $w, $h): string
{
    $dir = public_path('storage/uploads/blogs');
    File::ensureDirectoryExists($dir, 0755, true);

    $origSize = $file->getSize();
    $ext = strtolower($file->getClientOriginalExtension());

    $img = $this->images->read($file->getRealPath())->cover($w, $h);

    // Larger than 1MB → compress to WEBP
    if ($origSize > 1_000_000) {
        $name = "blog_" . uniqid() . "_{$w}x{$h}.webp";
        file_put_contents($dir . '/' . $name, (string) $img->toWebp(80));
        return "uploads/blogs/$name"; // ✅ public URL path
    }

    // Small image → keep original format
    $newExt = in_array($ext, ['jpg','jpeg','png','webp']) ? $ext : 'jpg';
    $name = "blog_" . uniqid() . "_{$w}x{$h}." . $newExt;
    $abs  = $dir . '/' . $name;

    switch ($newExt) {
        case 'png':
            file_put_contents($abs, (string) $img->toPng());
            break;
        case 'webp':
            file_put_contents($abs, (string) $img->toWebp(90));
            break;
        default:
            file_put_contents($abs, (string) $img->toJpeg(90));
    }

    return "uploads/blogs/$name"; // ✅ public URL path
}

    protected function deleteFile(?string $path)
{
    if (!$path) return;
    $full = public_path($path); // ✅ will resolve /public/uploads/blogs/...
    if (is_file($full)) @unlink($full);
}
}
