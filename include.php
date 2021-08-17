<?php

error_reporting(-1);
ini_set('display_errors', 'On');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("jsdata.php");
include("database.php");

function createToDo($userid, $creatorid, $priority, $title, $description)
{
    include("database.php");
    $sql = "INSERT INTO todos(user_id, creator_id, priority, title, description) VALUES ('" . $userid . "', '" . $creatorid . "', '" . $priority . "', '" . $title . "', '" . $description . "')";
    $conn->query($sql);
}

function getUserId($usernameToID): int
{
    include("database.php");
    $sql = "SELECT * FROM accounts WHERE username='" . $usernameToID . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        return $row["ID"];
    }
    return 0;
}

function deleteToDo($todoId)
{
    include('database.php');
    $sql = "DELETE FROM todos WHERE ID='" . $todoId . "'";
    $conn->query($sql);
}

function editStatus($todoId, $newstatus) {
    include('database.php');
    $sql = "UPDATE todos SET todo_status='" . $newstatus . "' WHERE ID='" . $todoId . "'";
    $conn->query($sql);
}

function getToDoS($userId, $status): int {
    include('database.php');
    $sql = "SELECT COUNT(ID) FROM todos WHERE todo_status='" . $status . "' AND user_id='" . $userId . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        return $row['COUNT(ID)'];
    }
    return 0;
}

function getToDoSCreator($creatorId): int {
    include('database.php');
    $sql = "SELECT COUNT(ID) FROM todos WHERE creator_id='" . $creatorId . "'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        return $row['COUNT(ID)'];
    }
    return 0;
}

function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendTo($actualurl) {
    ?>
    <script>
        window.location = "<?php echo $actualurl; ?>";
    </script>
    <?php
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo System</title>
    <style>
        .manager {
            padding-bottom: 15px;
        }
        .hr-class {
            border-top: 4px solid black;
            height: 14px;
            width: 37%;
            color: black;
            font-size: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">ToDo System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
                </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-danger my-2 my-sm-0" href="logout.php" type="submit">Logout</a>
                </form>
            </div>
        </nav>
        <br>
        <div class="toast-section" id="toast-section" aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">
            <div class="toast" data-delay="5000" style="position: absolute; top: 0; right: 0; margin-right: 40px;">
                <div class="toast-header">
                    <strong class="mr-auto">ToDo System</strong>
                    <small>gerade eben</small>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    Du hast eine neue ToDo bekommen
                </div>
            </div>
        </div>
    </header>

    <script>
        //$('.toast').toast('show');

        $(document).ready(function() {

            //Show Element
            //document.getElementById('toast-section').style.minHeight = 200;

            //Hide Element
            document.getElementById('toast-section').style.minHeight = 0;

        });
    </script>

</body>

</html>
<?php
include("jsdata.php");
?>