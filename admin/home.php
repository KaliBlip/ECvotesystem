<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Voting System Admin - Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
            <a href="home.php" class="nav-item active">
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
        <div class="dashboard-stats">
            <!-- Positions Card -->
            <div class="stat-card">
                <div class="stat-info">
                    <?php
                        $sql = "SELECT * FROM positions";
                        $query = $conn->query($sql);
                        $positions_count = $query->num_rows;
                    ?>
                    <h3><?php echo $positions_count; ?></h3>
                    <p>Total Positions</p>
                </div>
                <div class="stat-icon positions">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>

            <!-- Candidates Card -->
            <div class="stat-card">
                <div class="stat-info">
                    <?php
                        $sql = "SELECT * FROM candidates";
                        $query = $conn->query($sql);
                        $candidates_count = $query->num_rows;
                    ?>
                    <h3><?php echo $candidates_count; ?></h3>
                    <p>Total Candidates</p>
                </div>
                <div class="stat-icon candidates">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>

            <!-- Voters Card -->
            <div class="stat-card">
                <div class="stat-info">
                    <?php
                        $sql = "SELECT * FROM voters";
                        $query = $conn->query($sql);
                        $voters_count = $query->num_rows;
                    ?>
                    <h3><?php echo $voters_count; ?></h3>
                    <p>Total Voters</p>
                </div>
                <div class="stat-icon voters">
                    <i class="fas fa-users"></i>
                </div>
            </div>

            <!-- Votes Card -->
            <div class="stat-card">
                <div class="stat-info">
                    <?php
                        $sql = "SELECT DISTINCT voters_id FROM votes";
                        $query = $conn->query($sql);
                        $votes_count = $query->num_rows;
                    ?>
                    <h3><?php echo $votes_count; ?></h3>
                    <p>Voters Voted</p>
                </div>
                <div class="stat-icon votes">
                    <i class="fas fa-vote-yea"></i>
                </div>
            </div>
        </div>

        <!-- Votes Tally -->
        <div class="votes-tally">
            <div class="tally-header">
                <h2>VOTES TALLY</h2>
                <button class="print-btn" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    Print
                </button>
            </div>

            <div class="position-results">
                <?php
                    $sql = "SELECT * FROM positions ORDER BY priority ASC";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                        echo '
                            <div class="position-card">
                                <h3>'.$row['description'].'</h3>
                        ';

                        $sql = "SELECT candidates.*, COUNT(votes.id) as vote_count 
                                FROM candidates 
                                LEFT JOIN votes ON candidates.id = votes.candidate_id 
                                WHERE candidates.position_id = '".$row['id']."' 
                                GROUP BY candidates.id 
                                ORDER BY vote_count DESC";
                        $cquery = $conn->query($sql);
                        while($crow = $cquery->fetch_assoc()){
                            $image = (!empty($crow['photo'])) ? '../images/'.$crow['photo'] : '../images/profile.jpg';
                            echo '
                                <div class="candidate-bar">
                                    <div class="candidate-info">
                                        <img src="'.$image.'" class="candidate-photo">
                                        <span class="candidate-name">'.$crow['firstname'].' '.$crow['lastname'].'</span>
                                    </div>
                                    <span class="vote-count">'.$crow['vote_count'].'</span>
                                </div>
                            ';
                        }
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>

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
        });
    </script>
</body>
</html>
