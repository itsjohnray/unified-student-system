<?php
include "config.php";
if(!isAdmin()){
    header("Location:index.php");
    exit();
}

$page="requests";

if(isset($_GET['approve'])){
    $id=$_GET['approve'];

    $get=$conn->query("SELECT username FROM requests WHERE id=$id")->fetch_assoc();
    $user=$get['username'];

    $conn->query("UPDATE requests SET status='approved' WHERE id=$id");

    $conn->query("INSERT INTO messages(sender,receiver,message) 
    VALUES('admin','$user','✅ Your certificate request has been APPROVED.')");
}

if(isset($_GET['reject'])){
    $id=$_GET['reject'];

    $get=$conn->query("SELECT username FROM requests WHERE id=$id")->fetch_assoc();
    $user=$get['username'];

    $conn->query("UPDATE requests SET status='rejected' WHERE id=$id");

    $conn->query("INSERT INTO messages(sender,receiver,message) 
    VALUES('admin','$user','❌ Your certificate request has been REJECTED.')");
}

$requests=$conn->query("SELECT * FROM requests ORDER BY id DESC");

$req=$conn->query("SELECT * FROM requests WHERE status='pending'")->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Requests</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI';}

body{display:flex;background:#f4f6f9;}

.sidebar{
width:240px;height:100vh;
background:linear-gradient(#800000,#4b0000);
color:white;position:fixed;padding:20px;
}

.sidebar h2{text-align:center;margin-bottom:30px;}

.sidebar a{
display:block;padding:12px;margin-bottom:8px;
color:#eee;text-decoration:none;border-radius:8px;
position:relative;
}

.sidebar a:hover{background:rgba(255,255,255,0.2);}

.sidebar a.active{
background:white;color:#800000;font-weight:bold;
}

.badge{
background:red;color:white;
border-radius:50%;
padding:3px 7px;
font-size:11px;
position:absolute;
right:10px;
top:10px;
}

.content{
margin-left:240px;
padding:30px;
width:100%;
}

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:10px;
overflow:hidden;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

th,td{
padding:12px;
text-align:center;
border-bottom:1px solid #eee;
}

th{
background:#800000;
color:white;
}

.pending{color:orange;font-weight:bold;}
.approved{color:green;font-weight:bold;}
.rejected{color:red;font-weight:bold;}

.btn{
padding:6px 10px;
border:none;
border-radius:5px;
cursor:pointer;
}

.approve{background:green;color:white;}
.reject{background:red;color:white;}

.btn:hover{opacity:0.8;}

h2{margin-bottom:15px;color:#800000;}
</style>

</head>

<body>

<div class="sidebar">
<h2>Admin</h2>

<a href="admin_dashboard.php">Dashboard</a>
<a href="manage_users.php">Users</a>
<a href="manage_subjects.php">Subjects</a>
<a href="manage_grades.php">Grades</a>
<a href="announcement.php">Announcements</a>

<a href="admin_requests.php" class="active">
Requests
<?php if($req>0){ ?>
<span class="badge"><?php echo $req; ?></span>
<?php } ?>
</a>

<a href="admin_certificate.php">Certificates</a>
<a href="logout.php">Logout</a>
</div>

<div class="content">

<h2>Certificate Requests</h2>

<table>
<tr>
<th>ID</th>
<th>Student</th>
<th>Type</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php 
if($requests->num_rows==0){
echo "<tr><td colspan='5'>No requests</td></tr>";
}else{
while($r=$requests->fetch_assoc()){ ?>
<tr>

<td><?php echo $r['id']; ?></td>
<td><?php echo $r['username']; ?></td>
<td><?php echo $r['type']; ?></td>

<td class="<?php echo $r['status']; ?>">
<?php echo strtoupper($r['status']); ?>
</td>

<td>
<?php if($r['status']=="pending"){ ?>
<a href="?approve=<?php echo $r['id']; ?>">
<button class="btn approve">Approve</button>
</a>

<a href="?reject=<?php echo $r['id']; ?>">
<button class="btn reject">Reject</button>
</a>
<?php }else{
echo "-";
} ?>
</td>

</tr>
<?php }} ?>

</table>

</div>

</body>
</html>
