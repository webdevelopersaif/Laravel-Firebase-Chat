<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class UserController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }


    public function index()
    {
        // Fetch the users from Firebase Realtime Database
        $users = $this->database->getReference('users')->getValue();

        // dd($users);
        // Pass the users list to the Blade view
        return view('users.index', ['users' => $users]);
    }

    public function show($userId)
    {
        $userRef = $this->database->getReference('users/' . $userId);
        $userData = $userRef->getValue();

        $userName = isset($userData['name']) ? $userData['name'] : 'Unknown User';

        return view('chat.show', ['userId' => $userId, 'userName' => $userName]);
    }

    public function sendMessage(Request $request, $userId)
    {
        $message = $request->input('message');

        // Store the message in Firebase
        $this->database
            ->getReference('chats/' . $userId)
            ->push([
                'text' => $message,
                'sender' => 'Admin',
                'timestamp' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    public function getMessages($userId)
    {
        $messages = $this->database
            ->getReference('chats/' . $userId)
            ->getValue();

        // Return messages as a JSON response
        return response()->json($messages);
    }

    public function login(Request $request)
    {

        // dd($request->input('name'));
        // Validate incoming request
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Get the reference to the 'users' node in Firebase
            $reference = $this->database->getReference('users');
           
            // Query Firebase for the user by their name
            $users = $reference->orderByChild('name')->equalTo($request->input('name'))->getValue();
            // dd($users);
            if ($users) {
                // Firebase returns an array, so loop through it to find the matching user
                foreach ($users as $key => $user) {
                    // Check if the password matches
                    if (\Illuminate\Support\Facades\Hash::check($request->input('password'), $user['password'])) {

                        $user['user_key'] = $key;
                        // Password matches, login successful
                        return response()->json([
                            'success' => true,
                            'message' => 'Login successful',
                            'data' => $user // You can send back any user data as needed
                        ]);
                    }
                }

                // If no password matches, return an error
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password',
                ], 401);
            } else {
                // If no user was found with the given name
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
