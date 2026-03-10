<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Revisewell AI Tutor Demo</title>

<style>

body{
font-family: Arial;
background:#eef2f7;
padding:40px;
text-align:center;
}

.container{
background:white;
width:600px;
margin:auto;
padding:25px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

textarea{
width:100%;
height:90px;
font-size:16px;
padding:10px;
}

button{
padding:12px 18px;
margin:10px;
font-size:16px;
cursor:pointer;
border:none;
border-radius:5px;
}

.mic{
background:#ff5757;
color:white;
}

.ask{
background:#2a7cff;
color:white;
}

#answer{
margin-top:20px;
padding:15px;
background:#f4f4f4;
border-radius:8px;
text-align:left;
}

</style>
</head>

<body>

<div class="container">

<h2>🎓 Revisewell AI Tutor (Demo)</h2>

<textarea id="question" placeholder="Ask your question..."></textarea>

<br>

<button class="mic" onclick="startSpeech()">🎤 Speak</button>
<button class="ask" onclick="askAI()">Ask AI</button>

<div id="answer">AI answer will appear here...</div>

</div>

<script>

function startSpeech(){

const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();

recognition.lang="en-IN";

recognition.onresult=function(event){

document.getElementById("question").value =
event.results[0][0].transcript;

};

recognition.start();

}

function askAI(){

let q=document.getElementById("question").value;

document.getElementById("answer").innerHTML="Thinking...";

fetch("ai_answer.php",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"question="+encodeURIComponent(q)

})

.then(res=>res.text())

.then(data=>{

document.getElementById("answer").innerHTML=data;

});

}

</script>

</body>
</html>