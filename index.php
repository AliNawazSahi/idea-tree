<?php
include_once('db/db.php');
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
curl_close($curl);
$response = json_decode($response);
$token = $response->token;
$user_id = $response->id;
// $user_id = 1;//@todo testing
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://smplcards.com/wp-json/smplcards/v1/notebooks?token=' . $token,
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
$response = curl_exec($curl);
$response = json_decode($response);
curl_close($curl);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Turbo Cards</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="nodlify.js"></script>
    <style>
        body {
            height: 100vh;
        }

        #sort-1 {
            width: 20%;
            padding-top: 20px;
            overflow-y: auto;
            max-height: 100vh;
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            left: 0;
            top: 0;
            border-top: dashed 2px black;
            position: sticky;
        }

        #sort-1 div {
            margin: 0 5px 5px 5px;
            font-size: 12px;
        }


        .div-box {
            width: 200px;
            height: 200px;
            user-select: none;
            cursor: all-scroll;
            box-shadow: 3px 3px 10px 2px rgba(0, 0, 0, 0.1);
            padding: 10px 15px;
            color: #1b3e59;
            border-radius: 5px;
            box-shadow: rgba(27, 62, 89, 0.2) 0px 8px 24px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            background-color: #f5f4f5;
        }

        .node-box {
            user-select: none;
            width: 200px;
            height: 200px;
            cursor: all-scroll;
            position: relative;
            font-size: 13px;
            padding: 10px 15px;
            color: #1b3e59;
            border-radius: 5px;
            box-shadow: rgba(27, 62, 89, 0.2) 0px 8px 24px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            background-color: #f5f4f5;
        }

        .droppable-container {
            position: absolute;
            top: 130%;
            display: flex;
            cursor: default;
        }

        .bs_btn_cross {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 1500;
            width: 15px;
            height: 15px;
            font-size: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-width: 1px;
            background-color: red;
            padding: 2px;
            color: white;
            border-radius: 3px;
            font-weight: bold;
            box-shadow: 1px 1px 3px 1px rgba(0, 0, 0, 0.15);
            cursor: pointer;
        }

        #shuffleBtn {
            background-color: rgba(0, 113, 255, 255);
            transition: rgb(48 87 136) 0.3s ease;

        }

        #shuffleBtn:active {
            background-color: rgb(48 87 136);
        }

        .div-dashed {
            border: 2px dashed black;
        }

        .nodelifyNodes {
            border: 2px dashed black;
            padding: 20px;
            height: 100%;
            display: flex;
            margin: 0 auto;
            flex-direction: row;
            justify-content: center;
        }

        #sort-2 {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 200px;
            float: left;
        }

        #sort-2 div {
            margin: 0 5px 5px 5px;
            padding: 0.4em;
            padding-left: 1.5em;
            font-size: 16px;
            height: 20px;
        }

        #nodelifyCreator {
            height: 100vh;
        }

        .three-dots {
            cursor: default;
            font-size: 25px;
            text-align: center;
            cursor: pointer;
        }

        #sort-1 div.note-title,
        .note-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        #nodelifyRoot {
            display: flex;
            justify-content: space-between;
        }

        #nodelifyCreator {
            width: 85%;
        }

        .modal-footer {
            padding: 15px !important;
        }

        .modal-content {
            border: none;
        }

        #sort-1 {
            width: 20%;
            padding-top: 20px;
        }

        .bs-modal {
            display: none;
            width: 100%;
            max-width: 900px;
            z-index: 2500;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            background-color: white;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px;
        }

        .bs-overlay {
            display: none;
            background: black;
            color: black;
            height: 100%;
            width: 100%;
            opacity: 0.6;
            z-index: 2499;
            position: absolute;
        }

        .bs-icon {
            text-align: center;
            padding: 30px 0;
            background-color: #f5f5f5;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
        }

        .bs-icon img {
            width: 100px;
        }

        .bs-modal-body {
            padding: 20px 40px;
            font-size: 18px;
        }

        .bs-question {
            font-weight: bold;
            font-size: 20px;
        }

        .ic-resize-svg {
            position: absolute;
            bottom: 3px;
            right: 3px;
            width: 12px;
            height: auto;
            cursor: nw-resize;
        }

        .bs-form-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
            gap: 10px
        }

        .bs-form-wrap select {
            width: 40%;
            height: 40px !important;
        }

        .bs-form-wrap label {
            font-size: 20px !important;
        }

        .bs-form-wrap button {
            width: 10%;
            height: 40px;
            background-color: rgba(0, 113, 255, 255);
            font-size: 15px;
            font-weight: bold;
        }

        .bs-form-wrap input {
            border: 2px solid rgba(0, 113, 255, 255);
        }

        .note-title-wrap {
            width: 40%;
            height: 40px !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* display: none; */
        }

        .note-title-wrap label {
            margin-right: 5px;
        }

        .note-title-wrap input,
        .note-title-wrap select {
            height: 100%;
            width: 80%;
            margin-right: 5px;
        }

        .note-title-wrap input {
            display: none;
        }

        .note-title-wrap button {
            width: 10%;
            height: 100%;
        }

        .btn-cross-add-tree {
            display: none;
        }

        .btn-cross-add-tree {
            background-color: #e74c3c !important;
        }
    </style>
</head>

<body>
    <div class="bs-overlay" id="bsOverlay"></div>
    <!-- Modal to display note and note details -->
    <div class="bs-modal" id="myCustomModal">
        <div class="bs-icon">
            <img src="img/policy.png" alt="">
        </div>
        <div class="bs-modal-body">
            <p class="bs-question" id="bsQuestion">
            <p>
            <p id="bsAnswer"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="_closeDialog('myCustomModal')"
                data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- Notification Dialog -->
    <div class="bs-modal" id="myLoadingModal">
        <div class="bs-icon">
            <img id="loadingDialogImg" src="img/stopwatch.png" alt="">
        </div>
        <div class="bs-modal-body">
            <p class="bs-question text-center" id="bsLoadingBarTitle">Loading Please Wait
            <p>
            <p id="bsLoadingDescription" class="text-center"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="_closeDialog('myLoadingModal')"
                data-dismiss="modal">Close</button>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group bs-form-wrap">
                    <label for="bsNotes">Books</label>
                    <select id="bsNotes" class="form-control" onchange="_getNotes('bsNotes',0)">
                        <option value="" style="display: none;">Select Your Book</option>
                        <?php
                        foreach ($response->books as $book) {
                        ?>
                        <option value="<?php echo $book->node_id;
                                            ?>">
                            <?php echo $book->node_name;
                                                    ?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                    <div class="note-title-wrap" id="noteTitleWrap">
                        <label for="">Trees</label>
                        <input type="text" class="form-control" id="tvTreeTitle" placeholder="Enter Tree Title...">
                        <?php
                        $q = "SELECT * FROM  tbl_nodlify_nodes WHERE user_id='$user_id'";
                        $r = mysqli_query($conn, $q);
                        ?>
                        <select name="" class="form-control" id="treeSelect" onchange="_getNotes('treeSelect', 1)">
                            <option value="0">Select Tree</option>
                            <?php
                            if (mysqli_num_rows($r)) {
                                while ($ro = mysqli_fetch_array($r)) {
                            ?>
                            <option value="<?php echo $ro['id']; ?>">
                                <?php echo $ro['tree_name']; ?>
                            </option>
                            <?php }
                            } else { ?>
                            <option value="0">No Previous Tree Found</option>
                            <?php } ?>
                        </select>

                        <button id="btnTitleCross" type="button" onclick="addTree(this)"
                            class="btn-title-cross btn btn-success">+</button>
                        <button id="btnCloseAddTreeOption" type="button" onclick="_closeAddTreeOption()"
                            class="btn-cross-add-tree btn btn-danger">x</button>
                    </div>
                    <script>
                        var treeSelected = null;
                        var treeSelect = document.getElementById('treeSelect');
                        var tvTreeTitle = document.getElementById('tvTreeTitle');
                        var btnCloseAddTreeOption = document.getElementById('btnCloseAddTreeOption');
                        var btnTitleCross = document.getElementById('btnTitleCross');

                        function addTree(elem) {
                            // hide the elements that we should hide
                            treeSelect.style.display = "none";
                            elem.style.display = "none";
                            newTree = true;
                            btnCloseAddTreeOption.style.display = "block";
                            tvTreeTitle.style.display = "block";
                        }

                        function _closeAddTreeOption() {
                            newTree = false;
                            tvTreeTitle.style.display = "none";
                            document.getElementById('btnCloseAddTreeOption').style.display = "none";
                            btnTitleCross.style.display = "block";
                            treeSelect.style.display = "block";
                        }
                    </script>
                    <button type="button" id="shuffleBtn" class="btn btn-primary" onClick="shuffle()">Shuffle</button>
                    <button type="button" class="btn btn-primary" onClick="saveNodlify()">Save Tree</button>
                    <button type="button" class="btn btn-primary" onClick="exportTreePDF()">Export PDF</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="nodelifyRoot" class="" style="height: 100%">
                    <div id="sort-1" class="nodelifyList">
                    </div>
                    <div id="nodelifyCreator" class="nodelifyNodes droppable">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        /**
         * UserId that came from API
         */
        var userId = <? php echo $user_id; ?>;
        var n = new Nodelify(document.getElementById('nodelifyRoot'));
        n.initialize();
        var currentBookId = null;
        var nodlifyCreator = document.getElementById('nodelifyCreator');
        var sort1 = document.getElementById('sort-1')
    </script>
    <script>
        var tvTreeTitle = document.getElementById('tvTreeTitle');
        var newTree = false;

        function getSelectedValue(selectId) {
            let select = document.getElementById(selectId);
            let selectedOption = select.options[select.selectedIndex];
            if (selectedOption)
                return selectedOption.getAttribute('value');
            return 0;
        }

        function exportTreePDF() {
            const element = document.querySelector('#nodelifyCreator');
            // console.log('window.innerWidth', window.innerWidth);
            // var _w = window.innerWidth > 1920 ? window.innerWidth : 1920; //replace with width of tree
            // var _h = window.innerHeight > 1080 ? window.innerWidth : 1080; //replace with height of tree

            // var _w = 800;
            // var _h = 520;

            var elStyle = element.style.borderWidth;
            element.style.borderWidth = 0; //remove border
            const options = {
                filename: 'tree.pdf',
                margin: 0,
                image: {
                    type: 'jpeg',
                    quality: 0.9
                },
                html2canvas: {
                    scale: 2,
                    // x: 0,
                    // y: 0,
                    // width: _w * 1.05, //canvas width
                    // height: _h * 1.05,
                    // windowWidth: _w,
                    // windowHeight: _h,
                },
                jsPDF: {
                    unit: 'px',
                    format: 'a4',
                    orientation: 'l',
                    hotfixes: ["px_scaling"]
                },
            };
            html2pdf().set(options).from(element).save().then(function () {
                element.style.borderWidth = elStyle;
            });
        }

        function shuffle() {

            // Get all the note-title, note-content, and edit-img elements
            const noteTitle = document.querySelectorAll('#nodelifyCreator .note-title');
            const noteContent = document.querySelectorAll('#nodelifyCreator .note-content');
            const noteEditImg = document.querySelectorAll('#nodelifyCreator img[id="viewMore"]');

            const groups = [];
            for (let i = 0; i < noteTitle.length; i++) {
                groups.push({
                    title: noteTitle[i].innerHTML,
                    content: noteContent[i].innerHTML,
                    imgSrc: noteEditImg[i].src,
                    imgDataId: noteEditImg[i].getAttribute('data-id')
                });
            }

            const numNotes = groups.length;
            if (numNotes === 0) {
                alert("Please add notes before shuffling.");
            } else if (numNotes === 1) {
                alert("Please add more than one note before shuffling.");
            } else {
                let previousTitlePositions = Array.from(noteTitle).map(title => title.innerHTML);
                let currentTitlePositions;
                let shuffleCount = 0;
                const maxShuffleAttempts = 4;

                do {
                    // Shuffle the groups array
                    for (let i = groups.length - 1; i > 0; i--) {
                        const j = Math.floor(Math.random() * (i + 1));
                        const temp = groups[i];
                        groups[i] = groups[j];
                        groups[j] = temp;
                    }

                    // Update the HTML with the shuffled groups
                    for (let i = 0; i < groups.length; i++) {
                        noteTitle[i].innerHTML = groups[i].title;
                        noteContent[i].innerHTML = groups[i].content;
                        noteEditImg[i].src = groups[i].imgSrc;
                        noteEditImg[i].setAttribute('data-id', groups[i].imgDataId);

                        // Extract the parent element of the img
                        const parentElement = noteEditImg[i].closest('.node-box');

                        // Update data-note-id and id attributes of the parent element
                        parentElement.setAttribute('data-note-id', groups[i].imgDataId);
                        parentElement.setAttribute('id', groups[i].imgDataId);
                    }

                    // Update current title positions after shuffling
                    currentTitlePositions = Array.from(noteTitle).map(title => title.innerHTML);

                    shuffleCount++;
                } while (arraysAreEqual(previousTitlePositions, currentTitlePositions) && shuffleCount <
                maxShuffleAttempts);
            }
        }

        // Helper function to check if two arrays are equal
        function arraysAreEqual(array1, array2) {
            return array1.every((value, index) => value === array2[index]);
        }


        function saveNodlify() {
            let noteTitleWrap = document.getElementById('noteTitleWrap');
            let bsNotes = document.getElementById('bsNotes');
            let id = getSelectedValue('treeSelect');
            let treeTitle = "";
            if (newTree == true) {
                if (tvTreeTitle != "") {
                    treeTitle = tvTreeTitle.value;
                } else {
                    alert("Please provide a tree title");
                    return;
                }
            } else {
                if (Number(id) == 0) {
                    alert("Please select a note ");
                    return;
                }
            }
            // noteTitleWrap.style.display = "flex";
            // bsNotes.style.display = "none";


            let notes = nodlifyCreator.querySelectorAll('[data-note-id]');
            let nodlifyNotes = [];
            notes.forEach(e => {
                nodlifyNotes.push(e.getAttribute('data-note-id'));
            });

            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();
            // Define the URL to which you want to send the POST request
            var url = 'save-nodlify.php';
            // Define the data you want to send (in this case, a JSON object)
            var data = {
                content: nodlifyCreator.innerHTML,
                nodlifyNotes: nodlifyNotes,
                id: id,
                treeTitle: treeTitle,
                userId: userId,
                newTree: newTree,
            };
            // Convert the data object to a JSON string
            var jsonData = JSON.stringify(data);
            // Configure the request
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/json'); // Set the content type to JSON
            // Set up a callback function to handle the response
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    // console.log(xhr.responseText);
                    const response = JSON.parse(xhr
                        .responseText); // Loop through the flashcards array and create HTML elements

                    if (response.status == 200) {

                        _showModal('Tree hierarchy saved.',
                            'Your tree hierarchy has been successfully saved. Changes to the structure of your tree have been updated',
                            'saved.png');
                        if (newTree == true) {
                            let html = `<option value="${response.newTreeId}">${treeTitle}</option>`;
                            treeSelect.insertAdjacentHTML('beforeend', html);
                            treeSelect.value = response.newTreeId;
                            tvTreeTitle.value = "";
                            _closeAddTreeOption();
                        }

                    } else {
                        // Request failed, handle the error
                        console.error('Request failed with status:', xhr.status);
                    }
                }
            };
            // Send the POST request with the JSON data
            xhr.send(jsonData);
        }


        var bsNotes = {};

        function truncateString(text, maxLength) {
            let obj = {};
            if (text.length <= maxLength) {
                obj.status = false;
                obj.text = text;
                return obj;
            } else {
                obj.status = true;
                obj.text = text.substring(0, maxLength);
                return obj;
            }
        }
        // Function to send the HTTP request and handle the response
        function _getNotes(selectedOption, item) {
            let id = getSelectedValue(selectedOption);
            /**
             * if item 0 get book notes only else get tree only
             */
            // Make an XMLHttpRequest or use Fetch API to send the request
            let modalTitle = "";
            if (item == 1)
                modalTitle = "Loading the tree";
            else
                modalTitle = "Loading the book notes";

            _showModal(modalTitle, 'We are diligently working to load your tree. Please hang tight for a moment.');
            const xhr = new XMLHttpRequest();
            // currentBookId = selectedOption.value;
            // let id = selectedOption.value;
            xhr.open("GET", "get-notes.php?selectedOption=" + encodeURIComponent(id) + "&item=" + item, true);
            xhr.onreadystatechange = function () {
                _closeDialog('myLoadingModal');
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // console.log(xhr.responseText); // Print the response in the console
                    const jsonResponse = JSON.parse(xhr
                        .responseText); // Loop through the flashcards array and create HTML elements
                    if (item == 1) {
                        // sort1.innerHTML = "";
                        nodlifyCreator.innerHTML = "";
                        nodlifyCreator.innerHTML = jsonResponse.content;
                    }

                    nodlifyCreator.querySelectorAll('.droppable').forEach(e => {
                        n.registerEventsOnElements(e);
                    });

                    if (item == 0) {
                        if (jsonResponse.response.flashcards.length == 0)
                            _showModal('No Book Content',
                                'Please note that there might not be any associated notes for the chosen book.',
                                'page-not-found.png');
                    }
                    jsonResponse.response.flashcards.forEach(flashcard => {
                        /**
                         * Create object for store data
                         */
                        var qs = flashcard.question;
                        var ans = flashcard.correct_answer;
                        var m_key = flashcard.node_id;
                        // var nestedObject = {
                        //     qs: ans
                        // }
                        // bsNotes.m_key = nestedObject;
                        // let newKey = 123;
                        bsNotes[m_key.toString()] = {
                            question: qs,
                            answer: ans
                        };
                        /**
                         * End Creating object 
                         */

                        if (jsonResponse.notes_used.indexOf(flashcard.node_id) == -1) {
                            const divElement = document.createElement("div");
                            divElement.className = "div-box moveable";
                            divElement.setAttribute('data-note-id', flashcard.node_id);
                            divElement.id = flashcard.node_id;
                            const svgResizeImage = document.createElement("img");
                            svgResizeImage.id = "resizeElement";
                            svgResizeImage.src =
                                "img/ic_resize.svg"; // Replace with the actual path to your SVG image
                            svgResizeImage.setAttribute('draggable', false);
                            svgResizeImage.alt = "SVG Image";
                            svgResizeImage.className = "ic-resize-svg";
                            let truncatedAnswer = truncateString(flashcard.correct_answer, 140);
                            let _content = null;
                            if (truncatedAnswer.status) {
                                // _content = '<div class="note-title">' + flashcard.question + '</div> <div class="note-content">' + truncatedAnswer.text + ' </div> <div class="three-dots" data-id="' + flashcard.node_id + '">...</div>';
                                _content = '<div class="note-title">' + flashcard.question +
                                    '</div> <div class="note-content">' + truncatedAnswer.text +
                                    ' </div> <img id="viewMore" width="15px" style="margin-top: 7px;" src="img/new-window.png" class="three-dots" data-id="' +
                                    flashcard.node_id + '"/>';
                            } else {
                                _content = '<div class="note-title">' + flashcard.question +
                                    '</div> <div class="note-content">' + truncatedAnswer.text + '</div>';
                            }
                            divElement.innerHTML = _content;
                            divElement.appendChild(svgResizeImage);
                            // empty the previous notes
                            sort1.appendChild(divElement);
                            n.initialize();
                        }
                    });
                    // console.log(bsNotes);
                    /**
                     * Add event listner on three dots
                     */
                    // Get all elements with the class "three-dots"
                    var threeDotsElements = document.querySelectorAll('.three-dots');
                    // Loop through each element and add a click event listener
                    threeDotsElements.forEach(function (element) {
                        element.addEventListener('click', function (e) {
                            e.stopPropagation();
                            // Your click event code here
                            var dataId = element.getAttribute('data-id');
                            // alert('The three dots were clicked!');
                            // console.log("you clicked on " + dataId);
                            // You can replace the alert with any action you want to perform on the click
                            var modal = document.getElementById('myCustomModal');
                            var bsOverlay = document.getElementById('bsOverlay');
                            modal.style.display = "block";
                            bsOverlay.style.display = "block";
                            dataId = dataId.toString();
                            // Check if the ID exists in the data
                            if (bsNotes.hasOwnProperty(dataId)) {
                                let question = bsNotes[dataId].question;
                                let answer = bsNotes[dataId].answer;
                                // Insert question and answer into the elements with corresponding IDs
                                document.getElementById('bsQuestion').textContent = question;
                                document.getElementById('bsAnswer').textContent = answer;
                            } else {
                                console.log("ID not found in the data.");
                            }
                            // function _closeDialog() {
                            //     var modal = document.getElementById('myCustomModal');
                            //     var bsOverlay = document.getElementById('bsOverlay');
                            //     modal.style.display = "none";
                            //     bsOverlay.style.display = "none";
                            // }
                        });
                    });
                }
            };
            xhr.send();
        }

        function _showModal(title, msg, img = null) {
            let dialogImg = document.getElementById('loadingDialogImg');
            dialogImg.src = 'img/stopwatch.png';
            let modal = document.getElementById('myLoadingModal');
            let overlay = document.getElementById('bsOverlay');
            let bsTitle = document.getElementById('bsLoadingBarTitle');
            let bsMsg = document.getElementById('bsLoadingDescription');
            modal.style.display = "block";
            overlay.style.display = "block";
            bsTitle.innerHTML = title;
            bsMsg.innerHTML = msg;
            if (img != null)
                dialogImg.src = 'img/' + img;
        }

        function _closeDialog(modalId) {
            var modal = document.getElementById(modalId);
            var bsOverlay = document.getElementById('bsOverlay');
            modal.style.display = "none";
            bsOverlay.style.display = "none";
        }
    </script>
</body>

</html>