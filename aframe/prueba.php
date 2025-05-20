<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>A-Frame WebRTC Peer</title>
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
</head>

<body>
    <!-- A-Frame Scene -->
    <a-scene>
        <a-video id="remoteVideo" position="0 1.6 -3" width="4" height="3"></a-video>
        <a-plane position="0 0 -4" rotation="-90 0 0" width="10" height="10" color="#333"></a-plane>
        <a-sky color="#222"></a-sky>
    </a-scene>

    <!-- Hidden Video Elements -->
    <video id="localVideo" autoplay playsinline muted style="display:none;"></video>
    <video id="streamVideo" autoplay playsinline style="display:none;"></video>
    <video id="remoteVideo" autoplay playsinline style="display:none;"></video>

    <!-- Signaling Controls -->
    <div style="position:fixed; top:10px; left:10px; z-index:10; background:#fff;padding:10px;">
        <button onclick="start(true)">Start Caller</button>
        <button onclick="start(false)">Start Callee</button>
        <br><br>
        <label>Offer:</label><br>
        <textarea id="offer" rows="8" cols="40" placeholder="Paste offer here..."></textarea><br>
        <button onclick="setOffer()">Set Offer</button>
        <br><br>
        <label>Answer:</label><br>
        <textarea id="answer" rows="8" cols="40" placeholder="Copy/paste answer here..."></textarea><br>
        <button onclick="setAnswer()">Set Answer</button>
    </div>

    <script>
        window.onload = () => {
            const localVideo = document.getElementById('localVideo');
            const remoteVideo = document.getElementById('remoteVideo');
            const streamVideo = document.getElementById('streamVideo');
            const offerField = document.getElementById('offer');
            const answerField = document.getElementById('answer');

            let pc;

            async function start(isCaller) {
                pc = new RTCPeerConnection();

                pc.ontrack = (event) => {
                    const remoteStream = event.streams[0];
                    streamVideo.srcObject = remoteStream;

                    streamVideo.onloadeddata = () => {
                        if (remoteVideo) {
                            remoteVideo.setAttribute('src', '#streamVideo');
                        } else {
                            console.warn('remoteVideo no encontrado en el DOM.');
                        }
                    };
                };

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true,
                        audio: false
                    });
                    stream.getTracks().forEach(track => pc.addTrack(track, stream));
                    localVideo.srcObject = stream;
                } catch (err) {
                    alert("No se pudo acceder a la cámara: " + err.message);
                    return;
                }

                if (isCaller) {
                    const offer = await pc.createOffer();
                    await pc.setLocalDescription(offer);

                    pc.onicecandidate = (e) => {
                        if (e.candidate === null) {
                            offerField.value = JSON.stringify(pc.localDescription);
                        }
                    };
                } else {
                    pc.onicecandidate = (e) => {
                        if (e.candidate === null) {
                            answerField.value = JSON.stringify(pc.localDescription);
                        }
                    };
                }
            }

            function setOffer() {
                const offerText = offerField.value.trim();
                if (!offerText) return;

                const offer = JSON.parse(offerText);
                pc.setRemoteDescription(offer).then(async () => {
                    const answer = await pc.createAnswer();
                    await pc.setLocalDescription(answer);
                });
            }

            function setAnswer() {
                const answerText = answerField.value.trim();
                if (!answerText) return;

                const answer = JSON.parse(answerText);
                pc.setRemoteDescription(answer);
            }

            // Hacer las funciones globales
            window.start = start;
            window.setOffer = setOffer;
            window.setAnswer = setAnswer;
        };
    </script>
</body>

</html>