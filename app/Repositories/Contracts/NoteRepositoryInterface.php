<?php

namespace App\Repositories\Contracts;

interface NoteRepositoryInterface
{
    /**
     * Create a note for a given model (Product, Sale, etc.)
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $note
     * @param  int  $userId
     * @return \App\Models\Note
     */
    public function createForModel($model, string $note, int $userId);
    public function getForModel($model);
}
