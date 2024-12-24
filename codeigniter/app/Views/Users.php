<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="<?= base_url('Slashfavicon.png') ?>" type="image/x-icon">
    <title>Users</title>
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
    <nav class="mb-5 bg-gray-300 text-black px-4 py-4">
        <div class="flex justify-evenly items-center">
            <p class="text-lg font-semibold">Dashboard</p>
            <p class="text-lg font-semibold">Live</p>
            <p class="text-lg font-semibold">Conversations</p>
            <p class="text-lg font-semibold">Reports</p>
            <div class="dropdown p-2">
                <a class="text-lg font-semibold px-4 py-2" href="#">Operations</a>
                <div class="rounded-md dropdown-content">
                    <a href="/users">Users</a>
                    <a href="/campaigns">Campaigns</a>
                    <a href="/chats">Chat</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Main content -->
    <div class="flex justify-center items-center p-4">
        <!-- Dashboard Table Container -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
            <!-- Header Section with Space Between -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-3xl font-semibold text-gray-800 mb-4 sm:mb-0 sm:text-left">Users</h1>
                <div class="text-4xl cursor-pointer" onclick="openAddModal()">
                    <p>+</p>
                </div>
                <!-- Add User Modal -->
                <div id="addModal"
                    class="absolute inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50 hidden">
                    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm mx-4 sm:mx-8">
                        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Add New User</h2>
                        <form action="<?= base_url("/adduser") ?>" method="post">
                            <!-- Name Field -->
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
                                <input type="text" id="name" name="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="Enter your name" required>
                            </div>

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="Enter your email" required>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-6">
                                <label for="password"
                                    class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="Enter your password" required>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-between">
                                <button type="button" onclick="closeAddModal()"
                                    class="bg-gray-400 text-white px-4 py-2 rounded mb-2 sm:mb-0">Cancel</button>
                                <!-- Submit Button -->
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Add User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Table Start -->
            <table class="min-w-full table-auto border-collapse" id="userTable">
                <thead>
                    <tr class="bg-indigo-600 text-white">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">UserRole</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $row) { ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?php echo $row->id; ?></td>
                            <td class="px-4 py-2"><?php echo $row->name; ?></td>
                            <td class="px-4 py-2"><?php echo $row->email; ?></td>
                            <td class="px-4 py-2"><?php echo $row->userRole; ?></td>
                            <td class="px-4 py-2 text-center">
                                <!-- Edit Button with Data -->
                                <button
                                    class="bg-green-400 text-white py-1 px-4 rounded hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 mr-2"
                                    onclick="openEditModal(<?php echo $row->id; ?>,'<?php echo $row->name; ?>', '<?php echo $row->email; ?>','<?php echo $row->userRole; ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>

                                <!-- Delete Button with Data -->
                                <button
                                    class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    onclick="confirmDelete('<?php echo $row->id; ?>')">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div id="pagination" class="flex justify-between mt-6">
                <button onclick="changePage(-1)"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 focus:outline-none">Previous</button>
                <button onclick="changePage(1)"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 focus:outline-none">Next</button>
            </div>
        </div>
    </div>
    <!-- Edit User Modal -->
    <div id="editModal" class="absolute inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm mx-4 sm:mx-8">
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Edit User</h2>
            <form id="editForm" action="/updateuser" method="POST">
                <div class="mb-4" hidden>
                    <label for="editId" class="block text-gray-700">Id</label>
                    <input type="text" name="id" id="editId" class="w-full p-2 border border-gray-300 rounded mt-2"
                        readonly required>
                </div>
                <div class="mb-4">
                    <label for="editName" class="block text-gray-700">Name</label>
                    <input type="text" name="name" id="editName" class="w-full p-2 border border-gray-300 rounded mt-2"
                        required>
                </div>
                <div class="mb-4">
                    <label for="editEmail" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="editEmail"
                        class="w-full p-2 border border-gray-300 rounded mt-2" required>
                </div>
                <div class="mb-4">
                    <label for="edituserRole" class="block text-gray-700">UserRole</label>
                    <input type="text" name="userRole" id="edituserRole"
                        class="w-full p-2 border border-gray-300 rounded mt-2" required>
                </div>
                <div class="flex flex-col sm:flex-row justify-between">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-400 text-white px-4 py-2 rounded mb-2 sm:mb-0">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Pagination state variables
        let currentPage = 1;
        const rowsPerPage = 5;

        // Function to show the correct users for the current page
        function paginateUsers() {
            const rows = document.querySelectorAll('#userTable tbody tr');
            const totalRows = rows.length;
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            // Hide all rows first
            rows.forEach(row => row.style.display = 'none');

            // Show the rows for the current page
            for (let i = startIndex; i < endIndex && i < totalRows; i++) {
                rows[i].style.display = '';
            }
        }

        // Change page based on direction (-1 for previous, 1 for next)
        function changePage(direction) {
            const rows = document.querySelectorAll('#userTable tbody tr');
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            currentPage += direction;

            // Prevent going out of bounds
            if (currentPage < 1) {
                currentPage = 1;
            } else if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            paginateUsers();
        }

        // Initialize pagination on page load
        window.onload = function () {
            paginateUsers();
        };


        // Function to open Add Modal
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        // Function to close Add Modal
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        // Function to open Edit Modal
        function openEditModal(id, name, email, userRole) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('edituserRole').value = userRole;
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Function to close Edit Modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                // Send DELETE request to backend
                window.location.href = '/deleteuser/' + id;
            }
        }
    </script>
</body>

</html>