<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Voting System Admin - Voters</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="home.php" class="sidebar-brand">
                <i class="fas fa-vote-yea"></i>
                <span>Voting System</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">REPORTS</div>
            <a href="home.php" class="nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="votes.php" class="nav-item">
                <i class="fas fa-vote-yea"></i>
                <span>Votes</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">MANAGE</div>
            <a href="voters.php" class="nav-item active">
                <i class="fas fa-users"></i>
                <span>Voters</span>
            </a>
            <a href="positions.php" class="nav-item">
                <i class="fas fa-tasks"></i>
                <span>Positions</span>
            </a>
            <a href="candidates.php" class="nav-item">
                <i class="fas fa-user-tie"></i>
                <span>Candidates</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">SETTINGS</div>
            <a href="ballot.php" class="nav-item">
                <i class="fas fa-file-alt"></i>
                <span>Ballot Position</span>
            </a>
            <a href="#" class="nav-item" onclick="document.getElementById('logout').click();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <form action="logout.php" method="POST" style="display: none;">
                <button id="logout" type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h2>Voters List</h2>
            <div class="header-actions">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addnew">
                    <i class="fas fa-plus"></i> New Voter
                </button>
            </div>
        </div>

        <?php
        if(isset($_SESSION['error'])){
            echo "
                <div class='alert alert-danger'>
                    ".$_SESSION['error']."
                </div>
            ";
            unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
            echo "
                <div class='alert alert-success'>
                    ".$_SESSION['success']."
                </div>
            ";
            unset($_SESSION['success']);
        }
        ?>

        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <div class="search-box">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div>
                    <select class="form-control" id="entriesPerPage">
                        <option value="10">10 entries</option>
                        <option value="25">25 entries</option>
                        <option value="50">50 entries</option>
                        <option value="100">100 entries</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Lastname <i class="fas fa-sort"></i></th>
                            <th>Firstname <i class="fas fa-sort"></i></th>
                            <th>Voter ID</th>
                            <th>Department <i class="fas fa-sort"></i></th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM voters";
                        $query = $conn->query($sql);
                        while($row = $query->fetch_assoc()){
                            $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                            echo "
                                <tr>
                                    <td>
                                        <img src='".$image."' class='voter-photo'>
                                    </td>
                                    <td>".$row['lastname']."</td>
                                    <td>".$row['firstname']."</td>
                                    <td>".$row['voters_id']."</td>
                                    <td>".$row['department']."</td>
                                    <td>".$row['gender']."</td>
                                    <td>
                                        <div class='action-buttons'>
                                            <button class='btn-icon edit' data-id='".$row['id']."' title='Edit'>
                                                <i class='fas fa-edit'></i>
                                            </button>
                                            <button class='btn-icon delete' data-id='".$row['id']."' title='Delete'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="pagination-info">
                    Showing <span>1</span> to <span>10</span> of <span>3</span> entries
                </div>
                <div class="pagination">
                    <button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>
                    <button class="btn-page active">1</button>
                    <button class="btn-page" disabled><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/voters_modal.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.createElement('button');
            menuToggle.className = 'menu-toggle';
            menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(menuToggle);

            menuToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const tbody = document.querySelector('tbody');
                const rows = tbody.getElementsByTagName('tr');

                for (let row of rows) {
                    const cells = row.getElementsByTagName('td');
                    let found = false;
                    for (let cell of cells) {
                        if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                    row.style.display = found ? '' : 'none';
                }
            });

            // Entries per page
            const entriesSelect = document.getElementById('entriesPerPage');
            entriesSelect.addEventListener('change', function() {
                // Add pagination logic here
            });
        });

        // Modal handlers
        $(document).ready(function() {
            // Edit button click handler
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                getRow(id);
                $('#edit').modal('show');
            });

            // Delete button click handler
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                getRow(id);
                $('#delete').modal('show');
            });

            // Photo edit button click handler
            $(document).on('click', '.photo', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                getRow(id);
                $('#edit_photo').modal('show');
            });
        });

        // Get row data function
        function getRow(id) {
            $.ajax({
                type: 'POST',
                url: 'voters_row.php',
                data: {id: id},
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response); // Debug log
                    $('.id').val(response.id);
                    $('#edit_firstname').val(response.firstname);
                    $('#edit_lastname').val(response.lastname);
                    $('#edit_password').val(response.password);
                    $('#edit_department').val(response.department);
                    $('#edit_gender').val(response.gender);
                    $('.fullname').html(response.firstname + ' ' + response.lastname);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error); // Debug log
                }
            });
        }
    </script>
</body>
</html>
