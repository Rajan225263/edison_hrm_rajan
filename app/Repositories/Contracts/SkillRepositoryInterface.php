<?php
namespace App\Repositories\Contracts;

use App\Models\Skill;

interface SkillRepositoryInterface
{
    public function getAll();
    public function create(array $data): Skill;
}
