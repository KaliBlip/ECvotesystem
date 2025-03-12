<?php
	
	include 'includes/session.php';
	include 'includes/slugify.php';

	$output = array('error'=>false,'list'=>'');

	$sql = "SELECT * FROM positions";
	$query = $conn->query($sql);

	$output['list'] = '
	<style>
	    .preview-list {
	        margin-bottom: 2rem;
	    }
	    .preview-item {
	        display: flex;
	        align-items: center;
	        padding: 1rem;
	        border-bottom: 1px solid #dee2e6;
	    }
	    .preview-item:last-child {
	        border-bottom: none;
	    }
	    .preview-position {
	        font-weight: 600;
	        color: #2c3e50;
	        margin-bottom: 0.5rem;
	    }
	    .preview-candidate {
	        display: flex;
	        align-items: center;
	    }
	    .preview-photo {
	        width: 50px;
	        height: 50px;
	        border-radius: 50%;
	        object-fit: cover;
	        margin-right: 1rem;
	        border: 2px solid #fff;
	        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
	    }
	    .preview-name {
	        font-size: 1.1rem;
	        color: #2c3e50;
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
					$output['list'] .= '<div class="preview-list">';
					$output['list'] .= '<div class="preview-position">'.$row['description'].'</div>';
					foreach($_POST[$position] as $key => $values){
						$sql = "SELECT * FROM candidates WHERE id = '$values'";
						$cmquery = $conn->query($sql);
						$cmrow = $cmquery->fetch_assoc();
						$image = (!empty($cmrow['photo'])) ? 'images/'.$cmrow['photo'] : 'images/profile.jpg';
						$output['list'] .= '
							<div class="preview-item">
								<div class="preview-candidate">
									<img src="'.$image.'" class="preview-photo" alt="'.$cmrow['firstname'].' '.$cmrow['lastname'].'">
									<span class="preview-name">'.$cmrow['firstname'].' '.$cmrow['lastname'].'</span>
								</div>
							</div>
						';
					}
					$output['list'] .= '</div>';
				}
			}
			else{
				$candidate = $_POST[$position];
				$sql = "SELECT * FROM candidates WHERE id = '$candidate'";
				$csquery = $conn->query($sql);
				$csrow = $csquery->fetch_assoc();
				$image = (!empty($csrow['photo'])) ? 'images/'.$csrow['photo'] : 'images/profile.jpg';
				$output['list'] .= '
					<div class="preview-list">
						<div class="preview-position">'.$row['description'].'</div>
						<div class="preview-item">
							<div class="preview-candidate">
								<img src="'.$image.'" class="preview-photo" alt="'.$csrow['firstname'].' '.$csrow['lastname'].'">
								<span class="preview-name">'.$csrow['firstname'].' '.$csrow['lastname'].'</span>
							</div>
						</div>
					</div>
				';
			}
		}
	}

	echo json_encode($output);


?>