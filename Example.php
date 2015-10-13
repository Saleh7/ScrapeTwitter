<?php require_once('Class_EDPassword.php');

$EDPassword = new EDPassword();

// Key = Some secret key
$Key = "SalehTestSecurity";

// MyPassword
$Pass = '123123';

// Encrypt Password
$Encrypt = $EDPassword->Encrypt_My_Pass($Pass,$Key);
# Encrypt == B4NSmz/VnFTBydYEVCgj02WP5y8d4RoNiWqjH68qMBQ=

// Decrypt Password
echo $Decrypt = $EDPassword->Decrypt_My_Pass($Encrypt,$Key);

?>
