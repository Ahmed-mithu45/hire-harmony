<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Experience;
use App\Models\Education;
use App\Models\JobCircular;
use App\Models\Application;
use App\Models\ContactMessage; // Correctly placed at the top
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    /**
     * Display the home page with the latest 12 jobs and cached stats
     */
    public function index()
    {
        $jobs = JobCircular::with('company')->latest()->take(12)->get();

        $appliedJobIds = [];
        if (Auth::check() && Auth::user()->user_type == 'candidate') {
            $appliedJobIds = Application::where('user_id', Auth::id())
                            ->pluck('job_circular_id')
                            ->toArray();
        }

        $counts = Cache::remember('harmony_stats', 1800, function () {
            return [
                'companies'  => User::where('user_type', 'company')->count(),
                'candidates' => User::where('user_type', 'candidate')->count(),
                'vacancies'  => JobCircular::sum('openings'),
                'hires'      => Application::where('status', 'Interview Set')->count(),
            ];
        });

        return view('index', compact('jobs', 'appliedJobIds', 'counts'));
    }

    /**
     * Display the About page with dynamic cached stats
     */
    public function about()
    {
        $counts = Cache::remember('harmony_stats', 1800, function () {
            return [
                'companies'  => User::where('user_type', 'company')->count(),
                'candidates' => User::where('user_type', 'candidate')->count(),
                'vacancies'  => JobCircular::sum('openings'),
                'hires'      => Application::where('status', 'Interview Set')->count(),
            ];
        });

        return view('about', compact('counts'));
    }

    /**
     * Store contact or feedback messages
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject ?? 'Feedback Form',
            'user_type' => $request->user_type ?? 'Guest',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your message has been sent successfully!');
    }

    /**
     * Update basic profile info
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'          => 'nullable|string|max:255',
            'title'         => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'dob'           => 'nullable|date',
            'address'       => 'nullable|string',
            'summary'       => 'nullable|string',
            'skills'        => 'nullable|string',
            'github_url'    => 'nullable|url',
            'linkedin_url'  => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'cv'            => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('cv')) {
            if ($user->cv_path && file_exists(public_path('uploads/cvs/' . $user->cv_path))) {
                unlink(public_path('uploads/cvs/' . $user->cv_path));
            }
            $fileName = time() . '_' . $request->cv->getClientOriginalName();
            $request->cv->move(public_path('uploads/cvs'), $fileName);
            $data['cv_path'] = $fileName;
        }

        unset($data['cv']);

        $filteredData = array_filter($data, function ($value) {
            return $value !== null;
        });

        if (!empty($filteredData)) {
            User::where('id', $user->id)->update($filteredData);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Handle Profile Photo Upload
     */
    public function updatePhoto(Request $request)
    {
        $request->validate(['photo' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $user = Auth::user();

        if ($request->hasFile('photo')) {
            if ($user->profile_photo && file_exists(public_path('images/profiles/' . $user->profile_photo))) {
                unlink(public_path('images/profiles/' . $user->profile_photo));
            }

            $file = $request->file('photo');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profiles'), $name);

            User::where('id', $user->id)->update(['profile_photo' => $name]);
            return back()->with('success', 'Photo updated successfully!');
        }

        return back()->with('error', 'No photo selected.');
    }

    /**
     * Dynamic Work Experience - Add New
     */
    public function addExperience(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'company' => 'required|string',
            'duration' => 'required|string',
        ]);

        Experience::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'company' => $request->company,
            'duration' => $request->duration,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Experience added successfully!');
    }

    /**
     * Dynamic Work Experience - Delete
     */
    public function deleteExperience($id)
    {
        $experience = Experience::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $experience->delete();
        return back()->with('success', 'Experience removed successfully!');
    }

    /**
     * Dynamic Education - Add New
     */
    public function addEducation(Request $request)
    {
        $request->validate([
            'degree' => 'required|string',
            'institution' => 'required|string',
            'duration' => 'required|string',
        ]);

        Education::create([
            'user_id' => Auth::id(),
            'degree' => $request->degree,
            'institution' => $request->institution,
            'duration' => $request->duration,
        ]);

        return back()->with('success', 'Education details added!');
    }

    /**
     * Dynamic Education - Delete
     */
    public function deleteEducation($id)
    {
        $education = Education::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $education->delete();
        return back()->with('success', 'Education record removed successfully!');
    }

    /**
     * Delete CV file
     */
    public function deleteCV()
    {
        $user = Auth::user();
        if ($user->cv_path) {
            $filePath = public_path('uploads/cvs/' . $user->cv_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            User::where('id', $user->id)->update(['cv_path' => null]);
            return back()->with('success', 'CV deleted successfully!');
        }
        return back()->with('error', 'No CV found to delete.');
    }

    /**
     * PRIVATE DASHBOARD (Owner View)
     */
    public function companyDashboard()
    {
        $company = Auth::user();
        $jobs = JobCircular::where('user_id', $company->id)->latest()->get();

        $stats = [
            'active_jobs'      => $jobs->count(),
            'total_applicants' => Application::whereIn('job_circular_id', $jobs->pluck('id'))->count(),
            'interviews'       => Application::whereIn('job_circular_id', $jobs->pluck('id'))
                                            ->where('status', 'Interview Set')
                                            ->count(),
        ];

        return view('company-dashboard', compact('company', 'jobs', 'stats'));
    }

    /**
     * PUBLIC VIEW (Visitor View)
     */
    public function publicCompanyView($unique_id)
    {
        $company = User::where('unique_id', $unique_id)->firstOrFail();
        $jobs = JobCircular::where('user_id', $company->id)->latest()->get();

        $isPublic = true;

        $stats = [
            'active_jobs'      => $jobs->count(),
            'total_applicants' => 0,
            'interviews'       => 0
        ];

        return view('company-dashboard', compact('company', 'jobs', 'stats', 'isPublic'));
    }

    /**
     * Associated Companies Page
     */
    public function associatedCompanies()
    {
        $companies = User::where('user_type', 'company')->latest()->get();
        return view('associated', compact('companies'));
    }

    /**
     * Post Job
     */
    public function storeJob(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'job_type'     => 'required|string',
            'description'  => 'required|string',
            'openings'     => 'required|integer',
            'category'     => 'required|string',
        ]);

        $data['user_id'] = Auth::id();

        JobCircular::create($data);
        Cache::forget('harmony_stats');

        return back()->with('success', 'Job Circular posted successfully!');
    }

    /**
     * Delete Job
     */
    public function deleteJob($id)
    {
        JobCircular::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail()
                    ->delete();

        Cache::forget('harmony_stats');
        return back()->with('success', 'Job deleted successfully!');
    }

    /**
     * Handle Cover Photo Upload
     */
    public function updateCover(Request $request)
    {
        $request->validate(['cover' => 'required|image|mimes:jpeg,png,jpg|max:3072']);
        $user = Auth::user();

        if ($request->hasFile('cover')) {
            if ($user->cover_photo && file_exists(public_path('images/covers/' . $user->cover_photo))) {
                unlink(public_path('images/covers/' . $user->cover_photo));
            }

            $file = $request->file('cover');
            $name = time() . '_cover.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/covers'), $name);

            User::where('id', $user->id)->update(['cover_photo' => $name]);
            return back()->with('success', 'Cover photo updated!');
        }
        return back()->with('error', 'No file selected.');
    }

    /**
     * View applicants for a specific job
     */
    public function viewApplicants($id)
    {
        $job = JobCircular::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        $applicants = Application::where('job_circular_id', $id)
                                 ->with('user')
                                 ->latest()
                                 ->get();

        return view('view-applicants', compact('job', 'applicants'));
    }

/**
 * Handle a job application submission from a candidate
 */
public function applyForJob($id)
{
    // 1. Security: Only allow logged-in candidates to apply
    if (Auth::user()->user_type !== 'candidate') {
        return back()->with('error', 'Only candidates can apply for jobs.');
    }

    // 2. Prevent Duplicate Applications
    $exists = Application::where('user_id', Auth::id())
                         ->where('job_circular_id', $id)
                         ->exists();

    if ($exists) {
        return back()->with('error', 'You have already applied for this position.');
    }

    // 3. Create the Connection in the Database
    Application::create([
        'user_id' => Auth::id(),
        'job_circular_id' => $id,
        'status' => 'Pending'
    ]);

    Cache::forget('harmony_stats');
    return back()->with('success', 'Application submitted! The company will review your profile.');
}


    /**
     * Display Available Jobs page
     */
    public function findJobs()
    {
        $jobs = JobCircular::with('company')->latest()->get();
        $appliedJobIds = [];

        if (Auth::check() && Auth::user()->user_type == 'candidate') {
            $appliedJobIds = Application::where('user_id', Auth::id())
                            ->pluck('job_circular_id')->toArray();
        }

        return view('jobs', compact('jobs', 'appliedJobIds'));
    }

    /**
     * Candidate Profile
     */
    public function profile()
    {
        $user = Auth::user();
        $appliedJobs = Application::where('user_id', $user->id)
                        ->with('jobCircular')
                        ->latest()
                        ->get();

        return view('candidate-profile', compact('user', 'appliedJobs'));
    }

    /**
     * Withdraw application
     */
    public function removeApplication($id)
    {
        $application = Application::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        $application->delete();
        Cache::forget('harmony_stats');

        return back()->with('success', 'Application withdrawn successfully.');
    }
    public function adminDashboard()
{
    $candidates = User::where('user_type', 'candidate')->latest()->get();
    $companies = User::where('user_type', 'company')->latest()->get();
    $feedbacks = ContactMessage::latest()->get();

    return view('admin.dashboard', compact('candidates', 'companies', 'feedbacks'));
}

public function destroyUser($id)
{
    $user = User::findOrFail($id);
    // Delete related profile photos/CVs if they exist
    $user->delete();
    return back()->with('success', 'User profile deleted successfully.');
}

// Show the login form
public function showAdminLogin() {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('admin.login');
}

// Handle the login submission
public function adminLoginSubmit(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        Auth::logout();
        return back()->with('error', 'You are not an authorized admin.');
    }
    return back()->with('error', 'Invalid credentials.');
}

// Update Admin Credentials (from Dashboard)
public function updateAdminSettings(Request $request) {
    // Re-fetching via the Model clears the editor warning
    $user = \App\Models\User::find(Auth::id());

    $request->validate([
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed'
    ]);

    $user->email = $request->email;
    if ($request->password) {
        $user->password = bcrypt($request->password);
    }

    $user->save(); // The squiggle should disappear now!

    return back()->with('success', 'Admin credentials updated! Use these for next login.');
}
/**
 * PUBLIC CANDIDATE VIEW (For Admin)
 */
public function publicCandidateView($unique_id)
{
    // 1. Fetch the user by unique ID
    $user = User::where('unique_id', $unique_id)->firstOrFail();

    // 2. You MUST define $appliedJobs here so compact() can find it
    $appliedJobs = \App\Models\Application::where('user_id', $user->id)
                    ->with('jobCircular')
                    ->latest()
                    ->get();

    // 3. Now both 'user' and 'appliedJobs' are defined and ready to go
    return view('candidate-profile', compact('user', 'appliedJobs'));
}


public function updateApplicationStatus(Request $request, $id)
{
    $application = Application::findOrFail($id);
    $application->update(['status' => $request->status]);

    // Clear cache so the "Interviews Set" stat updates
    Cache::forget('harmony_stats');

    return back()->with('success', 'Status updated to ' . $request->status);
}
}
