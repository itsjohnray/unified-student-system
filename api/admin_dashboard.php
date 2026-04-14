<?php
include "config.php";
if(!isAdmin()){
    header("Location:index.php");
    exit();
}

$page="dashboard";

$users=$conn->query("SELECT * FROM users")->num_rows;
$grades=$conn->query("SELECT * FROM grades")->num_rows;
$subjects=$conn->query("SELECT * FROM subjects")->num_rows;
$ann=$conn->query("SELECT * FROM announcements")->num_rows;

$req=$conn->query("SELECT * FROM requests WHERE status='pending'")->num_rows;

$online=$conn->query("SELECT username FROM users WHERE last_active > NOW() - INTERVAL 5 MINUTE");

$preview=$conn->query("SELECT * FROM grades ORDER BY id DESC LIMIT 5");
$recent_req=$conn->query("SELECT * FROM requests ORDER BY id DESC LIMIT 5");

$high=$conn->query("SELECT * FROM grades WHERE grade>=90")->num_rows;
$mid=$conn->query("SELECT * FROM grades WHERE grade>=75 AND grade<90")->num_rows;
$low=$conn->query("SELECT * FROM grades WHERE grade<75")->num_rows;

if(isset($_POST['send'])){
    $to=$_POST['to'];
    $msg=$_POST['message'];
    $from=$_SESSION['username'];
    $conn->query("INSERT INTO messages(sender,receiver,message) VALUES('$from','$to','$msg')");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

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
}
.sidebar a:hover{background:rgba(255,255,255,0.2);}
.sidebar a.active{background:white;color:#800000;}

.badge{
background:red;color:white;border-radius:50%;
padding:3px 7px;font-size:11px;
float:right;
}

.content{margin-left:240px;width:100%;display:flex;}
.main{flex:3;padding:30px;}
.right{flex:1;background:white;padding:20px;border-left:1px solid #ddd;}

.topbar{display:flex;justify-content:space-between;margin-bottom:20px;}
.clock{color:#777;}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:15px;
}

.card{
background:white;
padding:20px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
border-left:5px solid #800000;
transition:0.3s;
}
.card:hover{transform:translateY(-5px);}

.section{margin-top:20px;}

table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:center;}
th{background:#800000;color:white;}

.bar{height:8px;background:#eee;border-radius:10px;margin-top:5px;}
.fill{height:8px;background:#800000;border-radius:10px;}

.user{
display:flex;justify-content:space-between;
padding:10px;border-bottom:1px solid #eee;cursor:pointer;
}
.user:hover{background:#f9f9f9;}
.dot{width:8px;height:8px;background:limegreen;border-radius:50%;}

input,textarea{
width:100%;padding:8px;margin-top:5px;
border:1px solid #ccc;border-radius:6px;
}
button{
margin-top:8px;width:100%;padding:10px;
border:none;background:#800000;color:white;
border-radius:6px;cursor:pointer;
}
button:hover{background:#a00000;}

.alert{
background:#fff3cd;
padding:10px;
border-left:5px solid orange;
margin-bottom:10px;
border-radius:6px;
}

.log{
font-size:13px;
color:#555;
margin-top:5px;
}
</style>

</head>

<body>

<div class="sidebar">
<h2>Admin</h2>

<a href="#" class="active">Dashboard</a>
<a href="manage_users.php">Users</a>
<a href="manage_subjects.php">Subjects</a>
<a href="manage_grades.php">Grades</a>
<a href="announcement.php">Announcements</a>

<a href="admin_requests.php">
Requests <?php if($req>0){ ?><span class="badge"><?php echo $req; ?></span><?php } ?>
</a>

<a href="admin_certificate.php">Certificates</a>
<a href="logout.php">Logout</a>
</div>

<div class="content">

<div class="main">

<div class="topbar">
<h2>Admin Dashboard</h2>
<div class="clock" id="clock"></div>
</div>

<?php if($req>0){ ?>
<div class="alert">
⚠ You have <?php echo $req; ?> pending requests!
</div>
<?php } ?>

<div class="grid">

<div class="card"><h2><?php echo $users; ?></h2><p>Users</p></div>
<div class="card"><h2><?php echo $subjects; ?></h2><p>Subjects</p></div>
<div class="card"><h2><?php echo $grades; ?></h2><p>Grades</p></div>
<div class="card"><h2><?php echo $ann; ?></h2><p>Announcements</p></div>

</div>

<div class="section card">
<h3>Grade Distribution</h3>

<p>Excellent (90+)</p>
<div class="bar"><div class="fill" style="width:<?php echo $high; ?>%"></div></div>

<p>Good (75-89)</p>
<div class="bar"><div class="fill" style="width:<?php echo $mid; ?>%"></div></div>

<p>Needs Improvement</p>
<div class="bar"><div class="fill" style="width:<?php echo $low; ?>%"></div></div>

</div>

<div class="section card">
<h3>Recent Grades</h3>
<table>
<tr><th>Student</th><th>Subject</th><th>Grade</th></tr>
<?php while($p=$preview->fetch_assoc()){ ?>
<tr>
<td><?php echo $p['username']; ?></td>
<td><?php echo $p['subject']; ?></td>
<td><?php echo $p['grade']; ?></td>
</tr>
<?php } ?>
</table>
</div>

<div class="section card">
<h3>Latest Requests</h3>
<?php while($r=$recent_req->fetch_assoc()){ ?>
<div class="log">
<b><?php echo $r['username']; ?></b> requested 
<?php echo $r['type']; ?> (<?php echo $r['status']; ?>)
</div>
<?php } ?>
</div>

</div>

<div class="right">

<h3>🟢 Online Users</h3>

<?php while($u=$online->fetch_assoc()){ ?>
<div class="user" onclick="selectUser('<?php echo $u['username']; ?>')">
<span><?php echo $u['username']; ?></span>
<div class="dot"></div>
</div>
<?php } ?>

<br>

<h3>Quick Message</h3>

<form method="POST">
<input type="text" name="to" id="to" placeholder="Select user" required>
<textarea name="message" placeholder="Type message..." required></textarea>
<button name="send">Send</button>
</form>

</div>

</div>

<script>
function selectUser(name){
document.getElementById("to").value=name;
}

setInterval(()=>{
let d=new Date();
document.getElementById("clock").innerHTML =
d.toLocaleString();
},1000);
</script>

</body>
</html>
