<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Yajra\DataTables\Facades\DataTables;

class ProjectDocumentController extends Controller
{
    protected ImageManager $images;

    public function __construct()
    {
        $this->images = new ImageManager(new Driver());
    }


    public function index(Project $project)
    {
        return view('admin.project-documents.index', compact('project'));
    }


    public function datatable(Project $project)
    {
        $q = ProjectDocument::where('project_id', $project->id)->orderBy('order');

        return DataTables::of($q)
            ->addIndexColumn()
            ->addColumn('preview', function (ProjectDocument $d) {
                $img = $d->preview_image
                    ? asset($d->preview_image)
                    : asset('assets/img/pdf-placeholder.webp');

                return '<img src="' . $img . '" class="img-fluid rounded" style="max-width:120px">';
            })
            ->addColumn('title', fn($d) => e($d->title ?? '—'))
            ->addColumn('type', fn($d) => ucwords(str_replace('_', ' ', $d->type)))
            ->addColumn('file', fn($d) =>
                '<a href="' . asset($d->file) . '" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-file-pdf me-1"></i> View PDF
                 </a>'
            )
            ->addColumn('order', fn($d) => $d->order)
            ->addColumn('actions', function ($d) use ($project) {
                return view('admin.project-documents._actions', [
                    'd'       => $d,
                    'editUrl' => route('admin.projects.documents.update', [$project->id, $d->id]),
                    'deleteUrl' => route('admin.projects.documents.destroy', [$project->id, $d->id]),
                ])->render();
            })
            ->rawColumns(['preview','file','actions'])
            ->make(true);
    }


    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'type'          => ['required','in:master_plan,floor_plan,brochure'],
            'title'         => ['nullable','string','max:255'],
            'file'          => ['required','file','mimes:pdf','max:40960'],
            'preview_image' => ['nullable','image','max:8192'],
            'order'         => ['nullable','integer','min:0'],
        ]);

        // -------------------------
        // PDF STORE (public_path)
        // -------------------------
        $pdfDir = "storage/uploads/projects/{$project->id}/documents";
        $this->ensureDir($pdfDir);

        $pdfName = "doc_" . uniqid() . ".pdf";
        $request->file('file')->move(public_path($pdfDir), $pdfName);

        $pdfPath = "$pdfDir/$pdfName";

        // preview image (optional)
        $preview = null;
        if ($request->hasFile('preview_image')) {
            $preview = $this->processPreview($request->file('preview_image'));
        }

        ProjectDocument::create([
            'project_id'    => $project->id,
            'type'          => $validated['type'],
            'title'         => $validated['title'] ?? null,
            'file'          => $pdfPath,
            'preview_image' => $preview,
            'order'         => $validated['order'] ?? 0,
        ]);

        return response()->json(['message' => 'Document added successfully.']);
    }


    public function update(Request $request, Project $project, ProjectDocument $document)
    {
        abort_unless($document->project_id === $project->id, 404);

        $validated = $request->validate([
            'type'          => ['required','in:master_plan,floor_plan,brochure'],
            'title'         => ['nullable','string','max:255'],
            'file'          => ['nullable','file','mimes:pdf','max:40960'],
            'preview_image' => ['nullable','image','max:8192'],
            'order'         => ['nullable','integer','min:0'],
        ]);

        // Replace PDF
        if ($request->hasFile('file')) {
            $this->deleteFile($document->file);

            $pdfDir = "storage/uploads/projects/{$project->id}/documents";
            $this->ensureDir($pdfDir);

            $pdfName = "doc_" . uniqid() . ".pdf";
            $request->file('file')->move(public_path($pdfDir), $pdfName);

            $document->file = "$pdfDir/$pdfName";
        }

        // Replace preview image
        if ($request->hasFile('preview_image')) {
            $this->deleteFile($document->preview_image);
            $document->preview_image = $this->processPreview($request->file('preview_image'));
        }

        $document->type  = $validated['type'];
        $document->title = $validated['title'] ?? null;
        $document->order = $validated['order'] ?? $document->order;
        $document->save();

        return response()->json(['message' => 'Document updated successfully.']);
    }


    public function destroy(Project $project, ProjectDocument $document)
    {
        abort_unless($document->project_id === $project->id, 404);

        $this->deleteFile($document->file);
        $this->deleteFile($document->preview_image);

        $document->delete();

        return response()->json(['message' => 'Document deleted successfully.']);
    }

    /*-----------------------------------------------------
     | Preview Image — WEBP if >1MB, else keep extension
     | Resize: 800x440 cover crop
     -----------------------------------------------------*/
    protected function processPreview(\Illuminate\Http\UploadedFile $file): string
    {
        $dir = "storage/uploads/projects/documents";
        $this->ensureDir($dir);

        $origSize = $file->getSize();
        $origExt  = strtolower($file->getClientOriginalExtension());

        $img = $this->images->read($file->getRealPath())->cover(800, 440);

        // If >1MB compress to WEBP
        if ($origSize > 1_000_000) {
            $name = "preview_" . uniqid() . ".webp";
            file_put_contents(public_path("$dir/$name"), (string) $img->toWebp(72));
            return "$dir/$name";
        }

        // Else keep original extension
        $ext = in_array($origExt, ['jpg','jpeg','png','webp']) ? $origExt : 'jpg';
        $name = "preview_" . uniqid() . '.' . $ext;

        $full = public_path("$dir/$name");

        switch ($ext) {
            case 'png':
                file_put_contents($full, (string) $img->toPng());
                break;
            case 'webp':
                file_put_contents($full, (string) $img->toWebp(85));
                break;
            default:
                file_put_contents($full, (string) $img->toJpeg(88));
        }

        return "$dir/$name";
    }


    /*-----------------------------------------------------
     | Ensure directory exists
     -----------------------------------------------------*/
    protected function ensureDir(string $dir)
    {
        $path = public_path($dir);
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    /*-----------------------------------------------------
     | Delete file safely
     -----------------------------------------------------*/
    protected function deleteFile(?string $path)
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
