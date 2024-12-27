<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="<?= base_url('Slashfavicon.png') ?>" type="image/x-icon">
    <title>Chat</title>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900">
    <!-- Navigation -->
    <nav class="bg-gray-400 text-white px-4 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <!-- Adjusted img size -->
                <img src="<?= base_url('Slashfavicon.png') ?>" alt="slash" class="w-10 h-10 mr-2">
                <!-- Changed width and height to 14 (larger) -->
                <h1 class="text-2xl font-semibold">Admin Dashboard</h1>
            </div>
            <div class="flex items-center space-x-4">
                <h1 class="text-lg hidden sm:block"><?php print_r(ucfirst(session()->get('user')->name)) ?></h1>
                <a href="/logout"
                    class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm sm:text-base">
                    Logout
                </a>
            </div>
        </div>
    </nav>
    <nav class="bg-gray-300 text-black px-4 py-4">
        <div class="flex justify-evenly items-center">
            <p class="text-lg font-semibold">Dashboard</p>
            <p class="text-lg font-semibold">Live</p>
            <p class="text-lg font-semibold">Conversations</p>
            <p class="text-lg font-semibold">Reports</p>
            <div class="dropdown p-2">
                <a class="text-lg font-semibold px-4 py-2" href="#">Operations</a>
                <div class="dropdown-content">
                    <a href="/users">Users</a>
                    <a href="/campaigns">Campaigns</a>
                    <a href="/accesslevel">Access Level</a>
                    <a href="/chats">Chat</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="h-screen flex flex-col md:flex-row bg-gray-100">
        <nav class="w-full md:w-80 bg-white border-b md:border-r border-gray-200 flex-none md:h-screen">
            <div class="p-4 border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-800">Contacts</h1>
            </div>
            <div class="overflow-y-auto md:h-[calc(100vh-73px)]">
                <div
                    class="contact hover:bg-gray-50 p-4 cursor-pointer border-b border-gray-100 flex items-center space-x-4">
                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde" alt="Contact 1"
                        class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-800"><?php print_r(ucfirst(session()->get('user')->name)) ?></h3>
                        <p class="text-sm text-gray-500 truncate">Hey, how are you doing?</p>
                    </div>
                </div>
                <div
                    class="contact hover:bg-gray-50 p-4 cursor-pointer border-b border-gray-100 flex items-center space-x-4">
                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330" alt="Contact 2"
                        class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-800">Afreen</h3>
                        <p class="text-sm text-gray-500 truncate">Let's meet tomorrow!</p>
                    </div>
                </div>
                <div
                    class="contact hover:bg-gray-50 p-4 cursor-pointer border-b border-gray-100 flex items-center space-x-4">
                    <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36" alt="Contact 3"
                        class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-800">Ibtesam</h3>
                        <p class="text-sm text-gray-500 truncate">Thanks for your help!</p>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 p-4 flex items-center space-x-4">
                <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde" alt="Current chat"
                    class="w-12 h-12 rounded-full object-cover">
                <div class="flex-1">
                    <h2 class="font-medium text-gray-800"><?php print_r(ucfirst(session()->get('user')->name)) ?></h2>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-sm text-gray-500">Online</span>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" id="messages">

            </div>

            <footer class="bg-white border-t border-gray-200 p-4">
                <div class="flex space-x-4 items-center">
                    <input type="text" id="message" placeholder="Type a message..."
                        class="flex-1 rounded-full border border-gray-300 px-4 py-2 focus:outline-none focus:border-blue-500"
                        aria-label="Type a message">
                    <button
                        class="bg-blue-500 text-white rounded-full p-3 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </footer>
        </main>
    </div>

    <!-- --------------------------------------------script--------------------------------------------------------------------- -->
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"
        integrity="sha384-mkQ3/7FUtcGyoppY6bz/PORYoGqOl7/aSUMn2ymDOJcapfS6PHqxhRTMh1RR0Q6+"
        crossorigin="anonymous"></script>
    <script>
        const socket = io("http://localhost:3000");

        async function socketConnection() {
            try {
                //---------------------fetch--stored--message--from--db------------------------------------------
                const result = await fetch('http://localhost:3000/api/get-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify({ email: "<?= session()->get('user')->email ?>", name: "<?= session()->get('user')->name ?>" }) //-----get--message where user is from value


                })

                const message = await result.json();
                console.log(message)
                const messagesDiv = document.getElementById('messages');
                message.messages.forEach((message, i) => {
                    let messageElement = document.createElement('div');
                    messageElement.id = i;
                    messageElement.className = 'flex items-end justify-end space-x-2 ';
                    messageElement.innerHTML = `<div class="max-w-md bg-green-200 rounded-lg p-3 shadow-sm ">
                                            <p class="text-gray-800 text-pretty font-bold">${message.name}</p>
                                            <p class="text-gray-800 text-pretty">${message.message}</p>
                                            <span class="text-xs text-gray-500">${new Date(message.timestamp).toLocaleTimeString()}</span>
                                            
                                        </div>`;
                    messagesDiv.appendChild(messageElement);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                })
                socket.emit('join', {
                    data: "<?= session()->get('user')->email ?>, <?= session()->get('user')->name ?>",
                });
            } catch (err) {
                console.log(err);
            }
        }
        socketConnection();

        socket.on('receive_message', (message) => {

            const messagesDiv = document.getElementById('messages');
            const messageElement = document.createElement('div');
            messageElement.className = 'flex items-end space-x-2';
            messageElement.innerHTML = `<div class="max-w-md bg-white rounded-lg p-3 shadow-sm">
                                            <p class="text-gray-800 text-pretty font-bold">${name}</p>
                                            <p class="text-gray-800">${message}</p>
                                            <span class="text-xs text-gray-500">${new Date().toLocaleTimeString()}</span>
                                        </div>`;
            messagesDiv.appendChild(messageElement);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            console.log(message)
        });

        async function sendMessage() {
            const message = document.getElementById('message').value;
            if (message !== "") {
                socket.emit('send_message', {
                    message: message
                });
                const messagesDiv = document.getElementById('messages');
                const messageElement = document.createElement('div');
                messageElement.className = 'flex items-end justify-end space-x-2 ';
                messageElement.innerHTML = `<div class="max-w-md bg-green-200 rounded-lg p-3 shadow-sm ">
                                            <p class="text-gray-800 text-pretty font-bold">${name}</p>                            
                                            <p class="text-gray-800 text-pretty">${message}</p>
                                            <span class="text-xs text-gray-500">${new Date().toLocaleTimeString()}</span>
                                        </div>`;
                messagesDiv.appendChild(messageElement);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                console.log(message)
                document.getElementById('message').value = "";
            }
            // ------------------------sending--mongodb---------------------------------------
            const result = await fetch('http://localhost:3000/api/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({
                    email: "<?= session()->get('user')->email ?>",
                    name: "<?= session()->get('user')->name ?>",
                    message
                })
            })
        }
    </script>
</body>

</html>