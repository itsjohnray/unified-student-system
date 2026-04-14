<?php
include "config.php";
if(!isset($_SESSION['username'])){
    header("Location:index.php");
    exit();
}

$username=$_SESSION['username'];

/* MARK AS READ */
$conn->query("UPDATE messages SET status='read' WHERE receiver='$username'");

/* GET MESSAGES */
$messages=$conn->query("SELECT * FROM messages WHERE receiver='$username' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Messages</title>

<style>
body{
font-family:'Segoe UI';
background:#f5f6fa;
margin:0;
}

.container{
max-width:700px;
margin:30px auto;
background:white;
padding:20px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.msg{
padding:12px;
border-bottom:1px solid #eee;
}

.msg:last-child{border:none;}

.msg strong{color:#800000;}

.back{
display:inline-block;
margin-bottom:15px;
text-decoration:none;
color:white;
background:#800000;
padding:8px 15px;
border-radius:6px;
}
</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="back">⬅ Back to Dashboard</a>

<h2>Messages</h2>

<?php 
if($messages->num_rows==0){
echo "<p>No messages</p>";
}else{
while($m=$messages->fetch_assoc()){ ?>
<div class="msg">
<strong><?php echo $m['sender']; ?></strong>
<p><?php echo $m['message']; ?></p>
</div>
<?php }} ?>

</div>

</body>
</html>
