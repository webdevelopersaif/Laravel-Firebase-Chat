<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class ChatController extends Controller
{

    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getMessages($userId)
    {
        // Get chat messages from Firebase or database
        $messages = $this->database->getReference("chats/{$userId}")
                                   ->getValue();
        
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {

        // dd("dsd");
        $messageData = [
            'sender' => 'User', // Set based on logged-in user or user type
            'text' => $request->message,
            'timestamp' => now(),
        ];

        // Save message to Firebase or database
        $this->database->getReference("chats/{$request->userId}")
                       ->push($messageData);

        return response()->json(['success' => true]);
    }
}
