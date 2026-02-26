<!DOCTYPE html>
<html>
<head>
    <title>Video Compressor</title>
</head>
<body>

<h2>Compress Video Before Upload</h2>

<input type="file" id="videoInput" accept="video/*">
<br><br>

<button onclick="compressVideo()">Compress & Upload</button>

<br><br>
<progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
<p id="status"></p>

<script type="module">

import { createFFmpeg, fetchFile } 
from 'https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.12.6/dist/ffmpeg.min.js';

const ffmpeg = createFFmpeg({
    log: true,
    progress: ({ ratio }) => {
        document.getElementById("progressBar").value = ratio * 100;
    }
});

window.compressVideo = async function () {

    const file = document.getElementById("videoInput").files[0];
    if (!file) {
        alert("Please select a video");
        return;
    }

    const status = document.getElementById("status");
    status.innerText = "Loading FFmpeg...";

    if (!ffmpeg.isLoaded()) {
        await ffmpeg.load();
    }

    status.innerText = "Compressing video...";

    // Write input file to FFmpeg memory
    ffmpeg.FS("writeFile", "input.mp4", await fetchFile(file));

    // Compression settings (GOOD balance for students)
    await ffmpeg.run(
        "-i", "input.mp4",
        "-vcodec", "libx264",
        "-crf", "28",        // higher = more compression
        "-preset", "fast",
        "-acodec", "aac",
        "-b:a", "128k",
        "-movflags", "+faststart",
        "output.mp4"
    );

    status.innerText = "Preparing upload...";

    const data = ffmpeg.FS("readFile", "output.mp4");

    const compressedBlob = new Blob([data.buffer], { type: "video/mp4" });

    uploadVideo(compressedBlob);
};

function uploadVideo(blob) {

    const status = document.getElementById("status");
    status.innerText = "Uploading...";

    const formData = new FormData();
    formData.append("video", blob, "compressed.mp4");

    fetch("upload.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        status.innerText = "Upload Complete!";
        alert("Video uploaded successfully!");
    })
    .catch(err => {
        status.innerText = "Upload Failed!";
        console.error(err);
    });
}

</script>

</body>
</html>