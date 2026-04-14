<?php
include "config.php";
if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

$res=$conn->query("SELECT * FROM announcements ORDER BY id DESC");
$total = $res->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
<title>Announcements</title>

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
display:flex;
background:linear-gradient(135deg,#f4f4f4,#eaeaea);
}

.sidebar{
width:230px;
height:100vh;
background:linear-gradient(#800000,#4b0000);
color:white;
position:fixed;
padding-top:20px;
box-shadow:5px 0 20px rgba(0,0,0,0.3);
}

.sidebar h2{
text-align:center;
margin-bottom:30px;
}

.sidebar a{
display:block;
padding:15px;
color:white;
text-decoration:none;
transition:0.3s;
}

.sidebar a:hover{
background:rgba(255,255,255,0.2);
padding-left:25px;
}

.dropdown a{
cursor:pointer;
}

.submenu{
display:none;
padding-left:15px;
animation:fadeIn 0.3s ease;
}

.submenu a{
font-size:14px;
background:rgba(255,255,255,0.1);
margin:3px 0;
border-radius:5px;
}

.submenu a:hover{
background:rgba(255,255,255,0.3);
}

.content{
margin-left:230px;
padding:30px;
width:100%;
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.search{
padding:12px;
width:260px;
border-radius:10px;
border:1px solid #ccc;
box-shadow:0 5px 10px rgba(0,0,0,0.1);
}

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.announcement{
background:rgba(255,255,255,0.8);
padding:20px;
border-radius:15px;
margin-bottom:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
transition:0.3s;
border-left:5px solid #800000;
}

.announcement:hover{
transform:translateY(-5px);
box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.announcement h3{
color:#800000;
margin-bottom:5px;
}

.date{
font-size:12px;
color:#777;
margin-bottom:10px;
}

.message{
font-size:14px;
color:#444;
line-height:1.5;
}

.empty{
text-align:center;
padding:40px;
color:#999;
font-style:italic;
}

.back{
position:fixed;
bottom:20px;
right:20px;
background:#e74c3c;
color:white;
padding:12px 15px;
border-radius:50px;
border:none;
cursor:pointer;
box-shadow:0 5px 15px rgba(0,0,0,0.3);
}

.back:hover{
background:#c0392b;
}

.clock{
position:fixed;
top:10px;
right:20px;
color:#555;
font-size:14px;
}

.badge{
display:inline-block;
background:#e67e22;
color:white;
padding:3px 8px;
border-radius:8px;
font-size:11px;
margin-bottom:5px;
}

@keyframes fadeIn{
from{opacity:0;transform:translateY(-5px);}
to{opacity:1;transform:translateY(0);}
}
</style>

</head>

<body>

<div class="sidebar">
<h2>🎓 Student</h2>

<a href="dashboard.php">Dashboard</a>
<a href="view_subjects.php">Subjects</a>

<div class="dropdown">
<a onclick="toggleMenu()">Grades ▼</a>

<div class="submenu" id="gradesMenu">
<a href="grades_1st.php">1st Semester</a>
<a href="grades_2nd.php">2nd Semester</a>
</div>
</div>

<a href="announcements.php">Announcements</a>
<a href="certificate.php">Certificate</a>

</div>

<div class="clock" id="clock"></div>

<div class="content">

<div class="header">
<h2>Announcements</h2>
<input type="text" id="search" class="search" placeholder="Search announcement...">
</div>

<div class="card" id="list">

<?php 
if($total > 0){
$i = 0;
while($row=$res->fetch_assoc()){
$i++;
?>

<div class="announcement">
<?php if($i==1){ echo "<span class='badge'>Latest</span>"; } ?>

<h3><?php echo $row['title']; ?></h3>

<div class="date">
<?php echo isset($row['created_at']) ? $row['created_at'] : "No date"; ?>
</div>

<div class="message">
<?php echo $row['message']; ?>
</div>

</div>

<?php }} else {
echo "<div class='empty'>No announcements yet.</div>";
} ?>

</div>

</div>

<button class="back" onclick="window.location='dashboard.php'">⬅</button>

<script>
document.getElementById("search").addEventListener("keyup", function(){
let value = this.value.toLowerCase();
let items = document.querySelectorAll(".announcement");

items.forEach(item=>{
item.style.display = item.innerText.toLowerCase().includes(value) ? "" : "none";
});
});

setInterval(()=>{
let d=new Date();
document.getElementById("clock").innerHTML = d.toLocaleTimeString();
},1000);

function toggleMenu(){
let menu = document.getElementById("gradesMenu");

if(menu.style.display === "block"){
menu.style.display = "none";
}else{
menu.style.display = "block";
}
}
</script>

</body>
</html>
