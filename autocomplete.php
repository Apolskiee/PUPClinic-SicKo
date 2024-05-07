<?php
require_once('src/includes/connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['input'])) {
        // If 'input' parameter is provided, return autocomplete suggestions
        $input = mysqli_real_escape_string($conn, $_POST['input']);

        $sql = "SELECT first_name, last_name, patient_id FROM patient WHERE first_name LIKE '%$input%' OR last_name LIKE '%$input%' LIMIT 5";
        $result = mysqli_query($conn, $sql);
        $names = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $names[] = $row;
        }
        echo json_encode($names);
        exit();
    } elseif (isset($_POST['patient_id'])) {
        // If 'patient_id' parameter is provided, return patient data including birthdate
        $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);

        $sql = "SELECT *, DATE_FORMAT(birthday, '%Y-%m-%d') AS formatted_birthday FROM patient WHERE patient_id = '$patient_id'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            // Include formatted birthdate in the response
            $row['birthdate'] = $row['formatted_birthday'];
            unset($row['formatted_birthday']); // Remove the formatted_birthday field from the response
            echo json_encode($row);
            exit();
        } else {
            echo json_encode(array()); // Return empty array if patient not found
            exit();
        }
    }
}
mysqli_close($conn);
?>
