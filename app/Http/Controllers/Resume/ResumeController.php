<?php

namespace App\Http\Controllers\Resume;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resume;

class ResumeController extends Controller
{
    public function create()
    {
        return view('profile.resume.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'additional_info' => 'nullable|string',
            'education' => 'required|array',
            'experience' => 'required|array',
            'skills' => 'required|array',
        ]);

        $resume = Resume::create([
            'user_id' => auth()->id(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'additional_info' => $validated['additional_info'] ?? null,
        ]);

        foreach ($validated['education'] as $edu) {
            $resume->education()->create($edu);
        }

        foreach ($validated['experience'] as $exp) {
            $resume->experience()->create($exp);
        }

        foreach ($validated['skills'] as $skill) {
            $resume->skills()->create($skill);
        }

        return redirect()->route('resume')->with('success', 'Резюме успешно создано!');
    }
}
