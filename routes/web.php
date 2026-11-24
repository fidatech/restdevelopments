<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ProjectPublicController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SitemapController;

// Admin
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeSectionController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectUnitController;
use App\Http\Controllers\Admin\ProjectGalleryController;
use App\Http\Controllers\Admin\ProjectDocumentController;
use App\Http\Controllers\Admin\ProjectAmenityController;
use App\Http\Controllers\Admin\HomeCounterController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\AboutSettingController;
use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\FounderSettingController;
use App\Http\Controllers\Admin\ProjectHighlightController;
use App\Http\Controllers\FounderController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\WhyChooseUsController;
use App\Http\Controllers\Admin\WhyChooseUsItemController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\VacancyController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\TestimonialController;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

/**
 * Redirect helpers (so /projects, /about and /blog always redirect to localized version)
 */
Route::get('/projects', function () {
    $locale = session('locale') ?: config('app.fallback_locale', 'en');
    return redirect()->route('projects.index', ['locale' => $locale]);
});
Route::get('/about', function () {
    $locale = session('locale') ?: config('app.fallback_locale', 'en');
    return redirect()->route('about', ['locale' => $locale]);
});
Route::get('/blog', function () {
    $locale = session('locale') ?: config('app.fallback_locale', 'en');
    return redirect()->route('blog.index', ['locale' => $locale]);
});
Route::get('/latest-launches', function () {
    $locale = session('locale') ?: config('app.fallback_locale', 'en');
    return redirect()->route('latest-launches', ['locale' => $locale]);
});

Route::get('/contact', function () {
    $locale = session('locale') ?: config('app.fallback_locale', 'en');
    return redirect()->route('contact', ['locale' => $locale]);
});

Route::get('/team', function () {
    $locale = session('locale') ?: config('app.fallback_locale', 'en');
    return redirect()->route('team.index', ['locale' => $locale]);
});

Route::get('/careers', function () {
    $locale = session('locale') ?: config('app.fallback_locale', 'en');
    return redirect()->route('careers.index', ['locale' => $locale]);
});



Route::get('/lang/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', config('app.available_locales', ['en']))
    ->name('lang.switch');

/**
 * --------------------------------------------------------------------------
 * Public Routes (localized)
 * --------------------------------------------------------------------------
 */
Route::group([
    'prefix'     => '{locale?}',
    'where'      => ['locale' => 'en|ps|fa|uz'],
    'middleware' => ['setlocale'],
], function () {
    Route::get('/',        [HomeController::class, 'index'])->name('home');
    Route::get('about',    [AboutController::class, 'index'])->name('about');
    Route::get('founder',  [FounderController::class, 'index'])->name('founder');

    // Projects
    Route::get('projects',        [ProjectPublicController::class, 'index'])->name('projects.index');
    Route::get('projects/{slug}', [ProjectPublicController::class, 'show'])
        ->name('projects.show')
        ->where('slug', '[A-Za-z0-9\-_]+');

    // Blog
    Route::get('blog',        [PostController::class, 'publicIndex'])->name('blog.index');
    Route::get('blog/{slug}', [PostController::class, 'publicShow'])->name('blog.show');

    Route::get('latest-launches', [ProjectPublicController::class, 'latestLaunches'])
        ->name('latest-launches');

    Route::get('/team', [TeamController::class, 'publicIndex'])->name('team.index');

    Route::get('/testimonials', [TestimonialController::class, 'publicIndex'])->name('testimonials.index');

    Route::get('/contact', [ContactController::class, 'publicIndex'])->name('contact');
    Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

    Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
    Route::get('/careers/{slug}', [CareerController::class, 'show'])->name('careers.show');
});

/**
 * --------------------------------------------------------------------------
 * Admin Routes (no locale in URL)
 * --------------------------------------------------------------------------
 */
Route::prefix('admin')
    ->middleware(['auth'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Home builder
        Route::get('/home', [HomeSectionController::class, 'index'])->name('home.sections');
        Route::post('/home/save-order', [HomeSectionController::class, 'saveOrder'])->name('home.sections.save');

        // Collections
        Route::resource('sliders', SliderController::class)->except(['show']);
        Route::post('sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');

        Route::resource('home-counters', HomeCounterController::class)->except(['show']);

        Route::get('about/settings', [AboutSettingController::class, 'index'])->name('about.settings.index');
        Route::post('about/settings', [AboutSettingController::class, 'store'])->name('about.settings.store');

        Route::get('about/founder', [FounderSettingController::class, 'index'])->name('founder.index');
        Route::post('about/founder', [FounderSettingController::class, 'store'])->name('founder.store');

        Route::resource('about/sections', AboutSectionController::class)->except(['show'])->names('about.sections');

        Route::resource('projects', ProjectController::class)->except(['show']);

        // Units
        Route::get('projects/{project}/units/datatable', [ProjectUnitController::class, 'datatable'])->name('projects.units.datatable');
        Route::get('projects/{project}/units',            [ProjectUnitController::class, 'index'])->name('projects.units.index');
        Route::post('projects/{project}/units',           [ProjectUnitController::class, 'store'])->name('projects.units.store');
        Route::put('projects/{project}/units/{unit}',     [ProjectUnitController::class, 'update'])->name('projects.units.update');
        Route::delete('projects/{project}/units/{unit}',  [ProjectUnitController::class, 'destroy'])->name('projects.units.destroy');

        // Documents
        Route::get('projects/{project}/documents/datatable', [ProjectDocumentController::class, 'datatable'])->name('projects.documents.datatable');
        Route::resource('projects/{project}/documents', ProjectDocumentController::class)->except(['show']);
        Route::post('projects/{project}/documents',           [ProjectDocumentController::class, 'store'])->name('projects.documents.store');

        // Gallery
        // Highlights (amenities with a photo)
        Route::get('projects/{project}/highlights',                [ProjectHighlightController::class, 'index'])->name('projects.highlights.index');
        Route::post('projects/{project}/highlights',               [ProjectHighlightController::class, 'store'])->name('projects.highlights.store');
        Route::put('projects/{project}/highlights/{highlight}',    [ProjectHighlightController::class, 'update'])->name('projects.highlights.update');
        Route::delete('projects/{project}/highlights/{highlight}', [ProjectHighlightController::class, 'destroy'])->name('projects.highlights.destroy');

        Route::get('projects/{project}/gallery/datatable', [ProjectGalleryController::class, 'datatable'])->name('projects.gallery.datatable');
        Route::resource('projects/{project}/gallery', ProjectGalleryController::class)->except(['show']);
        Route::post('projects/{project}/gallery',           [ProjectGalleryController::class, 'store'])->name('projects.gallery.store');
        Route::put('projects/{project}/gallery/{gallery}',     [ProjectGalleryController::class, 'update'])->name('projects.gallery.update');
        Route::delete('projects/{project}/gallery/{gallery}',  [ProjectGalleryController::class, 'destroy'])->name('projects.gallery.destroy');
        // Amenities
        Route::resource('amenities', ProjectAmenityController::class)->except(['show']);
        // Datatable for Amenities
        Route::get('amenities/datatable', [ProjectAmenityController::class, 'datatable'])->name('amenities.datatable');

        // Blog Categories
        Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);

        // Blog Posts
        Route::resource('blogs', BlogController::class)->except(['show']);
    Route::post('/blogs/ckeditor/upload', [BlogController::class, 'ckeditorUpload'])
    ->name('blogs.ckeditor.upload');
        // Why Choose Us (Admin)
        Route::get('why-us', [WhyChooseUsController::class, 'edit'])->name('why-us.edit');
        Route::post('why-us', [WhyChooseUsController::class, 'update'])->name('why-us.update');

        Route::resource('why-us/items', WhyChooseUsItemController::class)->except(['show']);

        // Team Members
        Route::resource('team', TeamController::class)->except(['show']);

        // Clients
        Route::resource('clients', ClientController::class)->except(['show']);

        // Route::resource('testimonials', TestimonialController::class);

        Route::get('contact', [ContactController::class, 'edit'])->name('contact.edit');
        Route::post('contact', [ContactController::class, 'update'])->name('contact.update');

        Route::prefix('careers')->name('careers.')->middleware(['auth'])->group(function () {
            Route::get('/', [VacancyController::class, 'index'])->name('index');
            Route::post('/', [VacancyController::class, 'store'])->name('store');
            Route::get('/{vacancy}/edit', [VacancyController::class, 'edit'])->name('edit');
            Route::put('/{vacancy}', [VacancyController::class, 'update'])->name('update');
            Route::delete('/{vacancy}', [VacancyController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('static-pages')->name('static-pages.')->group(function () {
            Route::get('/',  [StaticPageController::class, 'index'])->name('index');
            Route::post('/update/{slug}', [StaticPageController::class, 'update'])->name('update');
        });
    });

require __DIR__ . '/auth.php';
