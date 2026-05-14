<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Project;
use App\Models\Setting;
use App\Models\TechStack;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        return Inertia::render('Welcome', [
            'name' => 'Atala Aditia Anwar',
            'role' => 'QA Automation Engineer',
            'projects' => Project::latest()->get(),
            'socials' => Setting::all(), 
            'stacks' => TechStack::all(), 
        ]);
    }
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'tech_stack' => 'required',
            'link' => 'nullable|url',
            'image' => 'nullable|image|max:2048', 
        ]);

        if ($request->hasFile('image')) {
            // 1. Hapus foto lama jika ada di storage agar tidak menumpuk
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            
            // 2. Simpan foto baru ke folder 'projects' di disk 'public'
            $path = $request->file('image')->store('projects', 'public');
            
            // 3. PENTING: Masukkan path foto baru ke dalam array $data
            $data['image'] = $path;
        }

        // 4. Proses update database dengan data yang sudah divalidasi
        $project->update($data);

        return Redirect::back()->with('message', 'Project berhasil diupdate!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return Redirect::route('dashboard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'required|string',
            'link' => 'nullable|url',
            'image' => 'nullable|image|max:2048', // Tambahkan validasi image
        ]);

        // Simpan foto jika ada
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image'] = $path; // Masukkan path ke array validated
        }

        Project::create($validated);

        return Redirect::route('dashboard')->with('message', 'Project berhasil ditambah!');
    }

    public function portfolio()
    {
        return Inertia::render('Projects/Index', [
            'projects' => Project::latest()->get(),
        ]);
    }

    public function storeStack(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'url'  => 'required|url' 
        ]);

        TechStack::create($data);
        
        return back()->with('message', 'Stack berhasil ditambah!');
    }

    public function destroyStack(TechStack $stack) {
        $stack->delete();
        return back()->with('message', 'Stack dihapus!');
    }

    public function storeSocial(Request $request) {
        $data = $request->validate([
            'key' => 'required|string',   
            'value' => 'required|url'     
        ]);
        Setting::create($data);
        return back()->with('message', 'Social Media berhasil ditambah!');
    }

    public function destroySocial(Setting $social) {
        $social->delete();
        return back()->with('message', 'Social dihapus!');
    }
}
