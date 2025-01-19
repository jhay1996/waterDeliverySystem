<style>
	.logo {
    margin: auto;
    background: white;
    border-radius: 50% 50%;
    color: #000000b3;
    height: 50px;
    width: 50px;
    position: relative;
    overflow:hidden;
}
.logo>img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
}
#topNavBar{
  background-color:#0072b2 !important;
  background:#0072b2 !important;
}
</style>

<nav class="navbar navbar-dark bg-primary fixed-top " id="topNavBar" style="padding:0">
  <div class="container-fluid mt-2 mb-2">
  	<div class="col-lg-12">
      <div class="row align-items-center">
        <div class="col-md-1 float-left" style="display: flex;">
          <div class="logo">
            <img src="./../assets/defaults/ava.png" alt="<?php echo $_SESSION['setting_name']; ?> - Brand Logo">
          </div>
        </div>
        <div class="col-md-9 float-left">
          <h4 style="font-family: 'Dancing Script', cursive !important;" class="text-light"><a href="./../" class="text-reset"><b><?php echo $_SESSION['setting_name']; ?></b></a></h4>
        </div>
        <div class="col-md-2 float-right">
          <a href="ajax.php?action=logout" class="text-light"><?php echo $_SESSION['login_name'] ?> <i class="fa fa-power-off"></i></a>
        </div>
      </div>
    </div>
  </div>
  
</nav>