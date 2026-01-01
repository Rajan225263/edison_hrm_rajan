<?php
namespace App\Repositories;

use App\Models\Skill;
use App\Repositories\Contracts\SkillRepositoryInterface;

class SkillRepository implements SkillRepositoryInterface
{
    public function getAll()
    {
        return Skill::orderBy('name')->get();
    }

    public function create(array $data): Skill
    {
        return Skill::create($data);
    }
}
