<body>
    <div class="main-wrapper">
        <div class="header">
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
						<a class="dropdown-item" href="profile.html">My Profile</a>
						<a class="dropdown-item" href="edit-profile.html">Edit Profile</a>
						<a class="dropdown-item" href="settings.html">Settings</a>
						<a class="dropdown-item" onclick="return confirm('Are you sure want to log out?')" href="<?= $base_url; ?>logout.php">Logout</a>
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
<div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title">Main</li>
                        <li class="active">
                            <a href="<?= $base_url; ?>dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                        </li>
						<li>
                            <a href="<?= $base_url; ?>doctors/doctors.php"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>patients/patients.php"><i class="fa fa-wheelchair"></i> <span>Patients</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>appointment/appointment_list.php"><i class="fa fa-calendar"></i> <span>Appointments</span></a>
                        </li>
                        <li>
                            <a href="<?= $base_url; ?>schedule/schedule.php"><i class="fa fa-calendar-check-o"></i> <span>Doctor Schedule</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>prescription/prescription_list.php"><i class="fa fa-file-text-o"></i> <span>Prescriptions</span></a>
                        </li>
                        <li>
                            <a href="<?=$base_url; ?>lab/lab_category/lab.php"><i class="fa fa-flask"></i> <span>Test Lab</span></a>
                        </li>
                        <li class="submenu">
							<a href="#"><i class="fa fa-bed"></i> <span> Ward </span> <span class="menu-arrow"></span></a>
							<ul style="display: none;">
								<li><a href="<?= $base_url; ?>ward/rooms/rooms.php">Rooms</a></li>
								<li><a href="<?= $base_url; ?>ward/beds/beds.php">Beds</a></li>
								<li><a href="<?= $base_url; ?>ward/patients_addmission/admitted_patient_list.php">Patient Admission</a></li>
							</ul>
						</li>
                        <li>
                            <a href="<?= $base_url; ?>departments/departments.php"><i class="fa fa-hospital-o"></i> <span>Departments</span></a>
                        </li>
                        <li class="submenu">
							<a href="#"><i class="fa fa-money"></i> <span> Accounts </span> <span class="menu-arrow"></span></a>
							<ul style="display: none;">
								<li><a href="<?= $base_url; ?>invoices/invoice_list.php">Invoices</a></li>
								<li><a href="<?= $base_url; ?>payments/payments_list.php">Payments</a></li>
                                </ul>
						</li>
                        <li>
                            <a href="<?= $base_url; ?>designation/designation.php"><i class="fa fa-id-badge"></i> <span>Designation</span></a>
                        </li>
                        <li>
                            <a href="<?= $base_url; ?>shift/shift.php"><i class="fa fa-clock-o"></i> <span>Shift</span></a>
                        </li>
                        <li>
                            <a href="<?= $base_url; ?>pharmacy/pharmacy.php"><i class="fa fa-medkit"></i> <span>Pharmacy</span></a>
                        </li>
                        <li class="submenu">
							<a href="#"><i class="fa fa-tint"></i> <span> Blood Bank </span> <span class="menu-arrow"></span></a>
							<ul style="display: none;">
								<li><a href="<?= $base_url; ?>invoices/invoice_list.php">22</a></li>
								<li><a href="<?= $base_url; ?>payments/payments_list.php">11</a></li>
                                </ul>
						</li>
                    </div>
            </div>
        </div> 