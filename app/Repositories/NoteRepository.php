<?php

namespace App\Repositories;

use App\Repositories\Contracts\NoteRepositoryInterface;

class NoteRepository implements NoteRepositoryInterface
{
    public function createForModel($model, string $note, int $userId)
    {

        // Model should have a notes() relationship
        return $model->notes()->create([
            'note' => $note,
            'user_id' => $userId,
        ]);
    }

    public function getForModel($model)
    {
        return $model->notes()->with('user')->latest()->get();
    }
}
