<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{title}}</title>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0"/>
	<meta name="robots" content="index, follow">
	<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
	<meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>

	<link rel="shortcut icon" type="image/png" sizes="16x16" href="/resources/images/favicon.png" />
	<link rel="apple-touch-icon" type="image/png" sizes="16x16" href="/resources/images/favicon.png" />

	<!-- =========StyleSheets ========= -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" media="screen" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.min.css" rel="stylesheet"/>
	
	<link href="/assets/libs/aos/aos.css" rel="stylesheet"/>
	<link href="/assets/libs/fontawesome/css/all.min.css" rel="stylesheet"/>
	<link href="/assets/libs/bootstrap/bootstrap-icons.css" rel="stylesheet"/>
	<link href="/assets/tmp/boxicons/css/boxicons.min.css" rel="stylesheet"/> 
	<link href="/assets/tmp/glightbox/css/glightbox.min.css" rel="stylesheet"/> 
	<link href="/assets/plugins/swiper/swiper-bundle.min.css" rel="stylesheet"/>
	<link href="/resources/custom/stylesheet/app.css" rel="stylesheet" defer/>
	<link href="/resources/custom/stylesheet/main.css" rel="stylesheet" defer/>

	<!-- ========= Scripts ========= -->
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>


	<script src="/assets/plugins/purecounter/purecounter_vanilla.js"></script>
	<script src="/assets/libs/aos/aos.js"></script>
	<!-- <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script> -->
	<script src="/assets/tmp/glightbox/js/glightbox.min.js"></script>
	<script src="/assets/tmp/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="/assets/plugins/swiper/swiper-bundle.min.js"></script>
	<!-- <script src="/assets/plugins/typed.js/typed.min.js"></script> -->
	<script src="/assets/plugins/waypoints/noframework.waypoints.js"></script>
	<script src="/resources/custom/script/app.js" defer></script>
</head>
<body>
	<!-- ======= Mobile nav toggle button ======= -->
  <i class="bi bi-list mobile-nav-toggle d-xl-none admin_mobile_nav"></i>

  <!-- ======= Header ======= -->
	<header id="header" class="sidebar_admin">
	  <div class="d-flex flex-column">
	   	<div class="profile">
	      <img src="/resources/images/sample.jpg" alt="" class="img-fluid rounded-circle">
	      <h1 class="text-light"><span><a href="#"><small class="text-center">Voting System v1.2</small></a></span></h1>
	    </div>
	   	<nav id="navbar" class="nav-menu navbar">
	      <ul>
	        <li><a href="/admin/dashboard/" class="nav-link sb_dashboard">
	        	<i class="fas fa-igloo"></i> <span>Dashboard</span></a>
	        </li>
	        
	        <li><a href="/admin/voting-records/" class="nav-link sb_vrecords">
	        	<!-- <i class="fas fa-database"></i> -->
	        	<i class="fas fa-database"></i> <span>Votes Management</span></a>
	        </li> 
	        <li><a href="/admin/candidate-management/" class="nav-link sb_cmanagement">
	        	<i class="fas fa-users"></i> <span>Candidate Management</span></a>
	       	</li>
	         <li>
	        	<a href="/admin/candidates-ranking/" class="nav-link sb_crankings">
	        		<i class="fas fa-sort-amount-up"></i> <span>Candidates Ranking</span>
	        	</a>
	        </li>
	        <li>
	        	<a href="/buwan-ng-wikang-pambansa-2023-lakan-lakanbini-lakandyosa/candidates/" target="_blank" class="nav-link sb_upages">
	        		<i class="fas fa-pager"></i> <span>User Pages</span>
	        	</a>
	        </li>
	         <li>
	        	<a href="javascript:void(0)" class="nav-link " style="cursor: no-drop;">
	        		<i class="fa-solid fa-gear"></i> <span>Configuration</span>
	        	</a>
	        </li>
	        <li><a onclick="window.location.href='../../src/app/Actions/LogoutHandler.php?logout=1'" class="nav-link"
	        	style="cursor: pointer;">
	        	<i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
	        </li>
	      </ul>
	    </nav>
	  </div>
	</header>
	<!-- ======= Header ======= -->

	<!--===== Main Content of Page =====-->
	<main class="adminContent" id="main">
		{{contentAdmin}}
	</main>

	<main oncontextmenu="return false">
		{{contentUser}}
	</main>
	<!--===== End Main  =====-->

</body>
</html>