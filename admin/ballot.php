<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Voting System Admin - Ballot Position</title>
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
            <a href="ballot.php" class="nav-item active">
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
            <h2>Ballot Position</h2>
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

        <div class="ballot-container">
            <?php
            $sql = "SELECT * FROM positions ORDER BY priority ASC";
            $query = $conn->query($sql);
            $inc = 2;
            while($row = $query->fetch_assoc()){
                $inc = ($inc == 2) ? 1 : $inc+1; 
                if($inc == 1) echo "<div class='row'>";
                echo "
                    <div class='col-md-6'>
                        <div class='ballot-position-card'>
                            <div class='ballot-position-header'>
                                <h3>".$row['description']."</h3>
                                <div class='ballot-position-actions'>
                                    <button class='btn btn-sm btn-light moveup' data-id='".$row['id']."' title='Move Up'>
                                        <i class='fas fa-chevron-up'></i>
                                    </button>
                                    <button class='btn btn-sm btn-light movedown' data-id='".$row['id']."' title='Move Down'>
                                        <i class='fas fa-chevron-down'></i>
                                    </button>
                                </div>
                            </div>
                            <div class='ballot-candidates'>
                ";

                $sql = "SELECT * FROM candidates WHERE position_id = '".$row['id']."'";
                $cquery = $conn->query($sql);
                while($crow = $cquery->fetch_assoc()){
                    $image = (!empty($crow['photo'])) ? '../images/'.$crow['photo'] : '../images/profile.jpg';
                    echo "
                        <div class='ballot-candidate'>
                            <div class='candidate-img'>
                                <img src='".$image."' alt='".$crow['firstname']." ".$crow['lastname']."'>
                            </div>
                            <div class='candidate-info'>
                                <h4>".$crow['firstname']." ".$crow['lastname']."</h4>
                                <button class='btn btn-sm btn-info view-platform' data-id='".$crow['id']."'>
                                    <i class='fas fa-eye'></i> About Me
                                </button>
                            </div>
                        </div>
                    ";
                }

                echo "
                            </div>
                        </div>
                    </div>
                ";
                if($inc == 2) echo "</div>";
            }
            if($inc == 1) echo "<div class='col-md-6'></div></div>";
            ?>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>
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
        });

        $(function(){
            $(document).on('click', '.moveup', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'ballot_up.php',
                    data: {id:id},
                    dataType: 'json',
                    success: function(response){
                        if(response.error){
                            alert(response.message);
                        }
                        else{
                            location.reload();
                        }
                    }
                });
            });

            $(document).on('click', '.movedown', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'ballot_down.php',
                    data: {id:id},
                    dataType: 'json',
                    success: function(response){
                        if(response.error){
                            alert(response.message);
                        }
                        else{
                            location.reload();
                        }
                    }
                });
            });

            $(document).on('click', '.view-platform', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'candidates_row.php',
                    data: {id:id},
                    dataType: 'json',
                    success: function(response){
                        $('.fullname').html(response.firstname + ' ' + response.lastname);
                        $('#desc').html(response.platform);
                        $('#platform').modal('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
