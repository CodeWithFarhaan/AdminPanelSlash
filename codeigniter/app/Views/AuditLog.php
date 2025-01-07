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
    <title>Audit Logs</title>
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
    <nav class="bg-gray-300 text-black px-4 py-4">
        <?php if (
            $role === 'admin' ||
            ($role === 'user' || $role === 'supervisor' || $role === 'teamLeader')
        ): ?>
            <div class="flex justify-evenly items-center">

                <!-- For Admin: Full access to all sections -->
                <?php if ($role === 'admin'): ?>
                    <p class="px-8 text-lg font-semibold">Dashboard</p>
                    <p class="text-lg font-semibold">Live</p>

                    <!-- Conversations section for Admin -->
                    <div class="dropdown p-2">
                        <a class="text-lg font-semibold px-4 py-2" href="#">Conversations</a>
                        <div class="rounded-md dropdown-content">
                            <a href="/chats">Chat</a>
                        </div>
                    </div>
                    <div class="dropdown p-2">
                        <a class="text-lg font-semibold px-4 py-2" href="#">Report</a>
                        <div class="rounded-md dropdown-content">
                            <a href="/auditlog">Audit Log</a>
                        </div>
                    </div>
                    <div class="dropdown p-2">
                        <a class="text-lg font-semibold px-4 py-2" href="#">Operations</a>
                        <div class="rounded-md dropdown-content">
                            <a href="/users">Users</a>
                            <a href="/campaigns">Campaigns</a>
                            <a href="/accesslevel">Access Level</a>
                        </div>
                    </div>
                    <!-- For Supervisor, User, Team Leader: Limited access -->
                <?php elseif ($role === 'user' || $role === 'supervisor' || $role === 'teamLeader'): ?>
                    <!-- Conversations section for User, Supervisor, Team Leader -->
                    <div class="dropdown p-2">
                        <a class="text-lg font-semibold px-4 py-2" href="#">Conversations</a>
                        <div class="rounded-md dropdown-content">
                            <a href="/chats">Chat</a>
                        </div>
                    </div>
                    <div class="dropdown p-2">
                        <a class="text-lg font-semibold px-4 py-2" href="#">Operations</a>
                        <div class="rounded-md dropdown-content">
                            <a href="/campaigns">Campaigns</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </nav>
    <!-- Main content -->
    <div class="flex justify-center items-center p-4">
        <!-- Dashboard Table Container -->
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
            <!-- Header Section with Space Between -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-3xl font-semibold text-gray-800 mb-4 sm:mb-0 sm:text-left">Audit Logs</h1>
            </div>
            <!-- Table Start -->
            <table class="min-w-full table-auto border-collapse" id="auditlogTable">
                <thead>
                    <tr class="bg-indigo-600 text-white">
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Datetime</th>
                        <th class="px-4 py-2 text-left">Action</th>
                        <th class="px-4 py-2 text-left">UserId</th>
                        <th class="px-4 py-2 text-left">Entity</th>
                        <th class="px-4 py-2 text-left">EntityId</th>
                        <th class="px-4 py-2 text-left">Details</th>
                        <?php if ($role === 'admin'): ?>
                            <th class="px-4 py-2 text-center">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($auditlogs as $row) { ?>
                        <?php if (
                            ($role === 'admin') ||
                            (($role === 'user' || $role === 'supervisor' || $role === 'teamLeader') && $row->userRole !== 'admin')
                        ): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo $row->id; ?></td>
                                <td class="px-4 py-2"><?php echo $row->datetime; ?></td>
                                <td class="px-4 py-2"><?php echo $row->action; ?></td>
                                <td class="px-4 py-2"><?php echo $row->user_id; ?></td>
                                <td class="px-4 py-2"><?php echo $row->entity; ?></td>
                                <td class="px-4 py-2"><?php echo $row->entity_id; ?></td>
                                <td class="px-4 py-2"><?php echo $row->details; ?></td>
                                <?php if ($role === 'admin'): ?>
                                    <td class="px-4 py-2 text-center">
                                        <button
                                            class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                                            onclick="confirmDelete('<?php echo $row->id; ?>')">
                                            <i class="fa-solid fa-trash"></i> 
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
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
    <script>
        // Pagination state variables
        let currentPage = 1;
        const rowsPerPage = 3;

        // Function to show the correct users for the current page
        function paginateAuditLog() {
            const rows = document.querySelectorAll('#auditlogTable tbody tr');
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
            const rows = document.querySelectorAll('#auditlogTable tbody tr');
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            currentPage += direction;

            // Prevent going out of bounds
            if (currentPage < 1) {
                currentPage = 1;
            } else if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            paginateAuditLog();
        }

        // Initialize pagination on page load
        window.onload = function () {
            paginateAuditLog();
        };


        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                // Send DELETE request to backend
                window.location.href = '/deleteuser/' + id;
            }
        }
    </script>
</body>

</html>