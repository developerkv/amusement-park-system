<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role']!="staff"){
header("Location: login.html");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Scan Ticket</title>
<link rel="stylesheet" href="css/style.css">

<script src="https://unpkg.com/html5-qrcode"></script>

<style>

    .navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:10px;
background: linear-gradient(45deg,#ff512f,#dd2476);
}

.navbar h3{
color:white;
}

.nav-links a{
color:white;
text-decoration:none;
margin-left:20px;
font-weight:bold;
}

.nav-links a:hover{
color:yellow;
}


body{
font-family: Arial;
text-align:center;
}

#reader{
width:320px;
margin:auto;
margin-top:20px;
display:none;
}

#uploadBox{
margin-top:20px;
display:none;
}

#stopBtn{
display:none;
}

button{
padding:10px 20px;
margin:10px;
cursor:pointer;
}

/* BACK BUTTON */
.back-btn{
    background:#e5e7eb;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    color:#333;
}

.back-btn:hover{
    background:#d1d5db;
}

/* MAIN CARD */
.container{
    width:400px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

/* SCANNER BOX */
#reader{
    width:100%;
    margin-top:15px;
    display:none;
}

/* UPLOAD BOX */
#uploadBox{
    margin-top:20px;
    display:none;
}

/* BUTTON */
button{
    padding:10px;
    width:100%;
    margin-top:10px;
    border:none;
    border-radius:6px;
    background:#22c55e;
    color:white;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#16a34a;
}

/* STOP BUTTON */
#stopBtn{
    display:none;
    background:#ef4444;
}

#stopBtn:hover{
    background:#dc2626;
}

/* FILE INPUT */
input[type="file"]{
    width:100%;
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

/* TITLE */
h2{
    margin-bottom:10px;
}

.info{
    font-size:14px;
    color:#555;
}

</style>
</head>

<body>

<div class="navbar">
<div class="logo"><span class="joy-text">JOY</span></div>
<div class="nav-links">
<a href="walkin_booking.php">New book</a>
<a href="scan_ticket.php">Verify ticket</a>
<a href="staff_logout.php">Logout</a>
</div>
</div>

<br>

<div style="text-align:right; margin-bottom:20px;">
<a href="staff_panel.php" class="back-btn">Back</a>
</div>

<div class="container">

<h2>Verify Ticket</h2>
<p>Scan QR using camera or upload image</p>

<button onclick="startCamera()">Scan Using Camera</button>
<button onclick="showUpload()">Upload QR Image</button>

<div id="reader"></div>

<button id="stopBtn" onclick="stopScanner()">Stop Scanning</button>

<div id="uploadBox">
<input type="file" id="qrImage">
<button onclick="scanImage()">Scan Image</button>
</div>

<!-- RESULT BOX -->
<div id="resultBox"></div>

</div>

<script>

let html5QrCode;

/* CAMERA */
function startCamera(){
document.getElementById("reader").style.display="block";
document.getElementById("uploadBox").style.display="none";
document.getElementById("stopBtn").style.display="block";

html5QrCode = new Html5Qrcode("reader");

Html5Qrcode.getCameras().then(devices=>{
if(devices.length){

html5QrCode.start(
devices[0].id,
{ fps:10, qrbox:250 },
(decodedText)=>{

let id = decodedText.split("=")[1];
verifyTicket(id);

}
);

}
});
}

/* STOP */
function stopScanner(){
if(html5QrCode){
html5QrCode.stop().then(()=>{
document.getElementById("reader").style.display="none";
document.getElementById("stopBtn").style.display="none";
});
}
}

/* SHOW UPLOAD */
function showUpload(){
document.getElementById("uploadBox").style.display="block";
document.getElementById("reader").style.display="none";
document.getElementById("stopBtn").style.display="none";
}

/* IMAGE SCAN */
function scanImage(){

let fileInput=document.getElementById('qrImage').files[0];

if(!fileInput){
alert("Select QR image");
return;
}

let scanner=new Html5Qrcode("reader");

scanner.scanFile(fileInput,true)
.then(decodedText=>{
let id=decodedText.split("=")[1];
verifyTicket(id);
})
.catch(()=>{
alert("QR not detected");
});
}

/* VERIFY */
function verifyTicket(id){

fetch("verify_ticket.php?id="+id)
.then(res=>res.text())
.then(data=>{

let msg="";
let bg="";
let color="#fff";

if(data=="success"){
msg="Entry Allowed ";
bg="green";

}
else if(data=="used"){
msg="Already Used ";
bg="orange";
}
else if(data=="not_today"){
msg="Not Today Ticket ";
bg="red";
}
else{
msg="Invalid Ticket ";
bg="red";
}

/* SHOW RESULT BOX */
let box = document.getElementById("resultBox");
box.style.display="block";
box.style.align="center";
box.style.background=bg;
box.innerHTML=msg;

/* AUTO HIDE AFTER 3 SEC */
setTimeout(()=>{
box.style.display="none";
},3000);

});
}

</script>

</body>
</html>