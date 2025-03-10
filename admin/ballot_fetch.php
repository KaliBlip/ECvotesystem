<?php
    include 'includes/session.php';
    include 'includes/slugify.php';

    // Get all positions data for later use
    $sql = "SELECT * FROM positions";
    $pquery = $conn->query($sql);
    $totalPositions = $pquery->num_rows;

    // Initialize output
    $output = '';
    
    // Get positions ordered by priority
    $sql = "SELECT * FROM positions ORDER BY priority ASC";
    $query = $conn->query($sql);
    $num = 1;
    
    while($row = $query->fetch_assoc()){
        // Determine input type based on max_vote
        $isMultiSelect = $row['max_vote'] > 1;
        $inputType = $isMultiSelect ? 'checkbox' : 'radio';
        $inputName = slugify($row['description']) . ($isMultiSelect ? "[]" : "");
        $inputClass = 'flat-red ' . slugify($row['description']);
        $input = '<input type="' . $inputType . '" class="' . $inputClass . '" name="' . $inputName . '">';
        
        // Get candidates for this position
        $candidate = '';
        $sql = "SELECT * FROM candidates WHERE position_id='" . $row['id'] . "'";
        $cquery = $conn->query($sql);
        
        while($crow = $cquery->fetch_assoc()){
            // Handle candidate image
            $image = (!empty($crow['photo'])) ? '../images/' . $crow['photo'] : '../images/profile.jpg';
            $fullName = $crow['firstname'] . ' ' . $crow['lastname'];
            
            // Build candidate entry
            $candidate .= '
                <li class="candidate-item">
                    ' . $input . '
                    <button class="btn btn-platform" data-toggle="modal" data-target="#platformModal" data-candidate="' . $fullName . '">
                        <i class="fa fa-search"></i> Platform
                    </button>
                    <div class="candidate-photo">
                        <img src="' . $image . '" alt="' . $fullName . '">
                    </div>
                    <span class="candidate-name">' . $fullName . '</span>
                </li>
            ';
        }
        
        // Set instructions based on max_vote
        $instruct = $isMultiSelect 
            ? 'You may select up to ' . $row['max_vote'] . ' candidates' 
            : 'Select only one candidate';
        
        // Set up/down buttons disabled states
        $updisable = ($row['priority'] == 1) ? 'disabled' : '';
        $downdisable = ($row['priority'] == $totalPositions) ? 'disabled' : '';
        
        // Build the complete position box
        $output .= '
            <div class="position-container" id="position-' . $row['id'] . '">
                <div class="position-box">
                    <div class="position-header">
                        <h3 class="position-title">' . $row['description'] . '</h3>
                        <div class="position-controls">
                            <button type="button" class="btn btn-control moveup" data-id="' . $row['id'] . '" ' . $updisable . '>
                                <i class="fa fa-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-control movedown" data-id="' . $row['id'] . '" ' . $downdisable . '>
                                <i class="fa fa-arrow-down"></i>
                            </button>
                        </div>
                    </div>
                    <div class="position-body">
                        <div class="position-instructions">
                            <p>' . $instruct . '</p>
                            <button type="button" class="btn btn-reset reset" data-desc="' . slugify($row['description']) . '">
                                <i class="fa fa-refresh"></i> Reset
                            </button>
                        </div>
                        <div class="candidate-list">
                            <ul>
                                ' . $candidate . '
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        ';
        
        // Update position priority
        $sql = "UPDATE positions SET priority = '$num' WHERE id = '" . $row['id'] . "'";
        $conn->query($sql);
        $num++;
    }
    
    // Add CSS for the redesigned ballot
    $output .= '
    <style>
        .position-container {
            margin-bottom: 24px;
        }
        
        .position-box {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .position-header {
            background-color: #2c6ea3;
            color: white;
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .position-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .position-controls {
            display: flex;
            gap: 8px;
        }
        
        .btn-control {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-control:hover:not([disabled]) {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        .btn-control[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .position-body {
            padding: 20px;
        }
        
        .position-instructions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            color: #555;
            font-size: 14px;
        }
        
        .btn-reset {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .btn-reset:hover {
            background-color: #43A047;
        }
        
        .candidate-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }
        
        .candidate-item {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.2s;
            position: relative;
        }
        
        .candidate-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .candidate-item input[type="radio"],
        .candidate-item input[type="checkbox"] {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        
        .candidate-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .candidate-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .candidate-name {
            font-weight: 600;
            text-align: center;
            margin-top: 8px;
        }
        
        .btn-platform {
            background-color: #2c6ea3;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 8px;
            cursor: pointer;
        }
        
        .btn-platform:hover {
            background-color: #1e5483;
        }
        
        @media (max-width: 768px) {
            .candidate-list ul {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            
            .position-instructions {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
    ';
    
    echo json_encode($output);
?>