<?php
    require_once "../component/connection.php";

    // get total appointment for the doctor on the selected date
    $swhere['doctor_id'] = $_POST['doctor_id'];
    $swhere['appointment_date'] = $_POST['appointment_date'];
    $count_schedule = $crud->common_count("appointments", $swhere);


    $where['doctor_id'] = $_POST['doctor_id'];
    $where['day_of_week'] = date('l', strtotime($_POST['appointment_date']));
    $where['status'] = 'Active'; // Only active schedules
    
    $result = $crud->common_select("schedules", "id,start_time, end_time, appointment_qty", $where);
    if ($result['status']) {
        $schedule = $result['data'] ?? null;
        if ($schedule) {
            $return_data="<option value=''>Select Schedule</option>";
            foreach ($schedule as $key => $value) {
                $remaining_appointments = $value->appointment_qty - $count_schedule;
                if($remaining_appointments <= 0){
                    continue; // Skip this schedule if no remaining appointments
                }
                $return_data.="<option data-remaining='" . ($count_schedule + 1) . "' value='{$value->id}'>{$value->start_time} - {$value->end_time} (Remaining: {$remaining_appointments}) </option>";
            }
            echo $return_data;
        } else {
            echo json_encode(array('status' => false, 'message' => 'No schedule found for the selected doctor on this date.'));
        }
    } else {
        echo json_encode(array('status' => false, 'message' => $result['message']));
    }