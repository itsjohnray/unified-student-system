<?php 
include "config.php";
if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

date_default_timezone_set('Asia/Manila');

$username=$_SESSION['username'];

// Update last_active
$conn->query("UPDATE users SET last_active=NOW() WHERE username='$username'");

if(isset($_GET['mark_read'])){
    $conn->query("UPDATE messages SET status='read' WHERE receiver='$username'");
    exit();
}

$user=$conn->query("
SELECT users.*, courses.course_name 
FROM users 
LEFT JOIN courses ON users.course_id = courses.id 
WHERE users.username='$username'
")->fetch_assoc();

$subjects_count = $conn->query("SELECT COUNT(*) as total FROM subjects WHERE student_username='$username'")->fetch_assoc()['total'];
$grades_count = $conn->query("SELECT COUNT(*) as total FROM grades WHERE username='$username'")->fetch_assoc()['total'];
$announcements_count = $conn->query("SELECT COUNT(*) as total FROM announcements")->fetch_assoc()['total'];

$avg = $conn->query("SELECT AVG(grade) as avg_grade FROM grades WHERE username='$username'")->fetch_assoc();
$average = $avg['avg_grade'] ? round($avg['avg_grade'],2) : 0;

if($average >= 90){ $status="Excellent"; }
elseif($average >= 75){ $status="Good"; }
else{ $status="Needs Improvement"; }

$hour = date("H");
if($hour >= 5 && $hour < 12){
    $greet = "Good Morning";
}elseif($hour >= 12 && $hour < 18){
    $greet = "Good Afternoon";
}else{
    $greet = "Good Evening";
}

$unread=$conn->query("SELECT * FROM messages WHERE receiver='$username' AND status='unread'")->num_rows;

$req = $conn->query("SELECT * FROM requests WHERE username='$username' AND type='certificate' ORDER BY id DESC LIMIT 1");
$request_status = ($req->num_rows>0) ? $req->fetch_assoc()['status'] : "none";

if(isset($_POST['request_cert']) && $request_status != "pending"){
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    $conn->query("
    INSERT INTO requests(username,type,status,course,year_level,section)
    VALUES('$username','certificate','pending','$course','$year','$section')
    ");

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI';}
body{display:flex;background:#f5f6fa;}

.sidebar{
width:230px;height:100vh;
background:linear-gradient(#800000,#4b0000);
color:white;position:fixed;padding-top:20px;
}

.sidebar h2{text-align:center;margin-bottom:30px;}

.sidebar a{
display:block;padding:12px;margin:5px;
color:#eee;text-decoration:none;border-radius:8px;
position:relative;
}

.sidebar a:hover{background:rgba(255,255,255,0.2);}

.dropdown-content{
display:none;
background:#5a0000;
margin-left:10px;
border-radius:6px;
}

.dropdown-content a{
display:block;
padding:10px;
color:#fff;
font-size:14px;
}

.dropdown-content a:hover{
background:#800000;
}

.badge{
position:absolute;
right:10px;
top:10px;
background:red;
color:white;
border-radius:50%;
padding:3px 7px;
font-size:11px;
}

.content{margin-left:230px;padding:25px;width:100%;}

.topbar{
display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;
}

.clock{font-size:14px;color:#555;}

.notif{position:relative;}

.bell{
cursor:pointer;
font-weight:bold;
}

.notif-box{
display:none;
position:absolute;
right:0;
top:35px;
width:260px;
background:white;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
padding:10px;
}

.profile{display:flex;align-items:center;gap:15px;margin-bottom:20px;}

.avatar{
width:60px;height:60px;border-radius:50%;
background:#800000;color:white;
display:flex;align-items:center;justify-content:center;
font-size:22px;font-weight:bold;
}

.user-box{
background:linear-gradient(135deg,#800000,#a00000);
color:white;padding:25px;border-radius:15px;
margin-bottom:20px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:15px;
}

.card{
background:white;padding:20px;border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
text-align:center;border-left:5px solid #800000;
}

.status-box{
margin-top:20px;
padding:15px;
border-radius:10px;
background:#fff;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

input{
width:100%;padding:8px;margin-top:5px;margin-bottom:10px;
border:1px solid #ccc;border-radius:6px;
}

.btn{
background:#800000;color:white;
padding:10px;border:none;border-radius:8px;
cursor:pointer;
}
</style>

</head>

<body>

<div class="sidebar">
<h2>Student</h2>

<a href="dashboard.php">Dashboard</a>
<a href="view_subjects.php">Subjects</a>

<div>
<a href="javascript:void(0)" onclick="toggleDropdown()">Grades ▼</a>
<div class="dropdown-content" id="gradeMenu">
<a href="grades_1st.php">1st Semester</a>
<a href="grades_2nd.php">2nd Semester</a>
</div>
</div>

<a href="announcements.php">Announcements</a>

<a href="messages.php">
Messages
<?php if($unread>0){ ?>
<span class="badge"><?php echo $unread; ?></span>
<?php } ?>
</a>

<a href="certificate.php">Certificate</a>
<a href="logout.php">Logout</a>
</div>

<div class="content">

<div class="topbar">
<h2><?php echo $greet . ", " . $username; ?> </h2>

<div style="display:flex;gap:15px;align-items:center;">
<span class="clock" id="clock"></span>

<div class="notif">
<div class="bell" onclick="openNotif()">🔔</div>

<div class="notif-box" id="notifBox">
<h4>Notifications</h4>
<?php 
$notif=$conn->query("SELECT * FROM messages WHERE receiver='$username' ORDER BY id DESC LIMIT 5");
if($notif->num_rows==0){
echo "<p>No notifications</p>";
}else{
while($n=$notif->fetch_assoc()){ ?>
<p><b><?php echo $n['sender']; ?></b><br><?php echo $n['message']; ?></p>
<?php }} ?>
</div>
</div>

</div>
</div>

<div class="profile">
<div class="avatar">
<?php echo strtoupper(substr($username,0,1)); ?>
</div>
<div>
<h3><?php echo $username; ?></h3>
<small>Student Account</small>
</div>
</div>

<div class="user-box">
<h2>Academic Overview</h2>
<p><?php echo $status; ?> (<?php echo $average; ?>%)</p>
</div>

<div class="grid">
<div class="card"><h3>Subjects</h3><h1><?php echo $subjects_count; ?></h1></div>
<div class="card"><h3>Grades</h3><h1><?php echo $grades_count; ?></h1></div>
<div class="card"><h3>Announcements</h3><h1><?php echo $announcements_count; ?></h1></div>
</div>

<div class="status-box">
<h3>Certificate Request</h3>

<?php if($request_status=="pending"){ ?>
<p style="color:orange;">Pending approval</p>
<?php } else { ?>

<form method="POST">
<input type="text" name="course" value="<?php echo $user['course_name']; ?>" required>
<input type="text" name="year" value="<?php echo $user['year_level']; ?>" required>
<input type="text" name="section" value="<?php echo $user['section']; ?>" required>
<button name="request_cert" class="btn">Submit Request</button>
</form>

<?php } ?>

<p>Status: <b><?php echo strtoupper($request_status); ?></b></p>
</div>

</div>

<script>
function toggleDropdown(){
let menu = document.getElementById("gradeMenu");
menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

function openNotif(){
let box = document.getElementById("notifBox");
box.style.display = (box.style.display === "block") ? "none" : "block";
fetch("dashboard.php?mark_read=1");
}

setInterval(()=>{
document.getElementById("clock").innerHTML =
new Date().toLocaleString();
},1000);
</script>

</body>
</html>
