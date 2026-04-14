<?php
include "config.php";
if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

$username=$_SESSION['username'];

/* CHECK APPROVAL */
$check = $conn->query("
SELECT * FROM requests 
WHERE username='$username' 
AND type='certificate'
ORDER BY id DESC LIMIT 1
");

if($check->num_rows == 0){
    echo "<h2 style='text-align:center;margin-top:50px;color:#800000;'>
    📄 You need to request your certificate first.
    </h2>";
    echo "<div style='text-align:center;margin-top:20px;'>
    <a href='dashboard.php'>
    <button style='padding:10px 20px;background:#800000;color:white;border:none;border-radius:5px;'>⬅ Back to Dashboard</button>
    </a>
    </div>";
    exit();
}

$request = $check->fetch_assoc();

if($request['status'] == "pending"){
    echo "<h2 style='text-align:center;margin-top:50px;color:orange;'>
    ⏳ Your certificate request is still pending approval.
    </h2>";
    echo "<div style='text-align:center;margin-top:20px;'>
    <a href='dashboard.php'>
    <button style='padding:10px 20px;background:#800000;color:white;border:none;border-radius:5px;'>⬅ Back to Dashboard</button>
    </a>
    </div>";
    exit();
}

if($request['status'] == "rejected"){
    echo "<h2 style='text-align:center;margin-top:50px;color:red;'>
    ❌ Your certificate request was rejected. Contact admin.
    </h2>";
    echo "<div style='text-align:center;margin-top:20px;'>
    <a href='dashboard.php'>
    <button style='padding:10px 20px;background:#800000;color:white;border:none;border-radius:5px;'>⬅ Back to Dashboard</button>
    </a>
    </div>";
    exit();
}

/* USER + COURSE */
$user=$conn->query("
SELECT users.*, courses.course_name 
FROM users 
LEFT JOIN courses ON users.course_id = courses.id 
WHERE users.username='$username'
")->fetch_assoc();

/* GRADES */
$grades=$conn->query("SELECT * FROM grades WHERE username='$username'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Certificate of Grades</title>

<style>
body{
font-family:'Times New Roman';
background:#eee;
}

.paper{
width:800px;
margin:30px auto;
background:white;
padding:40px;
border:8px double #800000;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.header{
text-align:center;
margin-bottom:20px;
}

.logo{
width:80px;
}

.info{
width:100%;
margin-top:10px;
}

.info td{
padding:6px;
font-size:16px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

th,td{
border:1px solid black;
padding:8px;
text-align:center;
}

th{
background:#800000;
color:white;
}

.actions{
text-align:center;
margin-top:20px;
}

button{
padding:10px 20px;
background:#800000;
color:white;
border:none;
cursor:pointer;
border-radius:5px;
transition:0.3s;
}

button:hover{
background:#a00000;
transform:scale(1.05);
}

.back-btn{
background:#333;
margin-left:10px;
}

.back-btn:hover{
background:#555;
}

.no-data{
text-align:center;
padding:20px;
color:#555;
font-style:italic;
}

@media print{
button{display:none;}
body{background:white;}
}
</style>

</head>

<body>

<div class="paper" id="printArea">

<!-- HEADER -->
<div class="header">
<img src="/logo.jpg" class="logo"><br>
<h2>Our Lady of the Sacred Heart College of Guimba Inc.</h2>
<h4>St. John, Guimba, Nueva Ecija</h4>
<h3>Certificate of Grades</h3>
</div>

<!-- STUDENT INFO -->
<table class="info">
<tr>
<td><b>Name:</b> <?php echo $username; ?></td>
<td><b>Course:</b> <?php echo !empty($user['course_name']) ? $user['course_name'] : 'N/A'; ?></td>
</tr>

<tr>
<td><b>Year Level:</b> <?php echo !empty($user['year_level']) ? $user['year_level'] : 'N/A'; ?></td>
<td><b>Section:</b> <?php echo !empty($user['section']) ? $user['section'] : 'N/A'; ?></td>
</tr>
</table>

<?php
if($grades->num_rows == 0){
    echo "<div class='no-data'>No grades available yet.</div>";
}else{

echo "<table>";
echo "<tr><th>Subject</th><th>Grade</th></tr>";

while($row=$grades->fetch_assoc()){
    echo "<tr>
            <td>".$row['subject']."</td>
            <td>".$row['grade']."</td>
          </tr>";
}

echo "</table>";
}
?>

<br><br>

<p>________________________</p>
<p>Registrar</p>

</div>

<div class="actions">
<button onclick="window.print()">Print</button>

<a href="dashboard.php">
<button class="back-btn">⬅ Back to Dashboard</button>
</a>
</div>

</body>
</html>
