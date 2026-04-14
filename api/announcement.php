<?php
include "config.php";
if(!isAdmin()){ header("Location:index.php"); exit(); }

if(isset($_POST['post'])){
    $conn->query("INSERT INTO announcements(title,message)
    VALUES('$_POST[title]','$_POST[message]')");
}

if(isset($_GET['delete'])){
    $conn->query("DELETE FROM announcements WHERE id='$_GET[delete]'");
}

$res=$conn->query("SELECT * FROM announcements ORDER BY id DESC");
?>

<body style="font-family:Arial;background:#f4f4f4;padding:30px;">

<h2 style="color:#800000;">Announcements</h2>

<form method="POST" style="margin-bottom:20px;">
<input name="title" placeholder="Title" style="width:100%;padding:10px;"><br><br>
<textarea name="message" placeholder="Message" 
style="width:100%;height:100px;padding:10px;"></textarea><br><br>
<button name="post" style="background:#800000;color:white;padding:10px 20px;border:none;">Post</button>
</form>

<?php while($row=$res->fetch_assoc()){ ?>
<div style="background:white;padding:15px;margin-bottom:10px;border-radius:10px;">
<h3><?php echo $row['title']; ?></h3>
<p><?php echo $row['message']; ?></p>
<a href="?delete=<?php echo $row['id']; ?>" style="color:red;">Delete</a>
</div>
<?php } ?>

<br>
<a href="admin_dashboard.php">⬅ Back</a>

</body>
