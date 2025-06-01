<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Thread;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function threads(Request $request)
    {
        $user = $request->user();

        $threads = Thread::whereHas('participants', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with([
                'participants' => function($q) {
                    // pull only the columns you actually have
                    $q->select('users.id', 'users.first_name', 'users.last_name', 'users.profilePictureURL');
                },
                'messages' => function($q) {
                    $q->latest()->limit(1)
                      ->with(['user' => function($q) {
                          $q->select('users.id', 'users.first_name', 'users.last_name', 'users.profilePictureURL');
                      }]);
                },
            ])
            ->get();

        return response()->json($threads);
    }

    public function messages(Request $request, Thread $thread)
    {
        $this->authorize('view', $thread);

        $messages = $thread->messages()
            ->with(['user' => function($q) {
                $q->select('users.id', 'users.first_name', 'users.last_name', 'users.profilePictureURL');
            }])
            ->get();

        return response()->json($messages);
    }

    public function send(Request $request, Thread $thread)
    {
        $this->authorize('send', $thread);

        $data = $request->validate(['body' => 'required|string']);

        $msg = $thread->messages()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        // include the real user columns
        return response()->json($msg->load(['user' => function($q) {
            $q->select('users.id', 'users.first_name', 'users.last_name', 'users.profilePictureURL');
        }]));
    }
}
