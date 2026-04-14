<?php
include "config.php";
if(!isAdmin()){
    header("Location:index.php");
    exit();
}

$page = "cert";

if(isset($_POST['add'])){
    $user=$_POST['username'];
    $subject=$_POST['subject'];
    $grade=$_POST['grade'];
    $sem=$_POST['semester'];

    $conn->query("INSERT INTO grades(username,subject,grade,semester)
    VALUES('$user','$subject','$grade','$sem')");
}

$selectedUser = "";
if(isset($_POST['select_user'])){
    $selectedUser = $_POST['username'];
}

$users = $conn->query("SELECT username FROM users");

$grades = null;
if($selectedUser!=""){
    $grades = $conn->query("SELECT * FROM grades WHERE username='$selectedUser' ORDER BY semester");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Certificate Manager</title>

<style>
body{
font-family:Arial;
background:#f4f4f4;
padding:30px;
}

.box{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
margin-bottom:20px;
}

h2{
color:#800000;
}

input,select{
padding:10px;
margin:5px;
border-radius:5px;
border:1px solid #ccc;
}

button{
padding:10px 15px;
background:#800000;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#a00000;
}

table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

th,td{
border:1px solid #ddd;
padding:10px;
text-align:center;
}

th{
background:#800000;
color:white;
}

.back{
display:inline-block;
margin-top:10px;
color:#800000;
text-decoration:none;
}
</style>

</head>

<body>

<h2>Admin Certificate Manager</h2>

<div class="box">
<form method="POST">
<select name="username" required>
<option value="">Select Student</option>

<?php while($u=$users->fetch_assoc()){ ?>
<option value="<?php echo $u['username']; ?>">
<?php echo $u['username']; ?>
</option>
<?php } ?>

</select>

<button name="select_user">View</button>
</form>
</div>

<?php if($selectedUser!=""){ ?>

<div class="box">
<h3>Add Subject & Grade (<?php echo $selectedUser; ?>)</h3>

<form method="POST">
<input type="hidden" name="username" value="<?php echo $selectedUser; ?>">

<input type="text" name="subject" placeholder="Subject" required>
<input type="text" name="grade" placeholder="Grade" required>

<select name="semester">
<option>1st Semester</option>
<option>2nd Semester</option>
</select>

<button name="add">Add</button>
</form>
</div>

<div class="box">
<h3>Grades</h3>

<table>
<tr>
<th>Subject</th>
<th>Grade</th>
<th>Semester</th>
</tr>

<?php 
if($grades->num_rows==0){
    echo "<tr><td colspan='3'>No grades yet</td></tr>";
}else{
while($row=$grades->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['subject']; ?></td>
<td><?php echo $row['grade']; ?></td>
<td><?php echo $row['semester']; ?></td>
</tr>
<?php }} ?>

</table>

</div>

<?php } ?>

<a href="admin_dashboard.php" class="back">⬅ Back to Dashboard</a>

</body>
</html>
