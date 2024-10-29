<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with User</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Firebase SDK -->
    <script type="module">
        // Import the functions you need from the SDKs
        import {
            getAuth,
            signInAnonymously
        } from "https://www.gstatic.com/firebasejs/9.16.0/firebase-auth.js";
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/9.16.0/firebase-app.js";
        import {
            getDatabase,
            ref,
            push,
            onChildAdded
        } from "https://www.gstatic.com/firebasejs/9.16.0/firebase-database.js";

        $(document).ready(function() {
            // Firebase configuration
            const firebaseConfig = {
                apiKey: "AIzaSyB0cKyLdZXWKDQc46eNGEwtm9S_KfXcSio",
                authDomain: "fir-chat-application-a9d9b.firebaseapp.com",
                databaseURL: "https://fir-chat-application-a9d9b-default-rtdb.firebaseio.com",
                projectId: "fir-chat-application-a9d9b",
                storageBucket: "fir-chat-application-a9d9b.appspot.com",
                messagingSenderId: "439405959141",
                appId: "1:439405959141:web:505bc173bddf3fdf72810c",
                measurementId: "G-ZE52S15EK7"
            };

            // Initialize Firebase and Database
            const app = initializeApp(firebaseConfig);
            const db = getDatabase(app);
            const auth = getAuth(app);

            signInAnonymously(auth)
                .then(() => {
                    console.log('Authenticated anonymously');
                    // Now you can interact with the database
                })
                .catch((error) => {
                    console.error('Authentication failed:', error);
                });

            var userId = '{{ $userId }}';

            // Send message to Firebase
            $('#sendMessage').click(function() {
                var message = $('#message').val();

                if (message.trim() !== '') {
                    push(ref(db, 'chats/' + userId), {
                        sender: 'Admin',
                        text: message,
                        timestamp: new Date().toISOString()
                    }).then(() => {
                        $('#message').val(''); // Clear input
                    }).catch((error) => {
                        console.error("Error sending message:", error);
                    });
                }
            });

            // Listen for new messages
            onChildAdded(ref(db, 'chats/' + userId), (snapshot) => {
                var message = snapshot.val();
                displayMessage(message.sender, message.text);
            });


            // Display message in the chat window
            function displayMessage(sender, text) {
                var messagesDiv = $('#messages');
                var messageElement = $('<p></p>').text(sender + ': ' + text);

                // Admin and User message styling
                if (sender === 'Admin') {
                    messageElement.addClass('bg-light text-end p-2 rounded-3 my-2').css({
                        'background-color': '#d4f1f4',
                        'max-width': '70%',
                        'color': 'white',
                        'margin-left': 'auto'
                    });
                } else {
                    messageElement.addClass('bg-light text-start p-2 rounded-3 my-2').css({
                        'background-color': '#f0f0f0',
                        'max-width': '70%',
                        'margin-right': 'auto'
                    });
                }

                if (sender === 'Admin') {
                    messageElement[0].style.setProperty('background-color', '#007bff', 'important');
                } else {
                    messageElement[0].style.setProperty('background-color', '#d1ffe8', 'important');
                }

                messagesDiv.append(messageElement);
                messagesDiv.scrollTop(messagesDiv[0].scrollHeight); // Scroll to bottom
            }
        });
    </script>

    <style>
        #messages {
            border: 1px solid #ccc;
            height: 300px;
            overflow-y: scroll;
            padding: 10px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            resize: none;
        }

        button {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Chat with {{ $userName }}</h1>

        <!-- Chat Message Display -->
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-8">

            </div>
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div id="messages" class="border rounded p-3 bg-white shadow-sm mb-3">
                    <!-- Messages will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-8">

            </div>
            <div class="col-sm-6 col-md-6 col-lg-4">
                <textarea id="message" class="form-control" rows="3" placeholder="Type your message..."></textarea>
                <button id="sendMessage" class="btn btn-primary mt-2">Send</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>