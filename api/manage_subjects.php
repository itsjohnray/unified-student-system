<?php
include "config.php";
if(!isAdmin()){ header("Location:index.php"); exit(); }

if(isset($_POST['add'])){
    $conn->query("INSERT INTO subjects(subject_name,teacher) 
    VALUES('$_POST[subject]','$_POST[teacher]')");
}

if(isset($_GET['delete'])){
    $conn->query("DELETE FROM subjects WHERE id='$_GET[delete]'");
}

$res=$conn->query("SELECT * FROM subjects");
?>

<body style="font-family:Arial;background:#f4f4f4;padding:30px;">

<h2 style="color:#800000;">Manage Subjects</h2>

<form method="POST" style="margin-bottom:20px;">
<input type="text" name="subject" placeholder="Subject"
style="padding:8px;border:1px solid #ccc;border-radius:5px;">
<input type="text" name="teacher" placeholder="Teacher"
style="padding:8px;border:1px solid #ccc;border-radius:5px;">
<button name="add" style="padding:8px 15px;background:#800000;color:white;border:none;border-radius:5px;">Add</button>
</form>

<table style="width:100%;background:white;border-collapse:collapse;">
<tr style="background:#800000;color:white;">
<th>Subject</th><th>Teacher</th><th>Action</th>
</tr>

<?php while($row=$res->fetch_assoc()){ ?>
<tr style="text-align:center;">
<td><?php echo $row['subject_name']; ?></td>
<td><?php echo $row['teacher']; ?></td>
<td>
<a href="?delete=<?php echo $row['id']; ?>" style="color:red;">Delete</a>
</td>
</tr>
<?php } ?>
</table>

<br>
<a href="admin_dashboard.php">⬅ Back</a>

</body>
