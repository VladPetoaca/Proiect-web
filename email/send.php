<?php
include('config.php');

// Get event ID from GET parameter
$eventId = $_GET['id'];
$email = "proiectZVA@gmail.com";
$headers = "From: $email\n";

// Retrieve event details from evenimente table
$eventQuery = "SELECT * FROM evenimente WHERE ID = $eventId";
$eventResult = $mysqli->query($eventQuery);

$event = $eventResult->fetch_assoc();

// Extract event details and format them
$eventName = html_entity_decode($event['Titlu']);
$eventDate = date('d.m.Y', strtotime($event['Data']));
$eventTime = date('H:i', strtotime($event['Ora']));
$eventLocation = $event['Locatia'];
$eventDescription = html_entity_decode($event['Descriere']);

// Retrieve email addresses for sponsors associated with the event
$sponsoriEmails = [];
$sponsoriQuery = "SELECT Email FROM sponsori WHERE id IN (SELECT sponsori_id FROM eveniment_sponsori WHERE eveniment_id = $eventId)";
$sponsoriResult = $mysqli->query($sponsoriQuery);

if ($sponsoriResult->num_rows > 0) {
    while ($row = $sponsoriResult->fetch_assoc()) {
        $sponsoriEmails[] = trim($row['Email']);
    }
}

// Retrieve email addresses for partners associated with the event
$parteneriEmails = [];
$parteneriQuery = "SELECT Email FROM parteneri WHERE id IN (SELECT parteneri_id FROM eveniment_parteneri WHERE eveniment_id = $eventId)";
$parteneriResult = $mysqli->query($parteneriQuery);

if ($parteneriResult->num_rows > 0) {
    while ($row = $parteneriResult->fetch_assoc()) {
        $parteneriEmails[] = trim($row['Email']);
    }
}

// Prepare email template with event-specific details
$subject = "Invitație la evenimentul $eventName";
$message = "
Bună ziua!
Ne bucurăm să vă invităm la evenimentul $eventName, care va avea loc pe data de $eventDate la ora $eventTime la $eventLocation.

$eventDescription

Sperăm să ne onorați cu prezența!
Echipa Evenimente.ro";

// Send emails to sponsors
foreach ($sponsoriEmails as $email) {
    if(mail($email, $subject, $message, $headers)){
        echo "Mail spre $email trimis cu succes!\n";
    } else {
        echo "Mail spre $email nu a putut fi trimis\n";
    }
}

// Send emails to partners
foreach ($parteneriEmails as $email) {
    if(mail($email, $subject, $message, $headers)){
        echo "Mail spre $email trimis cu succes!\n";
    } else {
        echo "Mail spre $email nu a putut fi trimis\n";
    }
}
$mysqli->close();
