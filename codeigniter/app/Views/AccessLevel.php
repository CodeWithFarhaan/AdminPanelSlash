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
    <title>Access Level</title>
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
                <p class="text-black font-semibold">(<?php print_r(ucfirst(session()->get('user')->userRole)) ?>)</p>
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
                    <a href="/accesslevel">Access Level</a>
                    <a href="/chats">Chat</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="bg-gray-100 flex justify-center items-center h-[80%]">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-semibold text-center text-gray-800">Access Level</h1>
            </div>

            <!-- Table Start -->
            <table class="min-w-full table-auto border-collapse" id="AccessLevelTable">
                <thead>
                    <tr class="bg-indigo-600 text-white">
                        <th class="px-4 py-2 text-center">ID</th>
                        <th class="px-4 py-2 text-center">Name</th>
                        <th class="px-4 py-2 text-center">Email</th>
                        <th class="px-4 py-2 text-center">Roles</th>
                        <th class="px-4 py-2 text-center">Update Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2 text-center"><?php echo $user->id; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $user->name; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $user->email; ?></td>
                            <td class="px-4 py-2 text-center"><?php echo $user->userRole; ?></td>
                            <td class="px-4 py-2 text-center">
                                <form action="/update-role/<?php echo $user->id; ?>" method="POST">
                                    <select name="roles" class="px-4 py-2" onchange="this.form.submit()">
                                        <option value="admin" <?php echo ($user->userRole === 'admin') ? 'selected' : ''; ?>>
                                            Admin</option>
                                        <option value="user" <?php echo ($user->userRole === 'user') ? 'selected' : ''; ?>>
                                            User
                                        </option>
                                        <option value="teamLeader" <?php echo ($user->userRole === 'teamLeader') ? 'selected' : ''; ?>>Team Leader</option>
                                        <option value="superVisor" <?php echo ($user->userRole === 'superVisor') ? 'selected' : ''; ?>>Supervisor</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Table End -->
            <!-- Pagination Controls -->
            <div id="pagination" class="flex justify-between mt-6">
                <button onclick="changePage(-1)"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Previous</button>
                <button onclick="changePage(1)" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Next</button>
            </div>
        </div>
    </div>
    <script>
        // Pagination state variables
        let currentPage = 1;
        const rowsPerPage = 3;

        // Function to show the correct rows for the current page
        function paginateAccessLevel() {
            const rows = document.querySelectorAll('#AccessLevelTable tbody tr');
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
            const rows = document.querySelectorAll('#AccessLevelTable tbody tr');
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            currentPage += direction;

            // Prevent going out of bounds
            if (currentPage < 1) {
                currentPage = 1;
            } else if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            paginateAccessLevel();
        }

        // Initialize pagination on page load
        window.onload = function () {
            paginateAccessLevel();
        };

        // Update role based on selection
        function updateRole(id, role) {
            // Send AJAX request to update the user's role
            fetch(`/update-role/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ userRole: role })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Role updated successfully');
                    } else {
                        alert('Error updating role');
                    }
                });
        }
    </script>
</body>

</html>