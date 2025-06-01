<?php
namespace App\Http\Controllers;

use App\Models\PetBotMessage;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function index(Request $request)
    {
        $messages = $request->user()->petbotMessages()->oldest()->get();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'sender' => 'required|in:user,bot',
        ]);

        $message = $request->user()->petbotMessages()->create([
            'content' => $request->content,
            'sender' => $request->sender,
        ]);

        return response()->json($message);
    }
}
