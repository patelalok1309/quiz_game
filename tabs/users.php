<div class="tab-content" id="quizzes">
    <div class="container mt-4">
        <h2>Manage Users</h2>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus-circle"></i> Add User
        </button>

        <table id="userTable" class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- populate the users here  -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "fetch_users.php",
            success: function(response) {
                console.log(response);
                let res = JSON.parse(response);

                if (res.status === "success") {
                    let rows = '';
                    res.data.forEach(user => {
                        rows += `<tr>
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                    </tr>`;
                    });

                    // Destroy any existing DataTable before updating content
                    if ($.fn.DataTable.isDataTable('#userTable')) {
                        $('#userTable').DataTable().destroy();
                    }

                    $('#userTable tbody').html(rows); // Populate table body
                    $('#userTable').DataTable(); // Reinitialize DataTable
                }
            },
            error: function() {
                alert("Failed to fetch users.");
            }
        });
    });
</script>

</body>

</html>