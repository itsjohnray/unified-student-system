<?php
include "config.php";
if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

$username=$_SESSION['username'];

$res=$conn->query("SELECT * FROM subjects WHERE student_username='$username'");
$total = $res->num_rows;
?>

<!DOCTYPE html>
<html>
<head>
<title>My Subjects</title>

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

.stats{
margin-bottom:15px;
font-size:15px;
color:#555;
}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
border-radius:10px;
overflow:hidden;
}

th{
background:#800000;
color:white;
padding:14px;
}

td{
padding:12px;
border-bottom:1px solid #eee;
text-align:center;
}

tr:nth-child(even){
background:#fafafa;
}

tr:hover{
background:#ffe6e6;
transform:scale(1.01);
transition:0.2s;
}

.empty{
text-align:center;
padding:30px;
color:#999;
font-style:italic;
}

.btn{
background:#800000;
color:white;
padding:10px 15px;
border:none;
border-radius:8px;
cursor:pointer;
transition:0.3s;
margin-left:5px;
}

.btn:hover{
background:#a00000;
transform:scale(1.05);
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
<h2>My Subjects</h2>

<div>
<input type="text" id="search" class="search" placeholder="Search subject...">
<button class="btn" onclick="printTable()">Print</button>
</div>
</div>

<div class="card">

<div class="stats">
Total Subjects: <b><?php echo $total; ?></b>
</div>

<table id="table">
<tr>
<th>Subject</th>
<th>Teacher</th>
</tr>

<?php 
if($total > 0){
while($row=$res->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['subject_name']; ?></td>
<td><?php echo $row['teacher']; ?></td>
</tr>
<?php }} else {
echo "<tr><td colspan='2' class='empty'>No subjects assigned yet.</td></tr>";
} ?>

</table>

</div>

</div>

<button class="back" onclick="window.location='dashboard.php'">⬅</button>

<script nonce="<?php echo $csp_nonce ?? ''; ?>">
document.getElementById("search").addEventListener("keyup", function(){
let value = this.value.toLowerCase();
let rows = document.querySelectorAll("#table tr");

rows.forEach((row,i)=>{
if(i===0) return;
row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
});
});

function printTable(){
window.print();
}

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
