<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Voting System Admin - Positions</title>
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
            <a href="voters.php" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Voters</span>
            </a>
            <a href="positions.php" class="nav-item active">
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
            <h2>Positions</h2>
            <div class="header-actions">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addnew">
                    <i class="fas fa-plus"></i> New Position
                </button>
            </div>
        </div>

      <?php
        if(isset($_SESSION['error'])){
          echo "
                <div class='alert alert-danger alert-dismissible fade show'>
              ".$_SESSION['error']."
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
                <div class='alert alert-success alert-dismissible fade show'>
              ".$_SESSION['success']."
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>

        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <div class="search-box">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search positions...">
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
                            <th>Description <i class="fas fa-sort"></i></th>
                            <th>Department Category <i class="fas fa-sort"></i></th>
                            <th>Gender Classification <i class="fas fa-sort"></i></th>
                            <th>Maximum Vote <i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT * FROM positions ORDER BY priority ASC";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      echo "
                                <tr>
                          <td>".$row['description']."</td>
                          <td>".$row['dept_category']."</td>
                          <td>".$row['gender_class']."</td>
                          <td>".$row['max_vote']."</td>
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
                    Showing <span>1</span> to <span>10</span> of <span id="totalEntries">0</span> entries
                </div>
                <div class="pagination">
                    <button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>
                    <button class="btn-page active">1</button>
                    <button class="btn-page" disabled><i class="fas fa-chevron-right"></i></button>
            </div>
          </div>
        </div>
  </div>
    
  <?php include 'includes/positions_modal.php'; ?>

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

            // Get all table rows
            const tableRows = document.querySelectorAll('tbody tr');
            const totalRows = tableRows.length;
            document.getElementById('totalEntries').textContent = totalRows;

            // Pagination variables
            let currentPage = 1;
            let rowsPerPage = 10;
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            // Function to update pagination
            function updatePagination() {
                // Calculate total pages
                totalPages = Math.ceil(totalRows / rowsPerPage);
                
                // Update pagination info
                const startIndex = (currentPage - 1) * rowsPerPage + 1;
                const endIndex = Math.min(startIndex + rowsPerPage - 1, totalRows);
                document.querySelector('.pagination-info span:first-child').textContent = startIndex;
                document.querySelector('.pagination-info span:nth-child(2)').textContent = endIndex;
                
                // Clear existing pagination buttons
                const paginationContainer = document.querySelector('.pagination');
                paginationContainer.innerHTML = '';
                
                // Previous button
                const prevButton = document.createElement('button');
                prevButton.className = 'btn-page';
                prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
                prevButton.disabled = currentPage === 1;
                prevButton.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        showPage(currentPage);
                        updatePagination();
                    }
                });
                paginationContainer.appendChild(prevButton);
                
                // Page buttons (show max 5 pages)
                const maxPagesToShow = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
                let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
                
                if (endPage - startPage + 1 < maxPagesToShow) {
                    startPage = Math.max(1, endPage - maxPagesToShow + 1);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.className = 'btn-page' + (i === currentPage ? ' active' : '');
                    pageButton.textContent = i;
                    pageButton.addEventListener('click', function() {
                        currentPage = i;
                        showPage(currentPage);
                        updatePagination();
                    });
                    paginationContainer.appendChild(pageButton);
                }
                
                // Next button
                const nextButton = document.createElement('button');
                nextButton.className = 'btn-page';
                nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
                nextButton.disabled = currentPage === totalPages;
                nextButton.addEventListener('click', function() {
                    if (currentPage < totalPages) {
                        currentPage++;
                        showPage(currentPage);
                        updatePagination();
                    }
                });
                paginationContainer.appendChild(nextButton);
            }
            
            // Function to show specific page
            function showPage(page) {
                const startIndex = (page - 1) * rowsPerPage;
                const endIndex = startIndex + rowsPerPage;
                
                // Hide all rows
                tableRows.forEach(row => {
                    row.style.display = 'none';
                });
                
                // Show rows for current page
                for (let i = startIndex; i < endIndex && i < totalRows; i++) {
                    if (tableRows[i].style.display !== 'none' || !tableRows[i].hasAttribute('data-filtered')) {
                        tableRows[i].style.display = '';
                    }
                }
            }

            // Entries per page selector
            const entriesPerPage = document.getElementById('entriesPerPage');
            entriesPerPage.addEventListener('change', function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1; // Reset to first page
                showPage(currentPage);
                updatePagination();
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                let visibleCount = 0;

                // Filter rows
                tableRows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    let found = false;
                    
                    for (let cell of cells) {
                        if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                    
                    if (found) {
                        row.removeAttribute('data-filtered');
                        visibleCount++;
                    } else {
                        row.setAttribute('data-filtered', 'true');
                        row.style.display = 'none';
                    }
                });

                // Update pagination after filtering
                document.getElementById('totalEntries').textContent = visibleCount;
                totalRows = visibleCount;
                currentPage = 1;
                showPage(currentPage);
                updatePagination();
            });

            // Initialize pagination
            showPage(currentPage);
            updatePagination();
        });

        // Modal handlers
        $(document).ready(function() {
            $(document).on('click', '.edit', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
                $('#edit').modal('show');
  });

            $(document).on('click', '.delete', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
                $('#delete').modal('show');
  });
});

        function getRow(id) {
  $.ajax({
    type: 'POST',
    url: 'positions_row.php',
                data: {id: id},
    dataType: 'json',
                success: function(response) {
      $('.id').val(response.id);
      $('#edit_description').val(response.description);
      $('#edit_max_vote').val(response.max_vote);
                    $('#edit_dept_category').val(response.dept_category);
                    $('#edit_gender_class').val(response.gender_class);
      $('.description').html(response.description);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
    }
  });
}
</script>
</body>
</html>
