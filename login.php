<?php
	session_start();
	if(isset($_SESSION['voter'])){
		header('location: home.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Voting System</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="login-container">
			<div class="login-header">
				<h2>Voting System</h2>
				<p>Student Login</p>
			</div>

			<form action="login.php" method="POST">
				<?php
					if(isset($_SESSION['error'])){
						echo "
							<div class='alert alert-danger'>
								".$_SESSION['error']."
							</div>
						";
						unset($_SESSION['error']);
					}
				?>
				<div class="form-group">
					<input type="text" class="form-control" name="voter" placeholder="Voter ID" required>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary" name="login" style="width: 100%;">Login</button>
				</div>
			</form>
		</div>
	</div>

<?php
	if(isset($_POST['login'])){
		$voter = $_POST['voter'];
		$password = $_POST['password'];

		$conn = new mysqli('localhost', 'root', '', 'votesystem');

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM voters WHERE voters_id = '$voter'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Cannot find voter with the ID';
		}
		else{
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['voter'] = $row['id'];
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
			}
		}
		
	}
	else{
		$_SESSION['error'] = 'Input voter credentials first';
	}

	header('location: index.php');
?>
</body>
</html>