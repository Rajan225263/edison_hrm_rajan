<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Repositories\Contracts\SkillRepositoryInterface;
use App\Models\Skill;
use Exception;

class SkillController extends Controller
{
    protected $skills;

    public function __construct(SkillRepositoryInterface $skills)
    {
        $this->skills = $skills;
    }

    public function index()
    {
        return view('skills.index', [
            'skills' => $this->skills->getAll()
        ]);
    }

    public function create()
    {
        return view('skills.form', [
            'skill' => new Skill()
        ]);
    }

    public function store(SkillRequest $request)
    {
        try {
            $this->skills->create($request->only('name'));

            return redirect()
                ->route('skills.index')
                ->with('success', 'Skill created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong');
        }
    }

}
