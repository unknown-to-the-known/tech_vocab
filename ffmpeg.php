<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/@ffmpeg/ffmpeg@0.12.6/dist/ffmpeg.min.js"></script>
</head>
<body>
    <input type="file" id="videoInput" accept="video/*">
<button onclick="compressVideo()">Compress & Upload</button>

<script>
const { createFFmpeg, fetchFile } = FFmpeg;
const ffmpeg = createFFmpeg({ log: true });

async function compressVideo() {
    const input = document.getElementById("videoInput").files[0];
    if (!input) {
        alert("Please select video");
        return;
    }

    if (!ffmpeg.isLoaded()) {
        await ffmpeg.load();
    }

    ffmpeg.FS("writeFile", "input.mp4", await fetchFile(input));

    // Compress settings
    await ffmpeg.run(
        "-i", "input.mp4",
        "-vcodec", "libx264",
        "-crf", "28",
        "-preset", "fast",
        "-acodec", "aac",
        "-b:a", "128k",
        "output.mp4"
    );

    const data = ffmpeg.FS("readFile", "output.mp4");

    const compressedBlob = new Blob([data.buffer], { type: "video/mp4" });

    uploadVideo(compressedBlob);
}

function uploadVideo(blob) {
    const formData = new FormData();
    formData.append("video", blob, "compressed.mp4");

    fetch("upload.php", {
        method: "POST",
        body: formData
    }).then(res => res.text())
      .then(data => alert("Uploaded Successfully"));
}
</script>
</body>
</html>