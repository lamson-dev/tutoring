<?php

/* Define constants */
define('DEBUG', TRUE);

$listSchoolQuery = "SELECT School FROM COURSE ORDER BY School;";
$listCourseNumberQuery = "SELECT School,Number FROM COURSE WHERE School==schoolInput ORDER BY Number;";

/* This is a generic function for SharePlan REST API requests */
function makeApiCall($headers, $params)
{

    // create curl resource
    $ch = curl_init();

    // attach the custom header passed in from a API function
    curl_setopt_array($ch, $headers);

    /* Attach params if set */
    if (isset($params)) {
        // attach params
    }

    // $output contains the response string
    $output = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // output the response as json
    header("Content-type: application/json");

    // close curl resource to free up system resources
    curl_close($ch);

    return array($output, $http_status);
}

/* This function handles the response retrieved in makeApiCall function */
function handleResponse($response)
{
    // $responses is an array ($output, $http_status)
    // returned from makeApiCall function
    $output = $response[0];
    $http_status = $response[1];

    // if 200, echo the result
    // if 401, set header and echo the error message
    // if 404, set header and echo the error message

    /* Handle the result */
    if ($http_status == 200) {
        return $output;
    } else {
        // handle the error status
        // setting header notifies the front end of the error type
        if ($http_status == 401) {
            header("HTTP/1.0 401 Unauthorized");
            die("AuthToken not valid. Please log in.");
        } else {
            header("HTTP/1.0 404 Not Found");
            die("The page that you have requested could not be found.");
        }
    }
}

/* create header array with auth token retrieved and content-type */
function createHttpHeader()
{
    if (isset($_SESSION['token'])) {
        $httpHeader = array(
            'Content-Type: application/json',
            'Authorization: TOKEN ' . $_SESSION['token']
        );

        return $httpHeader;
    } else {
        header("HTTP/1.0 401 Unauthorized");
        die("AuthToken not valid. Please log in.");
    }
}

/* Ping to SharePlan server */
function ping()
{
    global $defaultHeader;

    // customize the default header
    $header = $defaultHeader;
    $header[CURLOPT_URL] = BASE_URL . '/Ping';
    $header[CURLOPT_GET] = TRUE;
    // since ping does not require an auth token, just specify
    // content type, but for other calls, use createHttpHeader()
    $header[CURLOPT_HTTPHEADER] = "Content-Type: application/json";

    // make the API call with the custom header
    $result = makeApiCall($header, null);

    echo handleResponse($result);
}


/* using user input username and password, obtain AuthToken */
function getAuthToken($username, $password)
{
    global $defaultHeader;

    // decode base64 encoded strings
    $decodedUser = base64_decode($username);
    $decodedPass = base64_decode($password);

    // Don't use default header because we need CURLOPT_USERPWD

    // Default header doesn't contain USERPWD because that will
    // affect other request calls using Auth Token
    $header = array(
        // In addition to these, each API call will have to specify
        // URL, POST/GET/PUT, POSTFIELDS, HTTPHEADER ...
        CURLOPT_URL => BASE_URL . "/AuthToken",
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_VERBOSE => TRUE,
        CURLOPT_USERPWD => $decodedUser . ':' . $decodedPass,
        CURLOPT_POST => TRUE,
        CURLOPT_HTTPHEADER => "Content-Type: application/json",
    );

    $result = makeApiCall($header, null);

    $output = $result[0]; // JSON response
    $status = $result[1]; // http status result

    /* Handle the result */
    // check the response status
    if ($status == 200) {
        echo $output;
        $parsed = json_decode($output);

        if (isset($parsed)) {
            // save the appended token data into a session variable
            $_SESSION['token'] = $parsed->data[0] . '-' . $parsed->data[1];
            // echo " ### TOKEN SAVED IN SESSION. ### " . $_SESSION['token'] . " ### ";
        }
    } else {
        echo 'Error: ';
        // TODO: $ch is undefined here
//        echo 'Error: ' . curl_error($ch);
    }
}

/* log out called from front-end widget, unset the session variable */
function logout()
{
    // unset the token PHP session variable
    if (isset($_SESSION['token'])) {
        unset($_SESSION['token']);
        echo("Logout successful");
    }
}

/* get my user info */
function getMyUserInfo()
{
    global $defaultHeader;

    // customize the default header
    $header = $defaultHeader;
    $header[CURLOPT_URL] = BASE_URL . '/User/my';
    $header[CURLOPT_GET] = TRUE;
    $header[CURLOPT_HTTPHEADER] = createHttpHeader();

    // make the API call with the custom header
    $response = makeApiCall($header, null);

    echo handleResponse($response);
}

/* get list of plans under my account */
function listPlans()
{
    global $defaultHeader;

    // customize the default header
    $header = $defaultHeader;
    $header[CURLOPT_URL] = BASE_URL . '/Plan';
    $header[CURLOPT_GET] = TRUE;
    $header[CURLOPT_HTTPHEADER] = createHttpHeader();

    // make the API call with the custom header
    $response = makeApiCall($header, null);

    echo handleResponse($response);
}

// TODO: comment
function getPlanFiles($planId, $path)
{
    global $defaultHeader;

    // customize the default header
    $header = $defaultHeader;
    $header[CURLOPT_URL] = BASE_URL . '/FileInfo/' . $planId . '/' . $path . '/?incChildren=true';
    $header[CURLOPT_GET] = TRUE;
    $header[CURLOPT_HTTPHEADER] = createHttpHeader();

    // make the API call with the custom header
    $response = makeApiCall($header, null);

    echo handleResponse($response);
}

// TODO: comment
function getFile($planId, $path)
{
    global $defaultHeader;

    // customize the default header
    $header = $defaultHeader;
    $header[CURLOPT_URL] = BASE_URL . '/file/' . $planId . '/' . $path;
    $header[CURLOPT_GET] = TRUE;
    $header[CURLOPT_HTTPHEADER] = createHttpHeader();

    // make the API call with the custom header
    $response = makeApiCall($header, null);

    // set the output file type according to its extension
    set_header($path);
    echo handleResponse($response);
}

// TODO: comment
function set_header($path)
{

    $filename = end(explode("/", $path));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_mime_type = finfo_file($finfo, $filename);

    if (($pos = strpos($filename, ".")) !== FALSE) {
        $file_extension = substr($filename, $pos + 1);
    }

    if ($file_extension == "pdf") {
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));
        header('Accept-Ranges: bytes');
    } else {
        header('Content-type: "' . $file_mime_type . '"');
    }

}

function uploadFile($planUid, $path, $file, $filename)
{
    // echo "reached php -- $planUid, $path, $filename";
    /*
        path -- path from root of the plan i.e. if the plan has folders, path should specify which folder to upload
        filename -- this is the path to the file on the local machine
    */
    // create curl resource
    $ch = curl_init();


    //curl -vv --form-string planUid=628326917144944237 --form-string path="" -F "file=@test.txt;type=file/text" -u 'jk:a1234' http://shareplan.cip.gatech.edu:4280/api/file

    // $cmd = 'curl -vv --form-string planUid=628326917144944237 --form-string path="" -F "file=@/home/ranjan/test_str.txt;type=file/text" -u "jk:a1234" http://shareplan.cip.gatech.edu:4280/api/file';
    // exec($cmd);
    //curl_setopt($ch, CURLOPT_URL, $cmd);
    curl_setopt($ch, CURLOPT_URL, APIENDPOINT . '/file/');


    // echo "at the curl file create";
    // Create a CURLFile object
    //$cfile = curl_file_create($filename,'image/jpeg','test_name.jpg');

    //echo "done creating";

    // Assign POST data
    $filepath = '@' . realpath($file) . '/' . $filename . ';type=file/text';

    echo "File path: <$filepath>";
    // die($filepath);
    $data = array(
        'planUid' => $planUid,
        'path' => $path,
        'file' => $filepath,
        //;type=file/text'
        //'type' => 'file/text'
    );
    /* $cmd='curl -vv --form-string planUid=628326917144944237 --form-string path="" -F "file=@/home/ranjan/test_upload.txt;type=file/text" -u  "jk:a1234" http://shareplan.cip.gatech.edu:4280/api/file';
    exec($cmd,$result);*/

    curl_setopt($ch, CURLOPT_POST, TRUE);
    // echo "executing curl command -- data creation 1\n";
    curl_setopt($ch, CURLOPT_POSTFIELDS, @$data);

    //echo "executing curl command -- data creation 2\n ";
    // return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERPWD, "jk:a1234");
    curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: multipart/form-data");
    // curl_setopt($ch, CURLOPT_HTTPHEADER, createHeader());

    $output = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo " --executing curl command $http_status";

    if ($http_status == 200) {
        echo "its success";
        echo $output;
    } else {
        echo 'error:' . curl_error($ch);
    }

    // close curl resource to free up system resources
    curl_close($ch);

}


?>