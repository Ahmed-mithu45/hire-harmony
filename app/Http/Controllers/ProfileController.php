<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Experience;
use App\Models\Education;
use App\Models\JobCircular;
use App\Models\Application;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    /**
     * Display the home page with dynamic job listings
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
     * Display the About page
     */
    public function about()
    {
        $counts = Cache::get('harmony_stats', function () {
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
     * Store contact messages
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
     * Update basic profile info (Single success message logic)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'           => 'nullable|string|max:255',
            'title'          => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'dob'            => 'nullable|date',
            'address'        => 'nullable|string',
            'summary'        => 'nullable|string',
            'skills'         => 'nullable|string',
            'github_url'     => 'nullable|url',
            'linkedin_url'   => 'nullable|url',
            'portfolio_url'  => 'nullable|url',
            'preferred_jobs' => 'nullable|string',
            'cv'             => 'nullable|mimes:pdf,doc,docx|max:2048',
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

        $filteredData = array_filter($data, fn($value) => !is_null($value));

        if (!empty($filteredData)) {
            User::where('id', $user->id)->update($filteredData);
            // Automate: Update all existing jobs posted by this company to the new name
            if (isset($filteredData['name'])) {
                JobCircular::where('user_id', $user->id)->update(['company_name' => $filteredData['name']]);
            }
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
     * Dynamic Work Experience
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

    public function deleteExperience($id)
    {
        Experience::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        return back()->with('success', 'Experience removed successfully!');
    }

    /**
     * Dynamic Education
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

    public function deleteEducation($id)
    {
        Education::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        return back()->with('success', 'Education record removed successfully!');
    }

    /**
     * Delete CV
     */
    public function deleteCV()
    {
        $user = Auth::user();
        if ($user->cv_path) {
            $filePath = public_path('uploads/cvs/' . $user->cv_path);
            if (file_exists($filePath)) unlink($filePath);
            User::where('id', $user->id)->update(['cv_path' => null]);
            return back()->with('success', 'CV deleted successfully!');
        }
        return back()->with('error', 'No CV found to delete.');
    }

    /**
     * Dashboards
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

    public function publicCompanyView($unique_id)
    {
        $company = User::where('unique_id', $unique_id)->firstOrFail();
        $jobs = JobCircular::where('user_id', $company->id)->latest()->get();
        $isPublic = true;
        $stats = ['active_jobs' => $jobs->count(), 'total_applicants' => 0, 'interviews' => 0];

        return view('company-dashboard', compact('company', 'jobs', 'stats', 'isPublic'));
    }

    public function associatedCompanies()
    {
        $companies = User::where('user_type', 'company')->latest()->get();
        return view('associated', compact('companies'));
    }

    /**
     * Post Job - Automatically uses current Auth user name
     */
    public function storeJob(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'job_type'      => 'required|string',
            'openings'      => 'required|integer',
            'educations'    => 'required|string',
            'category'      => 'required|string',
            'skills_needed' => 'required|string',
            'description'   => 'required|string',
            'job_details'   => 'required|string',
        ]);

        JobCircular::create([
            'user_id'       => Auth::id(),
            'company_name'  => Auth::user()->name, // Auto-sync with current name
            'title'         => $request->title,
            'job_type'      => $request->job_type,
            'openings'      => $request->openings,
            'educations'    => $request->educations,
            'category'      => $request->category,
            'skills_needed' => $request->skills_needed,
            'description'   => $request->description,
            'job_details'   => $request->job_details,
        ]);

        Cache::forget('harmony_stats');
        return back()->with('success', 'Job Circular posted successfully!');
    }

    public function deleteJob($id)
    {
        JobCircular::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        Cache::forget('harmony_stats');
        return back()->with('success', 'Job deleted successfully!');
    }

    public function viewApplicants($id)
    {
        $job = JobCircular::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $applicants = Application::where('job_circular_id', $id)->with('user')->latest()->get();
        return view('view-applicants', compact('job', 'applicants'));
    }

    public function applyForJob($id)
    {
        if (Auth::user()->user_type !== 'candidate') return back()->with('error', 'Only candidates can apply.');
        
        $exists = Application::where('user_id', Auth::id())->where('job_circular_id', $id)->exists();
        if ($exists) return back()->with('error', 'Already applied.');

        Application::create(['user_id' => Auth::id(), 'job_circular_id' => $id, 'status' => 'Pending']);
        Cache::forget('harmony_stats');
        return back()->with('success', 'Application submitted!');
    }

    public function findJobs()
    {
        $jobs = JobCircular::with('company')->latest()->get();
        $appliedJobIds = Auth::check() ? Application::where('user_id', Auth::id())->pluck('job_circular_id')->toArray() : [];
        return view('jobs', compact('jobs', 'appliedJobIds'));
    }

    public function profile()
{
    $user = Auth::user();
    
    // Get applied job IDs for the "Applied" button state
    $appliedJobIds = Application::where('user_id', $user->id)
                        ->pluck('job_circular_id')
                        ->toArray();

    $appliedJobs = Application::where('user_id', $user->id)
                    ->with(['jobCircular'])
                    ->latest()
                    ->get();

    // 1. Normalize User Data into searchable keyword strings
    $userSkills   = strtolower($user->skills ?? '');
    $userPrefs    = strtolower($user->preferred_jobs ?? '');
    $userEdu      = strtolower($user->educations->pluck('degree')->implode(' ')); // Assuming relation exists
    $userSummary  = strtolower($user->summary ?? '');

    $relatedJobs = JobCircular::all()->map(function ($job) use ($userSkills, $userPrefs, $userEdu, $userSummary) {
        $score = 0;
        $totalWeight = 4; // We are comparing 4 distinct sections

        // Section 1: Job Title vs Preferred Roles
        if (!empty($userPrefs)) {
            $prefArray = array_filter(array_map('trim', explode(',', $userPrefs)));
            foreach ($prefArray as $pref) {
                if (str_contains(strtolower($job->title), $pref)) {
                    $score++;
                    break; 
                }
            }
        }

        // Section 2: Education Requirements
        if (!empty($userEdu) && !empty($job->educations)) {
            // Check if user's degree keywords appear in job's education requirement
            $eduKeywords = ['bsc', 'msc', 'diploma', 'computer', 'engineer', 'hsc', 'ssc'];
            foreach ($eduKeywords as $key) {
                if (str_contains(strtolower($job->educations), $key) && str_contains($userEdu, $key)) {
                    $score++;
                    break;
                }
            }
        }

        // Section 3: Skills Match
        if (!empty($userSkills)) {
            $mySkills = array_filter(array_map('trim', explode(',', $userSkills)));
            $matchedSkills = 0;
            foreach ($mySkills as $skill) {
                if (str_contains(strtolower($job->skills_needed), $skill)) {
                    $matchedSkills++;
                }
            }
            // If at least one skill matches, we give a partial score, or full if many match
            if ($matchedSkills > 0) $score += min(1, $matchedSkills / 2); 
        }

        // Section 4: Short Description vs Summary (Keyword overlap)
        if (!empty($userSummary)) {
            $description = strtolower($job->description);
            $commonKeywords = ['developer', 'management', 'ai', 'web', 'design', 'software', 'intern'];
            $overlap = 0;
            foreach ($commonKeywords as $word) {
                if (str_contains($description, $word) && str_contains($userSummary, $word)) {
                    $overlap++;
                }
            }
            if ($overlap > 0) $score += 1;
        }

        // Final Percentage Calculation
        $job->match_percentage = min(100, round(($score / $totalWeight) * 100));
        return $job;
    })
    ->filter(fn($job) => $job->match_percentage > 10) // Only show if match is > 10%
    ->sortByDesc('match_percentage')
    ->take(6);

    return view('candidate-profile', compact('user', 'appliedJobs', 'relatedJobs', 'appliedJobIds'));
}

    public function removeApplication($id)
    {
        Application::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        Cache::forget('harmony_stats');
        return back()->with('success', 'Application withdrawn.');
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $status = $request->status;
        $application->update([
            'status' => $status,
            'interview_time' => ($status === 'Interview Set') ? $request->interview_time : null
        ]);
        Cache::forget('harmony_stats');
        return back()->with('success', 'Status updated successfully!');
    }

    // Admin Methods
    public function adminDashboard()
    {
        $candidates = User::where('user_type', 'candidate')->latest()->get();
        $companies = User::where('user_type', 'company')->latest()->get();
        $feedbacks = ContactMessage::latest()->get();
        return view('admin.dashboard', compact('candidates', 'companies', 'feedbacks'));
    }

    public function publicCandidateView($unique_id)
    {
        $user = User::where('unique_id', $unique_id)->firstOrFail();
        $appliedJobs = Application::where('user_id', $user->id)->with('jobCircular')->latest()->get();
        return view('candidate-profile', compact('user', 'appliedJobs'));
    }

    /**
 * Admin Action: Permanently delete a user (Candidate or Company)
 */
public function destroyUser($id)
{
    $user = User::findOrFail($id);

    if ($user->profile_photo && file_exists(public_path('images/profiles/' . $user->profile_photo))) {
        unlink(public_path('images/profiles/' . $user->profile_photo));
    }
    if ($user->cv_path && file_exists(public_path('uploads/cvs/' . $user->cv_path))) {
        unlink(public_path('uploads/cvs/' . $user->cv_path));
    }

    $user->delete();
    Cache::forget('harmony_stats');

    return back()->with('success', 'User and all associated data deleted successfully.');
}

/**
 * Handle Cover Photo Upload for Candidates and Companies
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

        // 3. Update the database using the Model find to clear editor warnings
        User::where('id', $user->id)->update(['cover_photo' => $name]);

        return back()->with('success', 'Cover photo updated successfully!');
    }

    return back()->with('error', 'No file was selected.');
}
}