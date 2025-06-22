<div class="mobile_menue">
    <div class="mobile_inner">
       <span id="close"><i class="fa-solid fa-xmark"></i></span>
       <div class="menue-items-span asf"><span>  <?php  echo $_SESSION['username'];  ?></span></div>
       <div class="menue-items-span "><span class="mobile_menue_radi" onclick="dashboard_fun()">Dashboard</span></div>
       <div class="menue-items-span "><span class="mobile_menue_radi"onclick="rooms_fun()">Rooms</span></div>
       <div class="menue-items-span "><span class="mobile_menue_radi"onclick="avail_fun()">Available Seats</span></div>
       <div class="menue-items-span "><span class="mobile_menue_radi"onclick="view_std_fun()">Students</span></div>
       <div class="menue-items-span "><span class="mobile_menue_radi"onclick="adstd_fun()">Add Student</span></div>
       <div class="menue-items-span "><span class="mobile_menue_radi"><a class="mob-log" href="php/logout.php">Logout</a></span></div>
    </div>
</div>