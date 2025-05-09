<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\addAdminRequest;
use App\Http\Requests\updateAdminRequest;

use App\Enums\AdminStatus;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use Illuminate\Support\Facades\Validator;
use App\Enums\Gender;


class SuperAdminController extends Controller
{
public function addAdmin(addAdminRequest $request)  {
        $data = $request->validated();
        //status actif automatiquement des la creation
        $data['status'] = AdminStatus::Active->value;
        $admin = User::create($data);


        //lui attribuer le role:
        $admin->assignRole('admin');

        //affichage 
        return response()->json([
            'success' => true,
            'message' => 'Admin ajouté avec succès',
            'admin' => new AdminResource($admin)
        ]);
    }

public function updateStatusAdmin(Request $request, $admin_id) {

        $validated = $request->validate([
            'status' => ['required', Rule::enum(AdminStatus::class)],
        ]);
        $admin = User::role('admin')->findOrfail($admin_id);
        $admin->status = $request->status;
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Statut de l’admin mis à jour avec succès',
            'status' => $admin->status,
        ]);
    }
public function updateAdmin($admin_id, Request $request){
    $admin = User::role('admin')->findOrFail($admin_id);

    $validator = Validator::make($request->all(), [
        'first_name' => 'sometimes|string|max:255',
        'last_name' => 'sometimes|string|max:255',
        'email' => [
            'sometimes',
            'email',
            'max:255',
            Rule::unique('users')->ignore($admin->id)
        ],
        'phone' => 'sometimes|string|max:20',
        'birth_date' => 'sometimes|date',
        'gender'=> ['sometimes', Rule::enum(Gender::class)],
        'password' => 'nullable|sometimes|min:8|confirmed',
    ], [
        'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
    ]);

    // 3. Si validation échoue, retourner les erreurs
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Erreurs de validation',
            'errors' => $validator->errors()
        ], 422);
    }

    // 4. Préparer les données à mettre à jour
    $data = $request->only([
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'gender'
    ]);
    
    // 5. Nettoyer les données - supprimer les champs non modifiés (null ou vides)
    foreach ($data as $key => $value) {
        if (is_null($value)) {
            unset($data[$key]);
        }
    }

    // 6. Gestion spéciale du mot de passe
    if ($request->filled('password')) {
        $data['password'] = $request->password;
    }

    // 7. Mise à jour de l'admin
    try {
        $admin->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Admin mis à jour avec succès',
            'admin' => new AdminResource($admin)
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function deleteAdmin($admin_id) {

        $admin = User::role('admin')->findOrfail($admin_id);
        $admin->status = AdminStatus::Deleted->value;  //statut devient supprime
        $admin->save();
        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin supprimé avec succés'
        ], 200);
    }
public function forceDeleteAdmin($admin_id) {
        $admin = User::role('admin')->withTrashed()->findOrfail($admin_id);
        $admin->forceDelete();
        return response()->json([
            'success' => true,
            'message' => 'Admin supprimé définitivement'
        ]);
    }

public function restoreAdmin($admin_id) {

        $admin = User::role('admin')->withTrashed()->findOrfail($admin_id);
        $admin->restore();

        $admin->status = AdminStatus::Active->value;  // statut retourne Active
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur restauré avec succès',
            'admin' => new AdminResource($admin),
        ]);
    }

    //filtre
public function getAdmins(Request $request) {
            $admins = User::role('admin')->where('status', '!=', 'Deleted')
            ->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Liste des administrateurs récupérée avec succès',
            'admins' => AdminResource::collection($admins),
            /*'pagination' => [
                'total' => $admins->total(),
                'per_page' => $admins->perPage(),
                'current_page' => $admins->currentPage(),
                'last_page' => $admins->la  ]*/
        ]);
    }
public function getAdminByEmailOrPhone($email_or_phone)
    {
        
        $admins = User::role('admin')
            ->where(function ($query) use ($email_or_phone) {
                $query->where('email', 'LIKE', "%{$email_or_phone}%")
                      ->orWhere('phone', 'LIKE', "%{$email_or_phone}%");
            })
            ->get();
    
        if ($admins->isEmpty()) {
            return response()->json(['message' => 'Aucun Admin avec ces coordonnées'], 404);
        }
    
        return response()->json([
            'admins' => AdminResource::collection($admins),
        ]);
    }
public function getAdminByStatut($status)
    {
        $admins = User::withTrashed()         // Inclut les soft‑deleted
        ->role('admin')
        ->where('status', $status)
        ->get();

        if ($admins->isEmpty()) {
            return response()->json(['message' => 'Aucun Admin avec ce statut'], 404);
        }

        return response()->json([
            'admins' => AdminResource::collection($admins),
        ]);
    }
    
    public function getAdminById($admin_id){
        $admin = User::withTrashed()->role('admin')->find($admin_id); 

        if (!$admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }
         return response()->json([
        'admin'=> new AdminResource($admin),
        'created_at' => $admin->created_at->format('d/m/Y H:i:s'),
        'updated_at' => $admin->updated_at->format('d/m/Y H:i:s'),
'deleted_at' => $admin->deleted_at ? $admin->deleted_at->format('d/m/Y H:i:s') : null    ]);
    }
  

   
   


}
