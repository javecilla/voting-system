<?php 
  http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 NOT FOUND</title>
  <link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,900" rel="stylesheet">
  <style type="text/css">
          body {
        background-image: url('/resources/images/gmc-bg.png');
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-size: cover;
        overflow-x: hidden;
        overflow-y: hidden;
      }
    #notfound {
  position: relative;
  height: 100vh;
}

#notfound .notfound {
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
}

.notfound {
  max-width: 920px;
  width: 100%;
  line-height: 1.4;
  text-align: center;
  padding-left: 15px;
  padding-right: 15px;
}

.notfound .notfound-404 {
  position: absolute;
  height: 100px;
  top: 0;
  left: 50%;
  -webkit-transform: translateX(-50%);
      -ms-transform: translateX(-50%);
          transform: translateX(-50%);
  z-index: -1;
}

.notfound .notfound-404 h1 {
  font-family: 'Maven Pro', sans-serif;
  color: lightgray;
  font-weight: 900;
  font-size: 276px;
  margin: 0px;
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
}

.notfound h2 {
  font-family: 'Maven Pro', sans-serif;
  font-size: 46px;
  color: #000;
  font-weight: 900;
  text-transform: uppercase;
  margin: 0px;
}

.notfound p {
  font-family: 'Maven Pro', sans-serif;
  font-size: 16px;
  color: #000;
  font-weight: 400;
  text-transform: uppercase;
  margin-top: 15px;
}

.notfound a {
  font-family: 'Maven Pro', sans-serif;
  font-size: 14px;
  text-decoration: none;
  text-transform: uppercase;
  background-image: linear-gradient(to right, #5a3b2f, #b4813f); 
  color: #f3f3f3!important;
  display: inline-block;
  padding: 10px 31px;
  border: 2px solid transparent;
  border-radius: 40px;
  font-weight: 400;
  cursor: pointer;
}

.notfound a:hover {
  opacity: .8;
}

@media only screen and (max-width: 480px) {
  .notfound .notfound-404 h1 {
    font-size: 162px;
  }
  .notfound h2 {
    font-size: 26px;
  }
}
  </style>
</head>
<body>
  <div id="notfound">
    <div class="notfound">
      <div class="notfound-404">
        <h1>404</h1>
      </div>
      <h2>We are sorry, Page not found!</h2>
      <p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
      <a onclick="window.history.go(-1);" class="text-white">Back to previous page</a>
    </div>
  </div>
</body>
</html>