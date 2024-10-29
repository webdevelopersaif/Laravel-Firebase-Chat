<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class FirebaseController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function checkFirebaseConnection()
    {
        try {

            // Fetch the reference to 'users' node
            $reference = $this->database->getReference('users');

            // Retrieve the existing data for the user if exists (based on unique key or identifier)
            $userKey = '-O9FCpeRj83H9ojh1HP8'; // Replace this with the actual user key you want to update

            // If the user already exists, update their password without changing the name
            $existingData = $reference->getChild($userKey)->getValue();

            if ($existingData) {
                // Update only the password field without overwriting the name
                $reference->getChild($userKey)
                    ->update([
                        'password' => bcrypt('msp@123'), // Secure the password
                        'timestamp' => now(),
                    ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Password updated successfully for user: ' . $existingData['name'],
                    'data' => $existingData
                ]);
            } else {
                // If the user doesn't exist, create a new one
                $newUser = $reference->push([
                    'name' => 'jaimin',
                    'password' => bcrypt('your_password_here'), // Secure the password
                    'timestamp' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'New user created successfully!',
                    'data' => $newUser->getValue()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error connecting to Firebase: ' . $e->getMessage(),
            ], 500);
        }
    }
}
