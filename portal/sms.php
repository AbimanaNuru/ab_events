<?php
function sendSMS() {
    $data = array(
        "sender" => 'Abelia Ltd',
        "recipients" => "0789424105",
        "message" => "Here We are AB EVENTS",
        "dlrurl" => ""
    );

    $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
    $data = http_build_query ($data);
    $username = "abelia.ltd";
    $password = "abelia.ltd";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo $result;
    echo $httpcode;
}

if (isset($_POST['send_sms'])) {
    sendSMS();
}
?>

<!-- HTML code with a button to call the sendSMS function when clicked -->
<form method="post">
    <button type="submit" name="send_sms">Send SMS</button>
</form>
