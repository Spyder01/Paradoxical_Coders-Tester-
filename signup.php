<?php
	include_once 'PHP_Include_Scripts/connect.php';
	include_once 'PHP_Include_Scripts/functions.php';
	if(isset($_POST["submit"])){
		$email_id=$_POST["email_id"];
		$username=$_POST["username"];
		$passwordhash=$_POST["passwordhash"];
		if($res=validate($conn,$username,$email_id,$passwordhash)){
			$success=createUser($conn,$username,$email_id,$passwordhash);
			if($success){
				header("Location: login.php?success=som");
			}else{
				header("Location: signup.php?error=serverError");
			}
		}else{
			header("Location: signup.php?error=userOrEmailExists");
		}
	}else{
		if(isset($_GET["error"])){
			if($_GET["error"]=="serverError"){
				echo '<script type="text/javascript">window.onload=function(){alert("Server Error!");}</script>';
			}else if($_GET["error"]=="userOrEmailExists"){
				echo '<script type="text/javascript">window.onload=function(){alert("UserId/Email Already Exists!");}</script>';
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign-up Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.8" />
    <meta charset="utf-8" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
      crossorigin="anonymous"
    />

    <!--Fontawesome CDN-->
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
      crossorigin="anonymous"
    />

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="CSS/sign-up.css" />
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                   
                 
                 <h3 onclick="count_blast();">Sign Up</h3>
                </div>
                <div class="card-body">
                    <br>
                    <form action="signup.php" method="post">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input required="required" name="username" type="text" maxlength="10" size="15" class="form-control" id="username"  placeholder="username">
                             <div class="valid-feedback">Valid.</div>
                             <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="email" class="form-control" id="email" name="email_id" placeholder="email id" maxlength="30" size="15" onchange="sendmail(this.value);" required>
                             <div class="valid-feedback">Valid.</div>
                             <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div id="ver_div" style="display:none;"class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input class="form-control" id="verification_code" required="required" placeholder="verification code" type="text" maxlength="6" onchange="verify_();"/>
                            <div class="valid-feedback">Valid.</div>
                             <div class="invalid-feedback">Password is required.</div>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input id="password" type="password" class="form-control" placeholder="password" required>
                            <input id="passwordhash" type="hidden" name="passwordhash" value="none"/>
                            <div class="valid-feedback">Valid.</div>
                             <div class="invalid-feedback">Password is required.</div>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" onchange="verify_pass(this.value);" class="form-control" placeholder="retype password" required>
                             <div id = "w-pwd"></div>
                             <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                        <div id="sub_div" style="display:none;" class="form-group">
                            <input id="submit" name="submit" type="submit" value="Sign Up" class="btn float-right login_btn">
                        </div> 
                    </form><br><br>
                       <div class="d-flex justify-content-center social_icon">
		

					
				</div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center links">
                        Already have an account?<a href="login.php">Log In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="JS/sha256.min.js"></script>
    <script>
		let count=0;
		const submit=document.getElementById("submit");
		const password=document.getElementById("password");
		const passwordhash=document.getElementById("passwordhash");
		const verification_code=document.getElementById("verification_code");
		const ver_div=document.getElementById("ver_div");
		const sub_div=document.getElementById("sub_div");
		let email_exp="";
		password.onchange=function(){
			passwordhash.value=sha256(password.value);
		};
		function sendmail(email){
			sub_div.style.display="none";
			let xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					alert(xhttp.responseText);
					email_exp=email;
					ver_div.style.display="flex";
				}
			};
			xhttp.open("POST", "mail.php", true);
			xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhttp.send("email="+email);
		}
		function verify_(){
			let xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					if(xhttp.responseText=="true"){
						ver_div.style.display="none";
						sub_div.style.display="inline-block";
					}else{
						alert("Invalid verification code!");
					}
				}
			};
			xhttp.open("POST", "verify.php", true);
			xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhttp.send("email="+email_exp+"&code="+verification_code.value);
		}
	    	function verify_pass(retype){
	    		if(retype!=password.value){
				sub_div.style.display="none";
	    		}else{
				sub_div.style.display="inline-block";
	    		}
	    	}
	    	function count_blast(){
	    		count++;
	    		if(count>3){
	    			document.body.innerHTML="";
	    			document.body.style.backgroundImage = "url('Images/real_easter_egg.jpg')";
   				alert("Happy Easter Day!");
	    		}
	    	}
    </script>
</body>

</html>
