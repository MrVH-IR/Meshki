<?php

$blockedIpFile = '../admin/blocked_ips.txt';

$userIp = $_SERVER['REMOTE_ADDR'];

if (file_exists($blockedIpFile)) {
    $blockedIps = file($blockedIpFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($userIp, $blockedIps)) {
        die('You are not allowed to access this site. Your IP is blocked.');
    }
}
?>
