<?PHP
	session_start();
	// Create connection to Oracle
	if(empty($_SESSION['ID']) || empty($_SESSION['NAME']) || empty($_SESSION['SURNAME'])){
		echo '<script>window.location = "Login.php";</script>';
	}
	$conn = oci_connect("system", "123456", "//localhost/XE");
	if (!$conn) {
		$m = oci_error();
		echo $m['message'], "\n";
		exit;
	} 
?>

Change Password
<hr>

<?PHP
	if(isset($_POST['submit'])){
		$oldpassword = trim($_POST['oldpassword']);
		$newpassword = trim($_POST['newpassword']);
		$confirmnewpassword = trim($_POST['confirmnewpassword']);
		$id = $_SESSION['ID'];
		if($newpassword == $confirmnewpassword){
		$query = "SELECT * FROM Member WHERE ID = '$id' and Password='$oldpassword'";
		$parseRequest = oci_parse($conn, $query);
		oci_execute($parseRequest);
		// Fetch each row in an associative array
		$row = oci_fetch_array($parseRequest, OCI_RETURN_NULLS+OCI_ASSOC);
		if($row){
			$query1 = "UPDATE Member SET Password = '$newpassword' where ID = '$id'";
			$parseRequest1 = oci_parse($conn, $query1);
			oci_execute($parseRequest1);
			echo '<script>window.location = "MemberPage.php";</script>';
		}else{
			echo "Old Password was wrong";
		}
		}else{
			echo "New password not match";
		}
	};
?>


<?PHP
	if(isset($_POST['back'])){
		echo '<script>window.location = "MemberPage.php";</script>';
	};
?>

<form action='Changepassword.php' method='post'>
	Old Password <br>
	<input name='oldpassword' type='password'><br>
	New Password<br>
	<input name='newpassword' type='password'><br>
    Comfirm New Password <br>
	<input name='confirmnewpassword' type='password'><br><br>
	<input name='submit' type='submit' value='Submit'>
    <input name='back' type='submit' value='Back'>
</form>