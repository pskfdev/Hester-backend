<?php

class Response {

    public function success($result = [], $message = 'Success') {
        $response = array(
            'status' => true,
            'response' => $result,
            'message' => $message
        );

        echo json_encode($response);
    }

    public function error($message = 'Error') {
        $response = array(
            'status' => false,
            'message' => $message
        );

        echo json_encode($response);
    }
}

?>