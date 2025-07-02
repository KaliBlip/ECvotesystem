<?php
	
	include 'includes/session.php';
	include 'includes/slugify.php';

	$output = array('error'=>false,'list'=>'');

	$sql = "SELECT * FROM positions";
	$query = $conn->query($sql);

	$output['list'] = '';

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
					$output['list'] .= '<div style="margin-bottom:1.5rem;background:#fff;padding:1rem;border-radius:0.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);">';
					$output['list'] .= '<div style="font-weight:600;color:#2c3e50;margin-bottom:0.5rem;">'.$row['description'].'</div>';
					$output['list'] .= '<ul style="list-style:none;padding:0;margin:0;">';
					foreach($_POST[$position] as $key => $values){
						$sql = "SELECT * FROM candidates WHERE id = '$values'";
						$cmquery = $conn->query($sql);
						$cmrow = $cmquery->fetch_assoc();
						$image = (!empty($cmrow['photo'])) ? 'images/'.$cmrow['photo'] : 'images/profile.jpg';
						$output['list'] .= '
							<li style="display:flex;align-items:center;margin-bottom:0.5rem;">
								<img src="'.$image.'" alt="'.$cmrow['firstname'].' '.$cmrow['lastname'].'" style="width:36px;height:36px;border-radius:50%;object-fit:cover;margin-right:0.75rem;border:1px solid #ccc;">
								<span style="font-size:1rem;color:#2c3e50;">'.$cmrow['firstname'].' '.$cmrow['lastname'].'</span>
							</li>
						';
					}
					$output['list'] .= '</ul>';
					$output['list'] .= '</div>';
				}
			}
			else{
				$candidate = $_POST[$position];
				$sql = "SELECT * FROM candidates WHERE id = '$candidate'";
				$csquery = $conn->query($sql);
				$csrow = $csquery->fetch_assoc();
				$image = (!empty($csrow['photo'])) ? 'images/'.$csrow['photo'] : 'images/profile.jpg';
				$output['list'] .= '<div style="margin-bottom:1.5rem;background:#fff;padding:1rem;border-radius:0.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.04);">';
				$output['list'] .= '<div style="font-weight:600;color:#2c3e50;margin-bottom:0.5rem;">'.$row['description'].'</div>';
				$output['list'] .= '<ul style="list-style:none;padding:0;margin:0;">';
				$output['list'] .= '
					<li style="display:flex;align-items:center;margin-bottom:0.5rem;">
						<img src="'.$image.'" alt="'.$csrow['firstname'].' '.$csrow['lastname'].'" style="width:36px;height:36px;border-radius:50%;object-fit:cover;margin-right:0.75rem;border:1px solid #ccc;">
						<span style="font-size:1rem;color:#2c3e50;">'.$csrow['firstname'].' '.$csrow['lastname'].'</span>
					</li>
				';
				$output['list'] .= '</ul>';
				$output['list'] .= '</div>';
			}
		}
	}

	echo json_encode($output);


?>