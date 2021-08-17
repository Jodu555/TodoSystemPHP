<?php

//Priority: 0 = Un Wichtig; 1 = Wichtig; 2 = Sehr Wichtig
//Status: 0 = Offen; 1 = In Bearbeitung; 2 = Erledigt

include('include.php');
include('checkIfLoggedIn.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(isset($_GET['delete'])) {
        $todoId = validate_input($_GET['delete']);
        if($todoId !== "") {
            deleteToDo($todoId);
            sendTo("dashboard.php");
        }
    }

    if(isset($_GET['activeTodo'])) {
        $todoId = validate_input($_GET['activeTodo']);
        if($todoId !== "") {
            editStatus($todoId, 1);
            sendTo("dashboard.php");
        }
    }

    if(isset($_GET['close'])) {
        $todoId = validate_input($_GET['close']);
        if($todoId !== "") {
            editStatus($todoId, 2);
            sendTo("dashboard.php");
        }
    }

    if(isset($_GET['back'])) {
        $todoId = validate_input($_GET['back']);
        if($todoId !== "") {
            editStatus($todoId, 0);
            sendTo("dashboard.php");
        }
    }
}
    

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        if (isset($_POST['todotitel']) && isset($_POST['selectedUser']) && isset($_POST['description']) && isset($_POST['selectedPriority'])) {
            //todotitel, selectedUser, description
            $todotitel = validate_input($_POST['todotitel']);
            $selectedUser = validate_input($_POST['selectedUser']);
            $description = validate_input($_POST['description']);
            $selectedPriority = validate_input($_POST['selectedPriority']);
            if($todotitel !== "" && $selectedUser !== "" && $description !== "" && $selectedPriority !== "") {
                $selectedUserId = getUserId($selectedUser);
                $creatorUserId = getUserId($_SESSION['username']);

                $priority = 0;
                if($selectedPriority == "Un Wichtig") {
                    $priority = 0;
                }
                if($selectedPriority == "Wichtig") {
                    $priority = 1;
                }
                if($selectedPriority == "Sehr Wichtig") {
                    $priority = 2;
                }

                createToDo($selectedUserId, $creatorUserId, $priority, $todotitel, $description);

            } else {

            }
        } else {

        }
    }
}


?>

<body>

    <div style="width: 100%;">

        <br>
        <h1 class="d-flex justify-content-center">ToDo System Dashboard</h1>
        <br>
        <br>

        <div class="row" style="width: 101%;">

            <div class="col-sm-1 manager" style="background-color: white;">

            </div>
            <div class="col-sm-4 manager" style="background-color: white;">
                <h2 class="d-flex justify-content-center">Erstelle eine neue ToDo</h2>
                <br>
                <br>
                <form method="POST">
                    <div class="form-group">
                        <label for="todotitel">Vergib hier den ToDo Titel</label>
                        <input type="text" class="form-control" id="todotitel" placeholder="Trage hier den titel der ToDo ein" id="todotitel" name="todotitel">
                    </div>
                    <div class="form-group">
                        <label for="selectedPriority">Wähle die dringlichkeit der ToDo</label>
                        <select class="form-control" id="selectedPriority" name="selectedPriority">
                            <option>Sehr Wichtig</option>
                            <option>Wichtig</option>
                            <option>Un Wichtig</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="selectedUser">Wähle den Benutzer dem diese ToDo unsterstellt wird</label>
                        <select class="form-control" id="selectedUser" name="selectedUser">
                            <?php
                                $sql = "SELECT * FROM accounts";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option>" . $row['username'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Trage hier ein Beschreibung zu deiner ToDo ein</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" id="submit" name="submit" class="btn btn-primary">ToDo Erstellen und ausgewähltem Benutzer zustellen</button>
                </form>
            </div>
            <div class="col-sm-2 manager" style="background-color: white;">

            </div>
            <div class="col-sm-5 manager" style="background-color: white;">
                <h2 class="d-flex justify-content-center">Deine ToDo`s</h2>
                <br>
                <br>
                <hr class="hr-class" style="margin: revert;">
                <h4 class="d-flex justify-content-center">Erstellte Todos (<?= getToDoSCreator(getUserId($_SESSION['username']), 1); ?>)</h4>
                <hr class="hr-class">
                <br>
                <?php
                    $sql = "SELECT * FROM todos WHERE creator_id='" . getUserId($_SESSION['username']) . "'";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card" style="width: 90%; margin-bottom: 10px;">';
                        echo '<div class="card-body">';
                        if($row['priority'] == 0) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-secondary">Un Wichtig</span>';
                        }
                        if($row['priority'] == 1) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-warning">Wichtig</span>';
                        }
                        if($row['priority'] == 2) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-danger">Sehr Wichtig</span>';
                        }

                        if($row['todo_status'] == 0) {
                            echo '<span class="card-title badge badge-secondary" style="margin-left: 60px;">Offen</span>';
                        } 
                        if($row['todo_status'] == 1) {
                            echo '<span class="card-title badge badge-primary" style="margin-left: 60px;">In Bearbeitung</span>';
                        } 
                        if($row['todo_status'] == 2) {
                            echo '<span class="card-title badge badge-success" style="margin-left: 60px;">Erledigt</span>';
                        }    
                        echo '<p class="card-text">' . $row['description'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '<br>';
                    }
                ?>
                <br>
                <hr class="hr-class" style="margin: revert;">
                <h4 class="d-flex justify-content-center">In Bearbeitung (<?= getToDoS(getUserId($_SESSION['username']), 1); ?>)</h4>
                <hr class="hr-class">
                <br>
                <?php
                    $sql = "SELECT * FROM todos WHERE user_id='" . getUserId($_SESSION['username']) . "' AND todo_status='1'";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card" style="width: 90%; margin-bottom: 10px;">';
                        echo '<div class="card-body">';
                        if($row['priority'] == 0) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-secondary">Un Wichtig</span>';
                        }
                        if($row['priority'] == 1) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-warning">Wichtig</span>';
                        }
                        if($row['priority'] == 2) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-danger">Sehr Wichtig</span>';
                        }
                        echo '<p class="card-text">' . $row['description'] . '</p>';
                        echo '<a href="dashboard.php?close=' . $row['ID'] . '" class="btn btn-outline-success">ToDo abschließen</a>';
                        echo '<a href="dashboard.php?back=' . $row['ID'] . '" class="btn btn-outline-danger" style="margin-left: 22px;">ToDo rückstufen</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '<br>';
                    }
                ?>
                <hr class="hr-class" style="margin: revert;">
                <h4 class="d-flex justify-content-center">Offen (<?= getToDoS(getUserId($_SESSION['username']), 0); ?>)</h4>
                <hr class="hr-class">
                <br>
                <?php
                    $sql = "SELECT * FROM todos WHERE user_id='" . getUserId($_SESSION['username']) . "' AND NOT todo_status='1'  AND NOT todo_status='2'";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card" style="width: 90%; margin-bottom: 10px;">';
                        echo '<div class="card-body">';
                        if($row['priority'] == 0) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-secondary">Un Wichtig</span>';
                        }
                        if($row['priority'] == 1) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-warning">Wichtig</span>';
                        }
                        if($row['priority'] == 2) {
                            echo '<h5 class="card-title">' . $row['title'] . '</h5> <span class="card-title badge badge-danger">Sehr Wichtig</span>';
                        }
                        echo '<p class="card-text">' . $row['description'] . '</p>';
                        echo '<a href="dashboard.php?activeTodo=' . $row['ID'] . '" class="btn btn-outline-success">ToDo in angrifff nehmen</a>';
                        echo '<a href="dashboard.php?delete=' . $row['ID'] . '" class="btn btn-outline-danger" style="margin-left: 22px;">ToDo abweisen</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '<br>';
                    }
                ?>
            </div>
        </div>
    </div>

</body>