<?php include 'includes/session.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <h1 class="site-title">ONLINE VOTING SYSTEM</h1>
                <div class="user-info">
                    <img src="<?php echo (!empty($voter['photo'])) ? 'images/'.$voter['photo'] : 'images/profile.jpg'; ?>" class="user-image" alt="User Image">
                    <span class="user-name"><?php echo $voter['firstname'].' '.$voter['lastname']; ?></span>
                    <a href="logout.php" class="logout-btn">LOGOUT</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <?php
                $parse = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
                $title = $parse['election_title'];
            ?>
            <h2 class="election-title"><?php echo strtoupper($title); ?></h2>

            <?php
            if(isset($_SESSION['error'])){
                ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul>
                        <?php
                            foreach($_SESSION['error'] as $error){
                                echo "<li>".$error."</li>";
                            }
                        ?>
                    </ul>
                </div>
                <?php
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])){
                ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $_SESSION['success']; ?>
                </div>
                <?php
                unset($_SESSION['success']);
            }
            ?>

            <div class="alert alert-danger alert-dismissible fade show" id="alert" style="display:none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="message"></span>
            </div>

            <?php
            // First check if user has already voted
            $sql = "SELECT * FROM votes WHERE voters_id = '".$voter['id']."'";
            $vquery = $conn->query($sql);
            if($vquery->num_rows > 0){
                ?>
                <div class="already-voted">
                    <div class="voted-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>You have already voted for this election.</h3>
                    <button type="button" data-toggle="modal" data-target="#view" class="btn btn-primary">
                        <i class="fas fa-eye"></i> View Ballot
                    </button>
                </div>
                <?php
            }
            else{
                // Get positions eligible for this voter based on both department and gender
                $sql = "SELECT * FROM positions WHERE (dept_category = 'all' OR dept_category = '".$voter['department']."') 
                        AND (gender_class = 'all' OR gender_class = '".$voter['gender']."') 
                        ORDER BY priority ASC";
                $pquery = $conn->query($sql);
                
                ?>
                <!-- Voting Ballot -->
                <form method="POST" id="ballotForm" action="submit_ballot.php">
                    <div class="ballot-navigation">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary" id="prev" disabled>
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <span id="position-indicator">Position 1 of <?php echo $pquery->num_rows; ?></span>
                            <button type="button" class="btn btn-primary" id="next">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <?php
                    include 'includes/slugify.php';
                    $candidate = '';
                    $position_count = 0;
                    
                    while($row = $pquery->fetch_assoc()){
                        $position_count++;
                        // Get candidates for this position
                        $sql = "SELECT * FROM candidates WHERE position_id = '".$row['id']."'";
                        $cquery = $conn->query($sql);
                        
                        $candidates_list = '';
                        while($crow = $cquery->fetch_assoc()){
                            $slug = slugify($row['description']);
                            $checked = '';
                            if(isset($_SESSION['post'][$slug])){
                                $value = $_SESSION['post'][$slug];
                                if(is_array($value)){
                                    foreach($value as $val){
                                        if($val == $crow['id']){
                                            $checked = 'checked';
                                        }
                                    }
                                }
                                else{
                                    if($value == $crow['id']){
                                        $checked = 'checked';
                                    }
                                }
                            }
                            
                            $input = ($row['max_vote'] > 1) ? 
                                '<input type="checkbox" class="candidate-select '.$slug.'" name="'.$slug."[]".'" value="'.$crow['id'].'" '.$checked.'>' : 
                                '<input type="radio" class="candidate-select '.$slug.'" name="'.slugify($row['description']).'" value="'.$crow['id'].'" '.$checked.'>';
                            
                            $image = (!empty($crow['photo'])) ? 'images/'.$crow['photo'] : 'images/profile.jpg';
                            
                            $candidates_list .= '
                                <div class="candidate-card" data-candidate-id="'.$crow['id'].'" data-input-name="'.slugify($row['description']).'">
                                    <div class="candidate-select-area">
                                        '.$input.'
                                    </div>
                                    <div class="candidate-photo">
                                        <img src="'.$image.'" alt="'.$crow['firstname'].' '.$crow['lastname'].'">
                                    </div>
                                    <div class="candidate-info">
                                        <h4>'.$crow['firstname'].' '.$crow['lastname'].'</h4>
                                        <button type="button" class="btn btn-sm btn-info platform" data-platform="'.$crow['platform'].'" data-fullname="'.$crow['firstname'].' '.$crow['lastname'].'" data-photo="'.$image.'">
                                            <i class="fas fa-eye"></i> About Me
                                        </button>
                                    </div>
                                </div>
                            ';
                        }

                        $instruct = ($row['max_vote'] > 1) ? 'You may select up to '.$row['max_vote'].' candidates' : 'Select only one candidate';
                        $display = ($position_count == 1) ? 'block' : 'none';

                        echo '
                            <div class="position-section" id="position-'.$row['id'].'" style="display: '.$display.';">
                                <div class="position-header">
                                    <h3>'.$row['description'].'</h3>
                                    <div class="position-actions">
                                        <span class="instruction">'.$instruct.'</span>
                                        <button type="button" class="btn btn-sm btn-secondary reset" data-desc="'.slugify($row['description']).'">
                                            <i class="fas fa-redo"></i> Reset
                                        </button>
                                    </div>
                                </div>
                                <div class="candidates-container">
                                    '.$candidates_list.'
                                </div>
                            </div>
                        ';
                    }
                    ?>

                    <div class="ballot-actions">
                        <button type="button" class="btn btn-info" id="preview">
                            <i class="fas fa-eye"></i> Preview Votes
                        </button>
                        <button type="submit" class="btn btn-success" name="vote">
                            <i class="fas fa-check-circle"></i> Submit Ballot
                        </button>
                    </div>
                </form>
                <!-- End Voting Ballot -->
                <?php
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Nursing University Elections</p>
        </div>
    </footer>

    <?php include 'includes/ballot_modal.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(function(){
        // Platform modal
        $(document).on('click', '.platform', function(e){
            e.preventDefault();
            $('#platform').modal('show');
            var platform = $(this).data('platform');
            var fullname = $(this).data('fullname');
            var photo = $(this).data('photo');
            $('.candidate').html(fullname);
            $('#platform_photo').attr('src', photo);
            $('#plat_view').html(platform);
        });

        // Reset button
        $(document).on('click', '.reset', function(e){
            e.preventDefault();
            var desc = $(this).data('desc');
            $('.'+desc).prop('checked', false);
        });

        // Preview votes
        $('#preview').click(function(e){
            e.preventDefault();
            var form = $('#ballotForm').serialize();
            if(form == ''){
                $('.message').html('You must vote for at least one candidate');
                $('#alert').show();
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: 'preview.php',
                    data: form,
                    dataType: 'json',
                    success: function(response){
                        if(response.error){
                            var errmsg = '';
                            var messages = response.message;
                            for (i in messages) {
                                errmsg += messages[i]; 
                            }
                            $('.message').html(errmsg);
                            $('#alert').show();
                        }
                        else{
                            $('#preview_modal').modal('show');
                            $('#preview_body').html(response.list);
                        }
                    }
                });
            }
        });

        // Navigation
        let currentPosition = 0;
        const positions = document.querySelectorAll('.position-section');
        const totalPositions = positions.length;
        
        // Update progress bar and position indicator
        function updateNavigation() {
            const progress = Math.round(((currentPosition + 1) / totalPositions) * 100);
            $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress).text(progress + '%');
            $('#position-indicator').text('Position ' + (currentPosition + 1) + ' of ' + totalPositions);
            
            // Enable/disable prev button
            if (currentPosition === 0) {
                $('#prev').prop('disabled', true);
            } else {
                $('#prev').prop('disabled', false);
            }
            
            // Change next button to submit on last position
            if (currentPosition === totalPositions - 1) {
                $('#next').html('Review <i class="fas fa-check"></i>').addClass('btn-success').removeClass('btn-primary');
            } else {
                $('#next').html('Next <i class="fas fa-chevron-right"></i>').addClass('btn-primary').removeClass('btn-success');
            }
        }
        
        // Initialize
        updateNavigation();
        
        // Next button
        $('#next').click(function() {
            positions[currentPosition].style.display = 'none';
            
            if (currentPosition === totalPositions - 1) {
                // If on last position, show preview
                $('#preview').click();
            } else {
                currentPosition++;
                positions[currentPosition].style.display = 'block';
                updateNavigation();
            }
        });
        
        // Previous button
        $('#prev').click(function() {
            positions[currentPosition].style.display = 'none';
            currentPosition--;
            positions[currentPosition].style.display = 'block';
            updateNavigation();
        });

        // Candidate card click selection
        $(document).on('click', '.candidate-card', function(e) {
            // Prevent About Me button from triggering card selection
            if($(e.target).closest('button').length > 0) return;
            var input = $(this).find('input.candidate-select');
            if(input.attr('type') === 'checkbox') {
                input.prop('checked', !input.prop('checked')).trigger('change');
            } else if(input.attr('type') === 'radio') {
                input.prop('checked', true).trigger('change');
            }
        });

        // Visual highlight for selected cards
        function updateCardSelection() {
            $('.candidate-card').each(function() {
                var input = $(this).find('input.candidate-select');
                if(input.is(':checked')) {
                    $(this).addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            });
        }
        $(document).on('change', '.candidate-select', updateCardSelection);
        updateCardSelection();
    });
    </script>
    <style>
    .candidate-card.selected {
        border: 2px solid #007bff;
        box-shadow: 0 0 8px #007bff33;
        background: #f0f8ff;
    }
    </style>
</body>
</html>