<?php
require_once ('src/includes/session-nurse.php');
require_once ('src/includes/connect.php');

// NOT YET WORKING.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $symptoms = mysqli_real_escape_string($conn, $_POST['symptoms']);
    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $treatments = mysqli_real_escape_string($conn, $_POST['treatments']);

    $sql = "INSERT INTO treatment_record (patient_id, full_name, gender, age, course, section, symptoms, diagnosis, treatments) 
            VALUES ('$patient_id', '$full_name', '$gender', '$age', '$course', '$section', '$symptoms', '$diagnosis', '$treatments')";

    if (mysqli_query($conn, $sql)) {
        header("Location: treatment-record-confirmation.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    $query_params = http_build_query([
        'patient_id' => $patient_id,
        'full_name' => $full_name,
        'gender' => $gender,
        'age' => $age,
        'course' => $course,
        'section' => $section,
        'symptoms' => $symptoms,
        'diagnosis' => $diagnosis,
        'treatments' => $treatments
    ]);
    header("Location: treatment-record-confirmation.php?$query_params");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SicKo - Treatment Record</title>
    <link rel="icon" type="image/png" href="src/images/sicko-logo.png">
    <link rel="stylesheet" href="src/styles/dboardStyle.css">
</head>

<style>
    #search-results {
    position: absolute;
    top: calc(100% + 5px);
    width: 30%;
    background-color: #fff;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    z-index: 9999; /* Ensure the dropdown appears above other elements */
    }

    .input-row {
        position: relative; /* Ensure relative positioning for the parent */
    }

    /* Adjust input field position */
    .input-row input[type="text"],
    .input-row input[type="number"],
    .input-row select {
        width: calc(100% - 30px); /* Adjust the width to accommodate the dropdown */
        padding-right: 30px; /* Space for dropdown icon */
    }

</style>

<body>
    <div class="overlay" id="overlay"></div>

    <?php
    include ('src/includes/sidebar.php');
    ?>

    <div class="content" id="content">
        <div class="left-header">
            <p>
                <span style="color: #E13F3D;">Treatment</span>
                <span style="color: #058789;">Record</span>
            </p>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form id="treatment-form" action="treatment-record.php" method="post">
                <div class="input-row">
                <input type="hidden" id="patient_id" name="patient_id">
                <input type="text" id="full-name" name="full_name" placeholder="Full Name" autocomplete="off" required onkeyup="searchPatients(this.value)">
                <div id="search-results"></div>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected hidden>Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="number" name="age" id="age" placeholder="Age" required>
                </div>
                <div class="input-row">
                    <input type="text" id="course" name="course" placeholder="Course/Organization" autocomplete="off" required>
                    <select id="section" name="section" required>
                        <option value="" disabled selected hidden>Block Section</option>
                        <option value="1-1">1-1</option>
                        <option value="1-2">1-2</option>
                        <option value="2-1">2-1</option>
                        <option value="2-2">2-2</option>
                        <option value="3-1">3-1</option>
                        <option value="3-2">3-2</option>
                        <option value="4-1">4-1</option>
                        <option value="4-2">4-2</option>
                    </select>
                </div>
                <div class="right-row">
                    <p class="bold" onclick="window.location.href='ai-basedSDT.php'">Use AI Symptoms Diagnostic Tool</p>
                </div>
                <div class="input-row">
                    <input type="text" id="symptoms" name="symptoms" placeholder="Symptoms" autocomplete="off" value="<?php echo isset($_GET['symptoms']) ? $_GET['symptoms'] : ''; ?>" required>
                </div>
                <div class="input-row">
                    <input type="text" id="diagnosis" name="diagnosis" placeholder="Diagnosis" autocomplete="off" value="<?php echo isset($_GET['diagnosis']) ? $_GET['diagnosis'] : ''; ?>" required>
                    <input type="text" id="treatments" name="treatments" placeholder="Treatments/Medicines" autocomplete="off" value="<?php echo isset($_GET['treatments']) ? $_GET['treatments'] : ''; ?>" required>
                </div>
                <div class="right-row">
                    <button type="submit" id="submit-form-button"
                        name="record-btn">Submit Form</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    include ('src/includes/footer.php');
    ?>
    <script src="src/scripts/script.js"></script>
    <script>
function searchPatients(keyword) {
    if(keyword.length > 0) {
        // Perform an AJAX request
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Display search results in the search-results div
                document.getElementById("search-results").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "search-patients.php?keyword=" + keyword, true);
        xhttp.send();
    } else {
        document.getElementById("search-results").innerHTML = "";
    }
}

function selectPatient(patient_id, fullName, gender, age, course, section) {
    document.getElementById("full-name").value = fullName;
    document.getElementById("gender").value = gender;
    document.getElementById("age").value = age;
    document.getElementById("course").value = course;
    document.getElementById("section").value = section;
    document.getElementById("patient_id").value = patient_id;
}
</script>
</body>

</html>