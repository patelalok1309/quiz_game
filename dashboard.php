<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <style>
        body {
            display: flex;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            width: 220px;
            background: #343a40;
            color: white;
            min-height: 100vh;
            padding: 15px;
            position: fixed;
            transform: translateX(0%);
            transition: transform 0.3s ease-in-out;
            z-index: 10;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar a {
            color: white;
            display: flex;
            align-items: center;
            padding: 10px;
            text-decoration: none;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background: #495057;
            border-radius: 8px;
        }

        .content {
            margin-left: 220px;
            padding: 12px;
            width: 100%;
            transition: margin-left 0.3s ease-in-out;
        }

        .toggle-btn {
            display: none;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .toggle-btn {
                display: block;
            }
        }

        .sidebar a.active {
            background: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->


    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="d-flex justify-content-center align-items-center">
            <h4 class="me-auto">Admin Panel</h4>
            <i class="bi bi-x" style="cursor: pointer; font-size: 1.5rem;" id="sidebarClose"></i>
        </div>
        <a href="#" class="tab-link" data-target="dashboard"><i class="bi bi-house-door"></i> <span>Dashboard</span></a>
        <a href="#" class="tab-link" data-target="quizzes"><i class="bi bi-question-circle"></i> <span>Quizzes</span></a>
        <a href="#" class="tab-link" data-target="users"><i class="bi bi-people"></i> <span>Users</span></a>
        <a href="#" class="tab-link" data-target="settings"><i class="bi bi-gear"></i> <span>Settings</span></a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <nav class="navbar navbar-light bg-light d-flex justify-content-between">
            <button class="btn btn-dark toggle-btn" id="sidebarToggle">☰</button>
            <span class="navbar-brand mb-0 h1">Admin Dashboard</span>
        </nav>

        <!-- Dashboard -->
        <div class="tab-content active" id="dashboard">
            <h3>Welcome to the Admin Dashboard</h3>
            <p>Manage your application from here.</p>
        </div>

        <!-- Questions -->
        <?php include("./tabs/quizzes.php") ?>

        <!-- Users -->
        <?php include("./tabs/users.php") ?>

        <!-- Settings -->
        <div class="tab-content" id="settings">
            <h3>Settings</h3>
            <p>Configure application settings.</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#quizTable').DataTable();

            // Sidebar Toggle
            $('#sidebarToggle, #sidebarClose').click(function() {
                $('#sidebar').toggleClass('show');
            });

            // Tab Navigation
            $('.tab-link').click(function(e) {
                e.preventDefault();

                let targetTab = $(this).data('target');

                // Remove active class from all and add to clicked one
                $('.tab-link').removeClass('active');
                $(this).addClass('active');

                // Hide all and show the selected tab
                $('.tab-content').removeClass('active');
                $('#' + targetTab).addClass('active');

                // Store last active tab in localStorage
                localStorage.setItem('activeTab', targetTab);
                localStorage.setItem('activeSidebar', targetTab);
            });

            // Remember last active tab and sidebar item
            let activeTab = localStorage.getItem('activeTab');
            let activeSidebar = localStorage.getItem('activeSidebar');

            if (activeTab) {
                $('.tab-content').removeClass('active');
                $('#' + activeTab).addClass('active');
            }

            if (activeSidebar) {
                $('.tab-link').removeClass('active');
                $('.tab-link[data-target="' + activeSidebar + '"]').addClass('active');
            }
        });
    </script>
</body>

</html>