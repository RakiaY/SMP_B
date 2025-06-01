<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulation;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $user = $request->user();

        $notifications = Postulation::with(['search.pet', 'sitter'])
            ->whereHas('search', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('statut', 'en_attente')
            ->get()
            ->map(function ($postulation) {
                $sitter = $postulation->sitter;
                $sitterName = $sitter ? trim($sitter->first_name . ' ' . $sitter->last_name) : 'Sitter inconnu';
                $sitterPhoto = $sitter && $sitter->profilePictureURL
                    ? url('storage/' . $sitter->profilePictureURL)
                    : '/assets/default-avatar.png';

                $petName = $postulation->search->pet->name ?? 'un animal';

                return [
                    'id' => $postulation->id,
                    'sitterId' => $sitter?->id,
                    'sitterName' => $sitterName,
                    'sitterPhoto' => $sitterPhoto,
                    'petName' => $petName,
                    'message' => "veut postuler pour la garde de $petName.",
                ];
            });

        return response()->json($notifications);
    }

    public function getSitterNotifications(Request $request)
    {
        $user = $request->user();

        $notifications = Postulation::with(['search.pet', 'search.user'])
            ->where('sitter_id', $user->id)
            ->whereIn('statut', ['validée', 'annulée'])
            ->get()
            ->map(function ($postulation) {
                $owner = $postulation->search->user;
                $ownerName = $owner ? $owner->first_name . ' ' . $owner->last_name : 'Propriétaire inconnu';
                $ownerPhoto = $owner && $owner->profilePictureURL
                    ? url('storage/' . $owner->profilePictureURL)
                    : '/assets/default-avatar.png';

                $petName = $postulation->search->pet->name ?? 'un animal';
                $status = $postulation->statut == 'validée' ? 'validée' : 'annulée';

                return [
                    'id' => $postulation->id,
                    'ownerName' => $ownerName,
                    'ownerPhoto' => $ownerPhoto,
                    'petName' => $petName,
                    'message' => "$ownerName a $status votre demande pour la garde de $petName.",
                ];
            });

        return response()->json($notifications);
    }
}
