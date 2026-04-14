<?php
include "config.php";
if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

$username = $_SESSION['username'];

/* GET 1ST SEM GRADES */
$grades = $conn->query("
SELECT * FROM grades 
WHERE username='$username' AND semester='1st'
");

/* AVERAGE */
$avg = $conn->query("
SELECT AVG(grade) as avg_grade 
FROM grades 
WHERE username='$username' AND semester='1st'
")->fetch_assoc();

$average = $avg['avg_grade'] ? round($avg['avg_grade'],2) : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>1st Semester Grades</title>

<style>
body{
font-family:'Segoe UI';
background:#f5f6fa;
}

.container{
width:800px;
margin:40px auto;
background:white;
padding:30px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
text-align:center;
color:#800000;
margin-bottom:20px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
}

th,td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:center;
}

th{
background:#800000;
color:white;
}

.avg{
margin-top:20px;
text-align:right;
font-size:16px;
}

.btn{
margin-top:20px;
padding:10px 15px;
background:#800000;
color:white;
border:none;
border-radius:6px;
cursor:pointer;
}

.btn:hover{
background:#a00000;
}

.no-data{
text-align:center;
padding:20px;
color:#777;
}
</style>

</head>

<body>

<div class="container">

<h2>1st Semester Grades</h2>

<?php if($grades->num_rows == 0){ ?>
<div class="no-data">No grades available</div>
<?php } else { ?>

<table>
<tr>
<th>Subject</th>
<th>Grade</th>
</tr>

<?php while($row=$grades->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['subject']; ?></td>
<td><?php echo $row['grade']; ?></td>
</tr>
<?php } ?>

</table>

<div class="avg">
<b>Average: <?php echo $average; ?></b>
</div>

<?php } ?>

<a href="dashboard.php">
<button class="btn">⬅ Back to Dashboard</button>
</a>

</div>

</body>
</html>
