<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Voting System Admin - Votes</title>
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
            <a href="votes.php" class="nav-item active">
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
            <a href="positions.php" class="nav-item">
                <i class="fas fa-tasks"></i>
                <span>Positions</span>
            </a>
            <a href="candidates.php" class="nav-item">
                <i class="fas fa-user-tie"></i>
                <span>Candidates</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1>Votes</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Votes</li>
                    </ol>
                </nav>
            </div>
            <div class="page-actions">
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reset">
                    <i class="fas fa-redo"></i> Reset Votes
                </button>
            </div>
        </div>

        <?php
        if(isset($_SESSION['error'])){
            echo "
                <div class='alert alert-danger alert-dismissible fade show'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <i class='fas fa-exclamation-triangle'></i> ".$_SESSION['error']."
                </div>
            ";
            unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
            echo "
                <div class='alert alert-success alert-dismissible fade show'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    <i class='fas fa-check'></i> ".$_SESSION['success']."
                </div>
            ";
            unset($_SESSION['success']);
        }
        ?>

        <!-- Votes Table Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-tools d-flex justify-content-between align-items-center">
                    <div class="search-box">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search votes...">
                        <i class="fas fa-search"></i>
                    </div>
                    <select class="form-control ml-3" id="entriesPerPage" style="width: auto;">
                        <option value="10">10 entries</option>
                        <option value="25">25 entries</option>
                        <option value="50">50 entries</option>
                        <option value="100">100 entries</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="sortable">Position <i class="fas fa-sort"></i></th>
                            <th class="sortable">Candidate <i class="fas fa-sort"></i></th>
                            <th class="sortable">Voter <i class="fas fa-sort"></i></th>
                            <th class="sortable">Time <i class="fas fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT *, candidates.firstname AS canfirst, candidates.lastname AS canlast, 
                                voters.firstname AS votfirst, voters.lastname AS votlast 
                                FROM votes 
                                LEFT JOIN positions ON positions.id=votes.position_id 
                                LEFT JOIN candidates ON candidates.id=votes.candidate_id 
                                LEFT JOIN voters ON voters.id=votes.voters_id 
                                ORDER BY positions.priority ASC";
                        $query = $conn->query($sql);
                        while($row = $query->fetch_assoc()){
                            echo "
                                <tr>
                                    <td>".$row['description']."</td>
                                    <td>".$row['canfirst'].' '.$row['canlast']."</td>
                                    <td>".$row['votfirst'].' '.$row['votlast']."</td>
                                    <td>".date('M d, Y h:i A')."</td>
                                </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
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

    <?php include 'includes/votes_modal.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        overflow-x: hidden;
    }
    .content {
        padding: 20px;
        margin-left: 250px;
        background-color: #f4f6f9;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        width: calc(100vw - 250px);
        box-sizing: border-box;
    }
    .page-header {
        margin-bottom: 1.5rem;
        width: 100%;
    }
    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        width: 100%;
        min-width: 0; /* Prevents flex item from overflowing */
    }
    .card-header {
        padding: 1rem 1.5rem;
        background: #fff;
        border-bottom: 1px solid #edf2f9;
        width: 100%;
    }
    .table-responsive {
        flex: 1;
        overflow: auto;
        min-height: 400px;
        width: 100%;
    }
    .table {
        margin-bottom: 0;
        width: 100%;
        table-layout: fixed;
    }
    .table thead {
        position: sticky;
        top: 0;
        z-index: 1;
        background: #f8f9fa;
    }
    .table th {
        border-top: none;
        background: #f8f9fa;
        padding: 1rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .table td {
        padding: 1rem;
        vertical-align: middle;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table th:nth-child(1), 
    .table td:nth-child(1) { width: 25%; }
    .table th:nth-child(2), 
    .table td:nth-child(2) { width: 25%; }
    .table th:nth-child(3), 
    .table td:nth-child(3) { width: 25%; }
    .table th:nth-child(4), 
    .table td:nth-child(4) { width: 25%; }
    
    .card-tools {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    .search-box {
        position: relative;
        width: 300px;
        min-width: 200px;
        flex-shrink: 1;
    }
    .card-footer {
        background: #fff;
        padding: 1rem 1.5rem;
        border-top: 1px solid #edf2f9;
        width: 100%;
    }
    .pagination {
        margin: 0;
        gap: 5px;
    }
    .btn-page {
        padding: 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #6c757d;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-page.active {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }
    .btn-page:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
    @media (max-width: 768px) {
        .content {
            margin-left: 0;
            padding: 15px;
            width: 100vw;
        }
        .card-tools {
            flex-direction: column;
            gap: 10px;
        }
        .search-box {
            width: 100%;
        }
        .card-footer {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
        .table th:nth-child(1), 
        .table td:nth-child(1),
        .table th:nth-child(2), 
        .table td:nth-child(2),
        .table th:nth-child(3), 
        .table td:nth-child(3),
        .table th:nth-child(4), 
        .table td:nth-child(4) {
            width: auto;
            min-width: 150px;
        }
    }
    </style>
    <script>
        $(document).ready(function() {
            // Get all table rows
            const tableRows = document.querySelectorAll('tbody tr');
            const totalRows = tableRows.length;
            document.getElementById('totalEntries').textContent = totalRows;

            // Entries per page handler
            const entriesSelect = document.getElementById('entriesPerPage');
            const itemsPerPage = parseInt(entriesSelect.value);
            let currentPage = 1;

            function showPage(page) {
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                
                tableRows.forEach((row, index) => {
                    if (!row.hasAttribute('data-filtered')) {
                        if (index >= start && index < end) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            }

            function updatePagination() {
                const pagination = document.querySelector('.pagination');
                const visibleRows = Array.from(tableRows).filter(row => !row.hasAttribute('data-filtered')).length;
                const totalPages = Math.ceil(visibleRows / itemsPerPage);
                
                let paginationHTML = `
                    <button class="btn-page" ${currentPage === 1 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                `;
                
                for (let i = 1; i <= totalPages; i++) {
                    paginationHTML += `
                        <button class="btn-page ${currentPage === i ? 'active' : ''}">${i}</button>
                    `;
                }
                
                paginationHTML += `
                    <button class="btn-page" ${currentPage === totalPages ? 'disabled' : ''}>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                `;
                
                pagination.innerHTML = paginationHTML;
                
                // Update showing entries text
                const start = ((currentPage - 1) * itemsPerPage) + 1;
                const end = Math.min(currentPage * itemsPerPage, visibleRows);
                document.querySelector('.pagination-info').innerHTML = 
                    `Showing <span>${start}</span> to <span>${end}</span> of <span>${visibleRows}</span> entries`;
            }

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

            // Pagination click handlers
            document.querySelector('.pagination').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-page')) {
                    const text = e.target.textContent.trim();
                    if (text === '') {
                        // Arrow buttons
                        if (e.target.querySelector('.fa-chevron-left')) {
                            if (currentPage > 1) {
                                currentPage--;
                            }
                        } else if (e.target.querySelector('.fa-chevron-right')) {
                            const totalPages = Math.ceil(totalRows / itemsPerPage);
                            if (currentPage < totalPages) {
                                currentPage++;
                            }
                        }
                    } else {
                        // Number buttons
                        currentPage = parseInt(text);
                    }
                    showPage(currentPage);
                    updatePagination();
                }
            });

            // Entries per page change handler
            entriesSelect.addEventListener('change', function() {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                showPage(currentPage);
                updatePagination();
            });
        });
    </script>
</body>
</html>
