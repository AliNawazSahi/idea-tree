
<?php
include_once('db/db.php');
// Retrieve the raw POST data

function book_record_exist($id)
{
    global $conn;
    $query = "SELECT id FROM " . TBL_NODLIFY_NODES . " WHERE id='$id'";
    $run = mysqli_query($conn, $query);
    if ($run) {
        if (mysqli_num_rows($run)) {
            return true;
        }
    }
    return false;
}



// function insert_tree($user_id, $tree_title)
// {

//     global $conn;
//     $query = "INSERT INTO `tbl_trees`(`user_id`, `tree_name`) VALUES ('$user_id','$tree_title')";
//     $run = mysqli_query($conn, $query); 
//     if ($run)
//         return mysqli_insert_id($conn);;
//     return 0;
// }


$postData = file_get_contents("php://input");

// Decode the JSON data into a PHP associative array
$data = json_decode($postData, true);

if ($data !== null) {
    // Data was successfully decoded from JSON

    // Now you can access the data using the keys you sent in the JSON object


    $content = $data['content'];
    $nodlifyNotes = json_encode($data['nodlifyNotes']);
    $id = $data['id'];
    $tree_title = $data['treeTitle'];
    $user_id = $data['userId'];
    $newTree = $data['newTree'];

    // $tree_id  =  insert_tree($user_id, $tree_title);

    // create database connection 



    if ($newTree == true) {
        $sql = "INSERT INTO " . TBL_NODLIFY_NODES . " (`content`,`tree_name`, `user_id`, `notes_used`) VALUES ('$content','$tree_title','$user_id','$nodlifyNotes')";
    }else{
        $sql = "UPDATE " . TBL_NODLIFY_NODES . " SET `content`='$content',`user_id`='$user_id',`notes_used`='$nodlifyNotes' WHERE id='$id'";

    }

    // if (!book_record_exist($id)) {

    //     $sql = "INSERT INTO " . TBL_NODLIFY_NODES . " (`content`,`tree_name`, `user_id`, `notes_used`) VALUES ('$content','$tree_title','$user_id','$nodlifyNotes')";
    // } else {
    //     $sql = "UPDATE " . TBL_NODLIFY_NODES . " SET `content`='$content',`user_id`='$user_id',`notes_used`='$nodlifyNotes' WHERE id='$id'";
    // }


    $run = mysqli_query($conn, $sql);

    if ($run) {
        // Return a response if needed
        $response = array(
            'message' => 'Operation successfull',

            'status' => 200
        );



        if ($newTree == true) $response['newTreeId'] = mysqli_insert_id($conn);

    } else {
        $response = array('message' => 'Error', 'status' => 201);
    }
    echo json_encode($response);
    // Perform any further processing with the data
    // ...

} else {
    // Failed to decode JSON or no data was sent
    http_response_code(400); // Bad Request
    echo json_encode(array('error' => 'Invalid data'));
}
?>
