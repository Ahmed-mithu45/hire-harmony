<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// --- 1. Public Routes (Anyone can visit) ---
Route::get('/', [ProfileController::class, 'index'])->name('home');
Route::get('/about', [ProfileController::class, 'about'])->name('about');
Route::get('/jobs', [ProfileController::class, 'findJobs'])->name('jobs.index');
Route::get('/associated', [ProfileController::class, 'associatedCompanies'])->name('associated.index');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::post('/contact/send', [ProfileController::class, 'storeContact'])->name('contact.send');

// --- 2. Authentication (Login/Register) ---
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- 3. Admin Entry Gate (Special Login) ---
Route::get('/admin', [ProfileController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [ProfileController::class, 'adminLoginSubmit'])->name('admin.login.submit');

// --- 4. Protected Routes (Must be logged in) ---
Route::middleware(['auth'])->group(function () {

    // General Profiles
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/company-dashboard', [ProfileController::class, 'companyDashboard'])->name('company.dashboard');

    // Profile Management (Photos, CV, Covers)
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/update-cover', [ProfileController::class, 'updateCover'])->name('profile.cover');
    Route::delete('/profile/cv-delete', [ProfileController::class, 'deleteCV'])->name('profile.cv.delete');

    // Experience & Education
    Route::post('/experience/add', [ProfileController::class, 'addExperience'])->name('experience.add');
    Route::delete('/experience/delete/{id}', [ProfileController::class, 'deleteExperience'])->name('experience.delete');
    Route::post('/education/add', [ProfileController::class, 'addEducation'])->name('education.add');
    Route::delete('/education/delete/{id}', [ProfileController::class, 'deleteEducation'])->name('education.delete');

    // Recruitment Engine (Job Store, Apply, Status)
    Route::post('/company/job/store', [ProfileController::class, 'storeJob'])->name('job.store');
    Route::delete('/company/job/{id}', [ProfileController::class, 'deleteJob'])->name('job.delete');
    Route::get('/job/{id}/applicants', [ProfileController::class, 'viewApplicants'])->name('job.applicants');
    Route::post('/jobs/apply/{id}', [ProfileController::class, 'applyForJob'])->name('jobs.apply');
    Route::delete('/application/remove/{id}', [ProfileController::class, 'removeApplication'])->name('application.remove');
    Route::post('/application/{id}/status', [ProfileController::class, 'updateApplicationStatus'])->name('application.status');

    // Public Profile Viewing (For Admin & Cross-User Viewing)
    Route::get('/company/view/{unique_id}', [ProfileController::class, 'publicCompanyView'])->name('company.public.view');
    Route::get('/candidate/view/{unique_id}', [ProfileController::class, 'publicCandidateView'])->name('candidate.public.view');

    // --- 5. Admin Management Area ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [ProfileController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::post('/settings/update', [ProfileController::class, 'updateAdminSettings'])->name('admin.settings.update');
        Route::delete('/user/delete/{id}', [ProfileController::class, 'destroyUser'])->name('admin.user.delete');
    });
    Route::post('/store-job', [ProfileController::class, 'storeJob'])->name('job.store');
    // resources/routes/web.php
Route::get('/associated-companies', [ProfileController::class, 'associatedCompanies'])->name('associated.companies');

});
