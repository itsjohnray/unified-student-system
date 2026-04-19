<?php
include "config.php";
if(!isAdmin()){
    header("Location:index.php");
    exit();
}

if(isset($_POST['add'])){
    $username = $_POST['username'];
    $subject  = $_POST['subject'];
    $grade    = $_POST['grade'];
    $semester = $_POST['semester'];

    if(empty($username) || empty($subject) || empty($grade) || empty($semester)){
        echo "<script nonce=\"$csp_nonce\">alert('All fields required!')</script>";
    }else{

        $sql = "INSERT INTO grades(username,subject,grade,semester)
                VALUES('$username','$subject','$grade','$semester')";

        if($conn->query($sql)){
            echo "<script nonce=\"$csp_nonce\">alert('✅ Grade Added Successfully')</script>";
        }else{
            echo "Error: " . $conn->error;
        }
    }
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM grades WHERE id='$id'");
    header("Location: manage_grades.php");
    exit();
}

$res = $conn->query("SELECT * FROM grades ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Grades</title>

<style>
body{
font-family:Arial;
background:#f4f4f4;
padding:30px;
}

h2{
color:#800000;
margin-bottom:15px;
}

form{
margin-bottom:20px;
}

input, select{
padding:10px;
margin:5px;
border-radius:6px;
border:1px solid #ccc;
}

button{
padding:10px 15px;
background:#800000;
color:white;
border:none;
border-radius:6px;
cursor:pointer;
}

button:hover{
background:#a00000;
}

table{
width:100%;
background:white;
border-collapse:collapse;
margin-top:20px;
}

th{
background:#800000;
color:white;
padding:12px;
}

td{
padding:10px;
border-bottom:1px solid #eee;
text-align:center;
}

a{
color:red;
text-decoration:none;
}
</style>

</head>

<body>

<h2>Manage Grades</h2>

<form method="POST">

<input name="username" placeholder="Username" required>

<input name="subject" placeholder="Subject" required>

<input name="grade" type="number" step="0.01" min="1" max="5" placeholder="Grade (ex. 1.50)" required>

<select name="semester" required>
<option value="">Select Semester</option>
<option value="1st">1st Semester</option>
<option value="2nd">2nd Semester</option>
</select>

<button type="submit" name="add">Add Grade</button>

</form>

<table>
<tr>
<th>User</th>
<th>Subject</th>
<th>Grade</th>
<th>Semester</th>
<th>Action</th>
</tr>

<?php while($row=$res->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['username']; ?></td>
<td><?php echo $row['subject']; ?></td>
<td><?php echo $row['grade']; ?></td>
<td><?php echo $row['semester']; ?></td>
<td>
<a href="#" class="del-btn" data-id="<?php echo $row['id']; ?>">Delete</a>
</td>
</tr>
<?php } ?>

</table>

<br>
<a href="admin_dashboard.php">⬅ Back</a>

<script nonce="<?php echo $csp_nonce ?? ''; ?>">
document.addEventListener("click", function(e){
    if(e.target && e.target.classList.contains("del-btn")){
        e.preventDefault();
        if(confirm("Delete this grade?")){
            window.location.href = "?delete=" + e.target.getAttribute("data-id");
        }
    }
});
</script>

</body>
</html>
