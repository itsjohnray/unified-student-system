<?php
include "config.php";

$message="";
$error="";

if(isset($_POST['register'])){
    $u=$_POST['username'];
    $p=$_POST['password'];
    $cp=$_POST['confirm'];
    $c=$_POST['course'];
    $year=$_POST['year'];
    $section=$_POST['section'];

    if($p != $cp){
        $error="Passwords do not match!";
    }else{
        $conn->query("INSERT INTO users(username,password,course_id,year_level,section) 
        VALUES('$u','$p','$c','$year','$section')");
        $message="Registration Successful!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI';}

body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
overflow:hidden;
background:
linear-gradient(rgba(128,0,0,0.5),rgba(40,0,0,0.6)),
url('/bg1.jpg');
background-size:cover;
background-position:center;
}

/* ✨ FLOATING PARTICLES */
body::before, body::after{
content:"";
position:absolute;
width:600px;
height:600px;
border-radius:50%;
filter:blur(180px);
opacity:0.4;
animation:move 10s infinite alternate;
}

body::before{
background:#ff4d4d;
top:-150px;
left:-100px;
}

body::after{
background:#ffb347;
bottom:-150px;
right:-100px;
}

@keyframes move{
0%{transform:translateY(0);}
100%{transform:translateY(40px);}
}

/* CONTAINER */
.container{
display:flex;
width:950px;
border-radius:25px;
overflow:hidden;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(25px);
box-shadow:0 30px 80px rgba(0,0,0,0.8);
animation:fadeIn 1s ease;
}

/* LEFT SIDE */
.left{
flex:1;
background:linear-gradient(135deg,#800000,#a00000);
color:white;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
padding:40px;
text-align:center;
}

.left h1{
font-size:32px;
margin-top:15px;
}

.left p{
opacity:0.9;
margin-top:10px;
}

/* LOGO */
.logo{
width:150px;
height:150px;
border-radius:50%;
overflow:hidden;
animation:float 3s infinite;
box-shadow:0 20px 50px rgba(0,0,0,0.6);
transition:0.3s;
}

.logo:hover{
transform:scale(1.15);
}

.logo img{
width:100%;
height:100%;
object-fit:cover;
}

@keyframes float{
0%{transform:translateY(0);}
50%{transform:translateY(-15px);}
100%{transform:translateY(0);}
}

/* RIGHT */
.right{
flex:1;
padding:45px;
color:white;
}

.right h2{
text-align:center;
margin-bottom:20px;
font-size:28px;
}

/* INPUT */
.input-box{
position:relative;
margin-bottom:15px;
}

.input-box input,
.input-box select{
width:100%;
padding:12px 12px 12px 35px;
border:none;
border-radius:10px;
outline:none;
background:rgba(255,255,255,0.2);
color:white;
transition:0.3s;
}

.input-box input:focus,
.input-box select:focus{
background:rgba(255,255,255,0.3);
box-shadow:0 0 10px rgba(255,255,255,0.4);
}

.input-box i{
position:absolute;
left:10px;
top:12px;
}

/* PASSWORD BAR */
.bar{
height:6px;
background:#222;
border-radius:5px;
overflow:hidden;
margin-top:-10px;
margin-bottom:10px;
}

.bar span{
display:block;
height:100%;
width:0%;
background:red;
transition:0.3s;
}

/* BUTTON */
.btn{
width:100%;
padding:14px;
border:none;
border-radius:12px;
background:linear-gradient(45deg,#ff4d4d,#ff0000);
color:white;
font-weight:bold;
cursor:pointer;
transition:0.3s;
}

.btn:hover{
transform:scale(1.08);
box-shadow:0 15px 30px rgba(255,0,0,0.6);
}

/* ALERT */
.success{
background:#2ecc71;
padding:10px;
border-radius:8px;
margin-bottom:10px;
text-align:center;
}

.error{
background:#e74c3c;
padding:10px;
border-radius:8px;
margin-bottom:10px;
text-align:center;
}

/* LINK */
.link{
text-align:center;
margin-top:10px;
}

.link a{
color:#fff;
text-decoration:underline;
}

/* ANIMATION */
@keyframes fadeIn{
from{opacity:0; transform:translateY(40px);}
to{opacity:1; transform:translateY(0);}
}

/* RESPONSIVE */
@media(max-width:768px){
.container{
flex-direction:column;
width:95%;
}
}
</style>

</head>

<body>

<div class="container">

<!-- LEFT -->
<div class="left">
<div class="logo">
<img src="/logo1.jpg">
</div>

<h1>Join OLSHCO</h1>
<p>Create your account and start your journey</p>
</div>

<!-- RIGHT -->
<div class="right">

<h2>Create Account</h2>

<?php if($message!=""){ echo "<div class='success'>$message</div>"; } ?>
<?php if($error!=""){ echo "<div class='error'>$error</div>"; } ?>

<form method="POST">

<div class="input-box">
<i>👤</i>
<input type="text" name="username" placeholder="Username" required>
</div>

<div class="input-box">
<i>🔒</i>
<input type="password" id="pass" name="password" placeholder="Password" required onkeyup="strength()">
</div>

<div class="bar"><span id="bar"></span></div>

<div class="input-box">
<i>🔐</i>
<input type="password" id="confirm" name="confirm" placeholder="Confirm Password" required onkeyup="match()">
</div>

<div class="input-box">
<select name="course" required>
<option value="">Select Course</option>
<?php
$courses=$conn->query("SELECT * FROM courses");
while($c=$courses->fetch_assoc()){
echo "<option value='".$c['id']."'>".$c['course_name']."</option>";
}
?>
</select>
</div>

<div class="input-box">
<select name="year" required>
<option>Year Level</option>
<option>1st Year</option>
<option>2nd Year</option>
<option>3rd Year</option>
<option>4th Year</option>
</select>
</div>

<div class="input-box">
<select name="section" required>
<option>Section</option>
<option>Block A</option>
<option>Block B</option>
</select>
</div>

<button name="register" class="btn">Create Account</button>

</form>

<div class="link">
<p>Already have an account? <a href="index.php">Login</a></p>
</div>

</div>

</div>

<script>
function strength(){
let p=document.getElementById("pass").value;
let bar=document.getElementById("bar");

if(p.length<5){
bar.style.width="30%";
bar.style.background="red";
}else if(p.match(/[A-Z]/) && p.match(/[0-9]/)){
bar.style.width="100%";
bar.style.background="lime";
}else{
bar.style.width="70%";
bar.style.background="orange";
}
}

function match(){
let p=document.getElementById("pass").value;
let c=document.getElementById("confirm").value;

if(c!="" && p!==c){
document.getElementById("confirm").style.border="2px solid red";
}else{
document.getElementById("confirm").style.border="none";
}
}
</script>

</body>
</html>
