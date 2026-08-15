<body>
    <div class="main-wrapper">
        <div class="header" style="background-color: #427996;">
			<div class="header-left">
				<a href="<?= $base_url; ?>dashboard.php" class="logo">
					<img src="<?= $base_url; ?>assets/assets/img/logo.png" width="35" height="35" alt=""> <span style="font-size: 20px;">SHIFA</span>
                    <span style="color: #fff; font-size: 12px; position: absolute; top: 34px; left: 90px;">Hospital Management</span>
				</a>
                
			</div>
			<a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
            <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
            <ul class="nav user-menu float-right">
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <i class="fa fa-user pr-2"></i><span class="auth-role" style="padding-right: 5px;"><?= $_SESSION['user_role']; ?></span>[<span class="auth-name"><?= $_SESSION['user_name']; ?></span>]
                    </a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="profile.html"><i class="fa fa-user-circle"></i> My Profile</a>
						<a class="dropdown-item" href="edit-profile.html"><i class="fa fa-edit"></i> Edit Profile</a>
						<a class="dropdown-item" href="settings.html"><i class="fa fa-cog"></i> Settings</a>
						<a class="dropdown-item" onclick="return confirm('Are you sure want to log out?')" href="<?= $base_url; ?>logout.php"><i class="fa fa-sign-out"></i> Logout</a>
					</div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile.html">My Profile</a>
                    <a class="dropdown-item" href="edit-profile.html">Edit Profile</a>
                    <a class="dropdown-item" href="settings.html">Settings</a>
                    <a class="dropdown-item" onclick="return confirm('Are you sure want to log out?')" href="<?= $base_url; ?>logout.php">Logout</a>
                </div>
            </div>
        </div>
<div class="sidebar" id="sidebar" style="background-color: #104d6f;">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title" style="color: #fff;">Main</li>
                        <li>
                            <a href="<?= $base_url; ?>dashboard.php"><i class="fa fa-dashboard" style="color: #fff;"></i> <span style="color: #fff;">Dashboard</span></a>
                        </li>
						<li>
                            <a href="<?= $base_url; ?>doctors/doctors.php"><i class="fa fa-user-md" style="color: #fff;"></i> <span style="color: #fff;">Doctors</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>patients/patients.php"><i class="fa fa-wheelchair" style="color: #fff;"></i> <span style="color: #fff;">Patients</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>appointment/appointment_list.php"><i class="fa fa-calendar" style="color: #fff;"></i> <span style="color: #fff;">Appointments</span></a>
                        </li>
                        <li>
                            <a href="<?= $base_url; ?>schedule/schedule.php"><i class="fa fa-calendar-check-o" style="color: #fff;"></i> <span style="color: #fff;">Doctor Schedule</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>prescription/prescription_list.php"><i class="fa fa-file-text-o" style="color: #fff;"></i> <span style="color: #fff;">Prescriptions</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>lab/lab_category/lab.php"><i class="fa fa-flask" style="color: #fff;"></i> <span style="color: #fff;">Test Lab</span></a>
                        </li>
                        <li class="submenu">
							<a href="#"><i class="fa fa-bed" style="color: #fff;"></i> <span style="color: #fff;"> Ward </span> <span class="menu-arrow" style="color: #fff;"></span></a>
							<ul style="display: none;">
								<li><a href="<?= $base_url; ?>ward/rooms/rooms.php">Rooms</a></li>
								<li><a href="<?= $base_url; ?>ward/beds/beds.php">Beds</a></li>
								<li><a href="<?= $base_url; ?>ward/patients_addmission/admitted_patient_list.php">Patient Admission</a></li>
							</ul>
						</li>
                        <li>
                            <a href="<?= $base_url; ?>departments/departments.php"><i class="fa fa-hospital-o" style="color: #fff;"></i> <span style="color: #fff;">Departments</span></a>
                        </li>
                        <li class="submenu">
							<a href="#"><i class="fa fa-money" style="color: #fff;"></i> <span style="color: #fff;"> Accounts </span> <span class="menu-arrow" style="color: #fff;"></span></a>
							<ul style="display: none;">
								<li><a href="<?= $base_url; ?>invoices/invoice_list.php">Invoices</a></li>
								<li><a href="<?= $base_url; ?>payments/payments_list.php">Payments</a></li>
                                </ul>
						</li>
                        <li>
                            <a href="<?= $base_url; ?>designation/designation.php"><i class="fa fa-id-badge" style="color: #fff;"></i> <span style="color: #fff;">Designation</span></a>
                        </li>
                        <li>
                            <a href="<?= $base_url; ?>shift/shift.php"><i class="fa fa-clock-o" style="color: #fff;"></i> <span style="color: #fff;">Shift</span></a>
                        </li>
                        <li>
                            <a href="<?= $base_url; ?>pharmacy/pharmacy.php"><i class="fa fa-medkit" style="color: #fff;"></i> <span style="color: #fff;">Pharmacy</span></a>
                        </li>
                        <li class="submenu">
							<a href="#"><i class="fa fa-tint" style="color: #fff;"></i> <span style="color: #fff;"> Blood Bank </span> <span class="menu-arrow" style="color: #fff;"></span></a>
							<ul style="display: none;">
								<li><a href="<?= $base_url; ?>blood_bank/donor/donor.php">Donors List</a></li>
								<li><a href="<?= $base_url; ?>blood_bank/blood_collection/collection.php">Blood Collection</a></li>
                                </ul>
						</li>
                    </div>
            </div>
        </div> 