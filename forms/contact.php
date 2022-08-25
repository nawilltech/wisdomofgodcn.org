<?php
$from = 'Contact us at wisdomofgodcn.org <info@wisdomofgodcn.org>'; // an email address that will be in the From field of the email.
$sendTo = 'Contact us at wisdomofgodcn.org <info@wisdomofgodcn.org>'; // an email address that will receive the email with the output of the form
$subject = 'New message from contact form'; // subject of the email
$fields = array('name' => 'Name', 'email' => 'Email', 'subject' => 'Subject', 'message' => 'Message'); // form field names and their translations. // array variable name => Text to appear in the email
$okMessage = 'Contact form successfully submitted. We will get back to you soon!'; // message that will be displayed when everything is OK :)
$errorMessage = 'Error while submitting the form. Please try again later'; // If something goes wrong, we will display this message.
error_reporting(E_ALL & ~E_NOTICE);
try
{
    if(count($_POST) == 0) throw new \Exception('Form is empty');           
    $emailText = "New message Wisdomofgodcn form\n=============================\n";
    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email 
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }
    // All the necessary headers for the email.
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $_POST['email'],
        'Return-Path: ' . $from,
    );
    mail($sendTo, $subject, $emailText, implode("\n", $headers)); // Send email
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}
// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    header('Content-Type: application/json');
    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
