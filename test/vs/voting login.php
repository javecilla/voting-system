<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>GMCVS Signin</title>
  <?php require_once __DIR__ . '/resources/api/links.inc.php'; ?>
  <link href="resources/vendor/plugins/aos/dist/aos.css" rel="stylesheet" type="text/css"/>
  <script src="resources/vendor/plugins/aos/dist/aos.js"></script>
  <style type="text/css">
    /*.area {
      min-height: 86vh!important;
    }*/
  </style>
</head>

<body>

  <div class="main-wrapper">
  
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
      style="background:url(resources/images/gmc-bg.png) no-repeat center center;">
      <div class="auth-box row ">
        <div class="col-lg-6 col-md-4 area p-3">
          <div class="mt-5">
            <h2 class="text-white">Welcome to</h2>
            <h3 class="text-white">Golden Minds Colleges- Voting System</h3>
            <p class="text-white mt-3">
             This system for is for authorized users only, if you do not have an account, please contact the system administrator to request access.
            </p>
          </div>           
          <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
          </ul>
        </div>
        <div class="col-lg-6 col-md-8 bg-white" >
          <div class="p-3">
            <div class="text-center">
              <img src="resources/images/gmc_logo.png" alt="GMC Logo" width="100">
            </div>
            <h3 class="mt-3 text-center">GMCVS v1.0</h3>
            <p class="text-center">Back to <a href="voting system.php" class="link"><u>Voting</u></a></p>
            <form action="resources/api/gmcvoting.contr.php" method="POST" class="mt-4">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group mb-3">
                    <input class="form-control" type="text" id="unameid" name="uname" placeholder="Username" autocomplete="on" />
                    <small class="text-danger" id="uname_errormsg"></small>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group mb-3">
                    <input class="form-control" type="password" id="pword" name="pword" placeholder="Password" autocomplete="on" />
                    <small class="text-danger" id="pword_errormsg"></small>
                  </div>
                </div>
                <div class="col-lg-12 text-center">
                  <button type="submit" name="isLogin" class="btn w-100 btnLogin"
                  style="background: #CA8606!important; color: #fff">Sign In</button>
                  
                </div>
              <div class="col-lg-12 mt-1 mb-2 text-center">
                <small class="concern mt-5">For more inquiries and concerns, email us at gmcbulac@gmail.com</small>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!--End login box-->
  </div>

  <?php
  if(isset($_GET['error']) && !empty($_GET['error'])) {
    ?>
    <script type="text/javascript">
      var erroMsg = '<?php echo $_GET['error']?>';
      if(erroMsg === 'empty_fields') {
        $('#unameid').addClass('is-invalid');
        $('#uname_errormsg').text('Username is required!');
        $('#pword').addClass('is-invalid');
        $('#pword_errormsg').text('Password is required!');
      }
      else if(erroMsg === 'invalid_username') {
        $('#unameid').addClass('is-invalid');
        $('#uname_errormsg').text('Invalid Username');
      } 
      else if(erroMsg === 'invalid_password') {
        $('#pword').addClass('is-invalid');
        $('#pword_errormsg').text('Invalid Password');
      }
      
    </script>
    <?php
  } else {
    ?>
    <script type="text/javascript">
      $('#uname').removeClass('is-invalid');
      $('#uname_errormsg').text('');
      $('#pword').removeClass('is-invalid');
      $('#pword_errormsg').text('');
    </script>
    <?php
  }
  ?>


</body>

</html>