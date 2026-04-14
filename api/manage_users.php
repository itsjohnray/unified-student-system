<?php
include "config.php";
if(!isAdmin()){ header("Location:index.php"); exit(); }

if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id='$id'");
}

$search="";
if(isset($_POST['search'])){
    $search=$_POST['search'];
    $res=$conn->query("SELECT * FROM users WHERE username LIKE '%$search%'");
}else{
    $res=$conn->query("SELECT * FROM users");
}
?>

<body style="font-family:Arial;background:#f4f4f4;padding:30px;">

<h2 style="color:#800000;">Manage Users</h2>

<form method="POST" style="margin-bottom:20px;">
<input type="text" name="search" placeholder="Search..." 
style="padding:8px;border-radius:5px;border:1px solid #ccc;">
<button style="padding:8px 15px;background:#800000;color:white;border:none;border-radius:5px;">Search</button>
</form>

<table style="width:100%;border-collapse:collapse;background:white;">
<tr style="background:#800000;color:white;">
<th style="padding:10px;">ID</th>
<th>Username</th>
<th>Role</th>
<th>Action</th>
</tr>

<?php while($row=$res->fetch_assoc()){ ?>
<tr style="text-align:center;">
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['username']; ?></td>
<td><?php echo $row['role']; ?></td>
<td>
<a href="?delete=<?php echo $row['id']; ?>" 
style="color:red;text-decoration:none;">Delete</a>
</td>
</tr>
<?php } ?>
</table>

<br>
<a href="admin_dashboard.php" style="color:#800000;">⬅ Back</a>

</body>
