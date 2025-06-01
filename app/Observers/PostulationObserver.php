<?php

namespace App\Observers;

use App\Models\Postulation;
use App\Models\Thread;
use Illuminate\Support\Facades\Log;

class PostulationObserver
{
    public function updated(Postulation $postulation)
    {
        // only fire when the statut actually changed to "validée"
        if ($postulation->wasChanged('statut') 
            && $postulation->statut === 'validée'
        ) {
            Log::debug("Postulation {$postulation->id} → validée, creating thread");

            // create thread if it doesn’t already exist
            $thread = Thread::firstOrCreate([
                'postulation_id' => $postulation->id
            ]);

            // attach owner and sitter as participants
            $ownerId  = $postulation->search->user_id;
            $sitterId = $postulation->sitter_id;
            $thread->participants()
                   ->syncWithoutDetaching([$ownerId, $sitterId]);
        }
    }
}
