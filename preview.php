<?php
	
	include 'includes/session.php';
	include 'includes/slugify.php';

	$output = array('error'=>false,'list'=>'');

	$sql = "SELECT * FROM positions";
	$query = $conn->query($sql);

	$output['list'] = '
	<style>
	    .preview-container {
	        padding: 1rem;
	    }
	    
	    .preview-section {
	        background: white;
	        border-radius: 8px;
	        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
	        margin-bottom: 1.5rem;
	        overflow: hidden;
	    }
	    
	    .preview-position {
	        background: var(--primary);
	        color: white;
	        padding: 1rem;
	        font-size: 1.1rem;
	        font-weight: 500;
	    }
	    
	    .preview-candidates {
	        padding: 1rem;
	    }
	    
	    .preview-candidate {
	        display: flex;
	        align-items: center;
	        padding: 0.75rem;
	        border: 1px solid var(--gray-200);
	        border-radius: 6px;
	        margin-bottom: 0.75rem;
	        transition: transform 0.2s ease;
	    }
	    
	    .preview-candidate:last-child {
	        margin-bottom: 0;
	    }
	    
	    .preview-candidate:hover {
	        transform: translateX(5px);
	        background: var(--gray-100);
	    }
	    
	    .preview-photo {
	        width: 60px;
	        height: 60px;
	        border-radius: 50%;
	        object-fit: cover;
	        border: 3px solid var(--light);
	        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
	        margin-right: 1rem;
	    }
	    
	    .preview-details {
	        flex-grow: 1;
	    }
	    
	    .preview-name {
	        font-size: 1.1rem;
	        font-weight: 500;
	        color: var(--gray-800);
	        margin: 0;
	    }
	    
	    .preview-check {
	        color: var(--success);
	        font-size: 1.5rem;
	        margin-left: 1rem;
	    }
	    
	    .preview-empty {
	        text-align: center;
	        padding: 2rem;
	        color: var(--gray-600);
	    }
	    
	    .preview-empty i {
	        font-size: 3rem;
	        color: var(--gray-400);
	        margin-bottom: 1rem;
	        display: block;
	    }
	</style>';

	while($row = $query->fetch_assoc()){
		$position = slugify($row['description']);
		$pos_id = $row['id'];
		if(isset($_POST[$position])){
			if($row['max_vote'] > 1){
				if(count($_POST[$position]) > $row['max_vote']){
					$output['error'] = true;
					$output['message'][] = '<li>You can only choose '.$row['max_vote'].' candidates for '.$row['description'].'</li>';
				}
				else{
					$output['list'] .= '
					<div class="preview-section">
						<div class="preview-position">
							<i class="fas fa-users-gear"></i> '.$row['description'].'
						</div>
						<div class="preview-candidates">';
					
					foreach($_POST[$position] as $key => $values){
						$sql = "SELECT * FROM candidates WHERE id = '$values'";
						$cmquery = $conn->query($sql);
						$cmrow = $cmquery->fetch_assoc();
						$image = (!empty($cmrow['photo'])) ? 'images/'.$cmrow['photo'] : 'images/profile.jpg';
						
						$output['list'] .= '
							<div class="preview-candidate">
								<img src="'.$image.'" class="preview-photo" alt="'.$cmrow['firstname'].' '.$cmrow['lastname'].'">
								<div class="preview-details">
									<h4 class="preview-name">'.$cmrow['firstname'].' '.$cmrow['lastname'].'</h4>
								</div>
								<div class="preview-check">
									<i class="fas fa-check-circle"></i>
								</div>
							</div>';
					}
					
					$output['list'] .= '
						</div>
					</div>';
				}
			}
			else{
				$candidate = $_POST[$position];
				$sql = "SELECT * FROM candidates WHERE id = '$candidate'";
				$csquery = $conn->query($sql);
				$csrow = $csquery->fetch_assoc();
				$image = (!empty($csrow['photo'])) ? 'images/'.$csrow['photo'] : 'images/profile.jpg';
				
				$output['list'] .= '
				<div class="preview-section">
					<div class="preview-position">
						<i class="fas fa-user-tie"></i> '.$row['description'].'
					</div>
					<div class="preview-candidates">
						<div class="preview-candidate">
							<img src="'.$image.'" class="preview-photo" alt="'.$csrow['firstname'].' '.$csrow['lastname'].'">
							<div class="preview-details">
								<h4 class="preview-name">'.$csrow['firstname'].' '.$csrow['lastname'].'</h4>
							</div>
							<div class="preview-check">
								<i class="fas fa-check-circle"></i>
							</div>
						</div>
					</div>
				</div>';
			}
		}
		else {
			$output['list'] .= '
			<div class="preview-section">
				<div class="preview-position">
					<i class="fas fa-user-tie"></i> '.$row['description'].'
				</div>
				<div class="preview-empty">
					<i class="fas fa-info-circle"></i>
					<p>No candidate selected for this position</p>
				</div>
			</div>';
		}
	}

	echo json_encode($output);


?>