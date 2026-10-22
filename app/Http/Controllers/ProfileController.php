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
use App\Notifications\JobMatched;

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
     * Update basic profile info (Removed size limits)
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
            'cv'             => 'nullable|file|mimes:pdf,doc,docx,rtf,txt', // Removed max size
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
            if (isset($filteredData['name'])) {
                JobCircular::where('user_id', $user->id)->update(['company_name' => $filteredData['name']]);
            }
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Photo Upload (All types allowed, no size limit)
     */
    public function updatePhoto(Request $request)
    {
        $request->validate(['photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp']);
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
     * Cover Photo (All types allowed, no size limit)
     */
    public function updateCover(Request $request)
    {
        $request->validate(['cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,bmp']);
        $user = Auth::user();

        if ($request->hasFile('cover')) {
            if ($user->cover_photo && file_exists(public_path('images/covers/' . $user->cover_photo))) {
                unlink(public_path('images/covers/' . $user->cover_photo));
            }

            $file = $request->file('cover');
            $name = time() . '_cover.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/covers'), $name);

            User::where('id', $user->id)->update(['cover_photo' => $name]);
            return back()->with('success', 'Cover photo updated successfully!');
        }
        return back()->with('error', 'No file was selected.');
    }

    /**
     * Work Experience: Add & Edit
     */
    public function addExperience(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'company' => 'required|string',
            'duration' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Use updateOrCreate: if experience_id is null/empty, it creates; otherwise it updates.
        Experience::updateOrCreate(
            [
                'id' => $request->experience_id, 
                'user_id' => Auth::id()
            ],
            $data
        );

        $message = $request->experience_id ? 'Experience updated!' : 'Experience added successfully!';
        return back()->with('success', $message);
    }

    public function deleteExperience($id)
    {
        Experience::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        return back()->with('success', 'Experience removed successfully!');
    }

    /**
     * Education: Add & Edit
     */
    public function addEducation(Request $request)
    {
        $data = $request->validate([
            'degree' => 'required|string',
            'institution' => 'required|string',
            'duration' => 'required|string',
        ]);

        // Use updateOrCreate: if education_id is null/empty, it creates; otherwise it updates.
        Education::updateOrCreate(
            [
                'id' => $request->education_id, 
                'user_id' => Auth::id()
            ],
            $data
        );

        $message = $request->education_id ? 'Education updated!' : 'Education details added!';
        return back()->with('success', $message);
    }

    public function deleteEducation($id)
    {
        Education::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        return back()->with('success', 'Education record removed successfully!');
    }

    public function deleteCV()
    {
        $user = Auth::user();
        if ($user->cv_path) {
            $filePath = public_path('uploads/cvs/' . $user->cv_path);
            if (file_exists($filePath)) unlink($filePath);
            
            // Updating via the authenticated user instance
            $user->update(['cv_path' => null]);
            
            return back()->with('success', 'CV deleted successfully!');
        }
        return back()->with('error', 'No CV found to delete.');
    }

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

    public function storeJob(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'job_type'      => 'required|string',
            'openings'      => 'required|integer',
            'educations'    => 'required|string',
            'category'      => 'required|string',
            'skills_needed' => 'required|string',
            'description'   => 'required|string',
            'job_details'   => 'required|string',
        ]);

        if ($request->filled('job_id')) {
            // --- UPDATE LOGIC ---
            JobCircular::where('id', $request->job_id)
                ->where('user_id', Auth::id())
                ->update($data);
            $message = 'Job Circular updated successfully!';
        } else {
            // --- CREATE LOGIC ---
            JobCircular::create(array_merge($data, [
                'user_id' => Auth::id(),
                'company_name' => Auth::user()->name
            ]));
            $message = 'Job Circular posted successfully!';
        }

        Cache::forget('harmony_stats');
        return back()->with('success', $message);
    }

    public function deleteJob($id)
    {
        JobCircular::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        Cache::forget('harmony_stats');
        return back()->with('success', 'Job deleted successfully!');
    }
public function viewApplicants(Request $request, $id)
    {
        $job = JobCircular::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $applicants = Application::where('job_circular_id', $id)->with('user')->get();

        // 1. Calculate the match score for all applicants
        $applicants = $applicants->map(function ($app) use ($job) {
            $user = $app->user;
            $userSkills = array_filter(array_map('trim', explode(',', strtolower($user->skills ?? ''))));
            $jobSkills = array_filter(array_map('trim', explode(',', strtolower($job->skills_needed ?? ''))));
            $matches = 0;
            foreach ($jobSkills as $jS) {
                foreach ($userSkills as $uS) {
                    similar_text($jS, $uS, $sim);
                    if ($sim > 75) { $matches++; break; }
                }
            }
            $app->match_score = count($jobSkills) > 0 ? round(($matches / count($jobSkills)) * 100) : 0;
            return $app;
        });

        // 2. Apply the filter if requested
        if ($request->has('min_score') && $request->min_score != '') {
            $minScore = (int) $request->min_score;
            $applicants = $applicants->filter(function ($app) use ($minScore) {
                return $app->match_score >= $minScore;
            });
        }

        // 3. Sort by the highest score
        $applicants = $applicants->sortByDesc('match_score');

        // Pass the requested score back to the view to keep the dropdown selected
        $selectedScore = $request->min_score;

        return view('view-applicants', compact('job', 'applicants', 'selectedScore'));
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $appliedJobIds = Application::where('user_id', $user->id)->pluck('job_circular_id')->toArray();
        $appliedJobs = Application::where('user_id', $user->id)->with(['jobCircular'])->latest()->get();

        $userSkillsArray = array_filter(array_map('trim', explode(',', strtolower($user->skills ?? ''))));
        $userPrefs = strtolower($user->preferred_jobs ?? '');

        $relatedJobs = JobCircular::all()->map(function ($job) use ($userSkillsArray, $userPrefs) {
            $score = 0;
            $totalWeight = 10;
            $matchedSkills = [];

            // 1. Preferred Roles Match (NLP)
            if (!empty($userPrefs)) {
                $prefArray = array_filter(array_map('trim', explode(',', $userPrefs)));
                foreach ($prefArray as $pref) {
                    similar_text($pref, strtolower($job->title), $percent);
                    if ($percent > 60 || str_contains(strtolower($job->title), $pref)) {
                        $score += 4;
                        break;
                    }
                }
            }

            // 2. Semantic Skills Match (NLP)
            if (!empty($userSkillsArray) && !empty($job->skills_needed)) {
                $jobSkillsNeeded = array_filter(array_map('trim', explode(',', strtolower($job->skills_needed))));
                foreach ($jobSkillsNeeded as $jSkill) {
                    $foundMatch = false;
                    foreach ($userSkillsArray as $uSkill) {
                        similar_text($uSkill, $jSkill, $similarity);
                        if ($similarity > 75 || str_contains($uSkill, $jSkill) || str_contains($jSkill, $uSkill)) {
                            $foundMatch = true;
                            break;
                        }
                    }
                    if ($foundMatch) $matchedSkills[] = $jSkill;
                }
                $skillRatio = count($jobSkillsNeeded) > 0 ? (count($matchedSkills) / count($jobSkillsNeeded)) : 0;
                $score += ($skillRatio * 6);
                $job->missing_skills = array_diff($jobSkillsNeeded, $matchedSkills);
            }

            $job->match_percentage = min(100, round(($score / $totalWeight) * 100));
            return $job;
        })
        ->filter(fn($job) => $job->match_percentage > 10)
        ->sortByDesc('match_percentage')
        ->take(6);

        // 3. Notification Logic
        foreach ($relatedJobs as $job) {
            if ($job->match_percentage >= 70) {
                // Prevent duplicate notifications by checking the JSON data column
                $alreadyNotified = $user->notifications()
                    ->where('data', 'like', '%"job_id":' . $job->id . '%')
                    ->exists();

                if (!$alreadyNotified) {
                    $user->notify(new \App\Notifications\JobMatched($job));
                }
            }
        }

        return view('candidate-profile', compact('user', 'appliedJobs', 'relatedJobs', 'appliedJobIds'));
    }

    public function removeApplication(int $id)
    {
        Application::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();
        Cache::forget('harmony_stats');
        return back()->with('success', 'Application withdrawn.');
    }

    public function updateApplicationStatus(Request $request,int $id)
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
     * Show the Admin Login Form
     */
    public function showAdminLogin()
    {
        return view('admin.login'); 
    }

    /**
     * Handle the Admin Login Request
     */
    public function adminLoginSubmit(Request $request)
    {
        // 1. Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Attempt to log in
        if (Auth::attempt($credentials)) {
            
            // 3. Verify that the logged-in user is actually an admin
            if (Auth::user()->user_type === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            }
            
            // If they are not an admin (e.g., a candidate or company trying to use this form)
            Auth::logout();
            return back()->withErrors(['email' => 'You do not have admin access.']);
        }

        // If credentials don't match
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }
}