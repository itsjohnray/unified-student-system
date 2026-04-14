<?php
include "config.php";

$error="";

if(isset($_POST['login'])){
    $u=$_POST['username'];
    $p=$_POST['password'];

    $res=$conn->query("SELECT * FROM users WHERE username='$u'");
    if($res->num_rows>0){
        $user=$res->fetch_assoc();

        if($p == $user['password']){
            $_SESSION['username']=$u;
            $_SESSION['role']=$user['role'];

            // Update last_active
            $conn->query("UPDATE users SET last_active=NOW() WHERE username='$u'");

            if($user['role']=="admin"){
                header("Location: admin_dashboard.php");
                exit();
            }else{
                header("Location: dashboard.php");
                exit();
            }
        }else{
            $error="Wrong password!";
        }
    }else{
        $error="User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>OLSHCO Login</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}

body{height:100vh;overflow:hidden;}

.bg{
position:absolute;
width:100%;
height:100%;
background:
linear-gradient(rgba(128,0,0,0.7),rgba(40,0,0,0.85)),
url('/bg1.jpg');
background-size:cover;
background-position:center;
z-index:-1;
}

.navbar{
position:fixed;
top:0;
width:100%;
padding:15px 50px;
display:flex;
justify-content:space-between;
align-items:center;
background:rgba(128,0,0,0.4);
backdrop-filter:blur(10px);
color:white;
}

.container{
height:100vh;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
}

.card{
display:flex;
width:900px;
border-radius:20px;
overflow:hidden;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(20px);
box-shadow:0 20px 50px rgba(0,0,0,0.6);
margin-bottom:20px;
}

.left{
flex:1;
padding:40px;
color:white;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
text-align:center;
}

.left h1{
font-size:32px;
margin-bottom:10px;
}

.logo{
width:200px;
height:200px;
border-radius:50%;
overflow:hidden;
margin-top:20px;
animation:float 3s ease-in-out infinite;
transition:0.3s;
box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

.logo img{
width:100%;
height:100%;
object-fit:cover;
}

.logo:hover{
transform:scale(1.1);
}

@keyframes float{
0%{transform:translateY(0);}
50%{transform:translateY(-15px);}
100%{transform:translateY(0);}
}

.right{
flex:1;
background:white;
padding:40px;
text-align:center;
}

.right h2{
color:#800000;
margin-bottom:20px;
}

.input-box{
margin-bottom:15px;
}

.input-box input{
width:100%;
padding:12px;
border-radius:8px;
border:1px solid #ccc;
}

.btn{
width:100%;
padding:12px;
border:none;
border-radius:8px;
background:#800000;
color:white;
font-weight:bold;
cursor:pointer;
transition:0.3s;
}

.btn:hover{
background:#a00000;
transform:scale(1.05);
}

.error{
color:red;
margin-bottom:10px;
}

.courses{
margin-top:10px;
text-align:center;
color:white;
animation:fadeIn 1s ease;
}

.courses h2{
font-size:26px;
margin-bottom:5px;
letter-spacing:1px;
}

.course-sub{
font-size:14px;
opacity:0.8;
margin-bottom:20px;
}

.course-grid{
display:flex;
justify-content:center;
gap:20px;
flex-wrap:wrap;
}

.course{
width:120px;
height:120px;
background:rgba(255,255,255,0.1);
border-radius:15px;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
backdrop-filter:blur(12px);
box-shadow:0 8px 20px rgba(0,0,0,0.3);
transition:0.3s;
cursor:pointer;
position:relative;
overflow:hidden;
}

.course span{
font-size:28px;
margin-bottom:5px;
}

.course h4{
font-size:14px;
letter-spacing:1px;
}

.course:hover{
transform:translateY(-10px) scale(1.08);
background:rgba(255,255,255,0.25);
box-shadow:0 15px 30px rgba(0,0,0,0.5);
}

.course::before{
content:"";
position:absolute;
width:100%;
height:100%;
background:linear-gradient(120deg,transparent,rgba(255,255,255,0.4),transparent);
top:-100%;
left:-100%;
transition:0.5s;
}

.course:hover::before{
top:100%;
left:100%;
}

@keyframes fadeIn{
from{opacity:0; transform:translateY(20px);}
to{opacity:1; transform:translateY(0);}
}

@media(max-width:768px){
.card{flex-direction:column;width:90%;}
}
</style>

</head>

<body>

<div class="bg"></div>

<div class="navbar">
<h3>OUR LADY OF THE SACRED HEART COLLEGE OF GUIMBA INC</h3>
<a href="register.php" style="color:white;">Register</a>
</div>

<div class="container">

<div class="card">

<div class="left">
<h1>Welcome Back</h1>
<p>Access your academic records anytime, anywhere.</p>

<div class="logo">
<img src="/logo1.jpg">
</div>
</div>

<div class="right">

<h2>Login Account</h2>

<?php if($error!=""){ echo "<div class='error'>$error</div>"; } ?>

<form method="POST">

<div class="input-box">
<input type="text" name="username" placeholder="Username" required>
</div>

<div class="input-box">
<input type="password" name="password" placeholder="Password" required>
</div>

<button name="login" class="btn">Login</button>

</form>

<br>
<p>No account? <a href="register.php">Register</a></p>

</div>

</div>

<div class="courses">

<h2>Our Courses</h2>
<p class="course-sub">Explore the programs we offer</p>

<div class="course-grid">

<div class="course"><span>💻</span><h4>BSIT</h4></div>
<div class="course"><span>📊</span><h4>BSBA</h4></div>
<div class="course"><span>📚</span><h4>BSED</h4></div>
<div class="course"><span>🧑‍🏫</span><h4>BEED</h4></div>
<div class="course"><span>🍽️</span><h4>BSHM</h4></div>

</div>

</div>

</div>

</body>
</html>
