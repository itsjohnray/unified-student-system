<?php
include "config.php";
if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

$username=$_SESSION['username'];

$res=$conn->query("SELECT * FROM grades WHERE username='$username'");

$total = 0;
$count = 0;

$gradesData = [];

while($row=$res->fetch_assoc()){
    $gradesData[] = $row;
    $total += $row['grade'];
    $count++;
}

$average = ($count > 0) ? round($total / $count, 2) : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>My Grades</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}

body{display:flex;background:#f4f4f4;}

.sidebar{
width:230px;
height:100vh;
background:linear-gradient(#800000,#4b0000);
color:white;
position:fixed;
padding-top:20px;
}

.sidebar h2{text-align:center;margin-bottom:30px;}

.sidebar a{
display:block;
padding:15px;
color:white;
text-decoration:none;
}

.sidebar a:hover{background:rgba(255,255,255,0.2);}

.content{
margin-left:230px;
padding:30px;
width:100%;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.search{
padding:10px;
border-radius:8px;
border:1px solid #ccc;
}

.btn{
background:#800000;
color:white;
padding:10px 15px;
border:none;
border-radius:8px;
cursor:pointer;
}

.card{
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

th{
background:#800000;
color:white;
padding:10px;
}

td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:center;
}

.pass{
background:#2ecc71;
color:white;
padding:5px 10px;
border-radius:10px;
}

.fail{
background:#e74c3c;
color:white;
padding:5px 10px;
border-radius:10px;
}

.info{
display:flex;
justify-content:space-between;
margin-bottom:10px;
color:#555;
}
</style>

</head>

<body>

<div class="sidebar">
<h2>🎓 Student</h2>
<a href="dashboard.php">Dashboard</a>
<a href="view_subjects.php">Subjects</a>
<a href="view_grades.php">Grades</a>
<a href="announcements.php">Announcements</a>
<a href="certificate.php">Certificate</a>
</div>

<div class="content">

<div class="topbar">
<h2>My Grades</h2>

<div>
<input type="text" id="search" class="search" placeholder="Search subject...">
<button class="btn" onclick="window.print()">Print</button>
</div>
</div>

<div class="card">

<div class="info">
<span>Total Subjects: <?php echo $count; ?></span>
<span>Average: <?php echo $average; ?></span>
</div>

<table id="table">
<tr>
<th>Subject</th>
<th>Grade</th>
<th>Status</th>
</tr>

<?php
if($count == 0){
    echo "<tr><td colspan='3'>No grades yet</td></tr>";
}else{

foreach($gradesData as $row){

$grade = floatval($row['grade']);

$status = ($grade <= 3.00) ? "Pass" : "Fail";
$class = ($status == "Pass") ? "pass" : "fail";
?>

<tr>
<td><?php echo $row['subject']; ?></td>
<td><?php echo number_format($grade,2); ?></td>
<td><span class="<?php echo $class; ?>"><?php echo $status; ?></span></td>
</tr>

<?php }} ?>

</table>

</div>

</div>

<script>
document.getElementById("search").addEventListener("keyup", function(){
let value = this.value.toLowerCase();
let rows = document.querySelectorAll("#table tr");


rows.forEach((row, index)=>{
if(index===0) return;

row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
});
});
</script>

</body>
</html>
