<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\VacancyTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Vacancy::with('translations')->select('vacancies.*');

            return datatables()->of($data)
                ->addColumn('title', function ($row) {
                    $translation = $row->translation('en');
                    return $translation ? $translation->title : '-';
                })
                ->addColumn('deadline', function ($row) {
                    // deadline is automatically cast as date if set in the model
                    return $row->deadline ? $row->deadline->format('Y-m-d') : '-';
                })
                ->addColumn('department', function ($row) {
                    $translation = $row->translation('en');
                    return $translation ? $translation->department : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary" onclick="editVacancy(' . $row->id . ')">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteVacancy(' . $row->id . ')">Delete</button>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.vacancies.index');
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $vacancy = Vacancy::create([
                'deadline' => $request->deadline,
                'salary'   => $request->salary,
                'status'   => $request->status == 'on' ? 'active' : 'inactive',
                'slug' => Str::slug($request->input('title.en')),
            ]);

            foreach (['en', 'fa', 'ps', 'uz'] as $locale) {
                if (!$request->input("title.$locale")) continue;

                VacancyTranslation::create([
                    'vacancy_id'       => $vacancy->id,
                    'locale'           => $locale,
                    'title'            => $request->input("title.$locale"),
                    'department'       => $request->input("department.$locale"),
                    'location'         => $request->input("location.$locale"),
                    'type'             => $request->input("type.$locale"),
                    'description'      => $request->input("description.$locale"),
                    'requirements'     => $request->input("requirements.$locale"),
                    'responsibilities' => $request->input("responsibilities.$locale"),
                ]);
            }
        });

        return response()->json(['success' => true]);
    }

    public function edit(Vacancy $vacancy)
    {
        $vacancy->load('translations');
        return response()->json($vacancy);
    }

    public function update(Request $request, Vacancy $vacancy)
    {
        DB::transaction(function () use ($request, $vacancy) {
            $vacancy->update([
                'deadline' => $request->deadline,
                'salary'   => $request->salary,
                'status'   => $request->status == 'on' ? 'active' : 'inactive',
                'slug' => Str::slug($request->input('title.en')),
            ]);

            foreach (['en', 'fa', 'ps', 'uz'] as $locale) {
                if (!$request->input("title.$locale")) continue;

                $vacancy->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title'            => $request->input("title.$locale"),
                        'department'       => $request->input("department.$locale"),
                        'location'         => $request->input("location.$locale"),
                        'type'             => $request->input("type.$locale"),
                        'description'      => $request->input("description.$locale"),
                        'requirements'     => $request->input("requirements.$locale"),
                        'responsibilities' => $request->input("responsibilities.$locale"),
                    ]
                );
            }
        });

        return response()->json(['success' => true]);
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return response()->json(['success' => true]);
    }
}
