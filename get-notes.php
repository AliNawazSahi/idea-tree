<?php


include_once('db/db.php');

if (isset($_GET['selectedOption'])) {


    $node_id  = $_GET['selectedOption'];
    $item  = $_GET['item'];


    /**
     * Item 0 means get book notes only
     * Item = 1 = means get the notes tree Only
     */





    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://smplcards.com/wp-json/smplcards/v1/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('username' => 'tester1', 'password' => 'tester123'),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer M2IzMTQyMzgzMmYwNDBkZmFjYjg0YmE2YjIwZmIwYmU4MGVmMWM5MDcyOGI3Njg0OGFiMWY0NDgxMWFmZDcwYzExYWI2NThiYmZlYzkwOTJiOWUxZmRkNGU2Y2E0ZWYzZjJjNGQ1NWYxMjg4NWQ3ZmE5OGUzYmJhZDA4MWI5OGU='
        ),
    ));

    $response = curl_exec($curl);
    $response = json_decode($response);
    $token = $response->token;



    /**
     * After getting token get all the notes
     */


    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://smplcards.com/wp-json/smplcards/v1/notes?token=' . $token . '&node_id=' . $node_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer M2IzMTQyMzgzMmYwNDBkZmFjYjg0YmE2YjIwZmIwYmU4MGVmMWM5MDcyOGI3Njg0OGFiMWY0NDgxMWFmZDcwYzExYWI2NThiYmZlYzkwOTJiOWUxZmRkNGU2Y2E0ZWYzZjJjNGQ1NWYxMjg4NWQ3ZmE5OGUzYmJhZDA4MWI5OGU='
        ),
    ));



    $resp = curl_exec($curl);

    curl_close($curl);





    // check if the current book_id data already saved




    $response = [];



    /**
     * If user requested for Tree
     */


    $response['content'] = '';
    $response['notes_used'] = [];
    $response['tree_title'] = "";

    $query = "SELECT * FROM tbl_nodlify_nodes WHERE id='$node_id'";
    $run = mysqli_query($conn, $query);
    if (mysqli_num_rows($run)) {
        // data found 
        $row = mysqli_fetch_array($run);

        $response['content'] = $row['content'];
        $response['notes_used'] = json_decode($row['notes_used']);
        $response['tree_title'] = $row['tree_name'];
    }




    $response['response'] = json_decode($resp);

    echo json_encode($response);
}
