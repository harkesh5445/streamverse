// resources/js/webrtc.js

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

// This is where you will define the WebRTC and signaling logic.

const localVideo = document.getElementById('local-video');
const remoteVideos = document.getElementById('remote-videos');
const startBtn = document.getElementById('start-stream-btn');
const stopBtn = document.getElementById('stop-stream-btn'); // Main stop button
const saveStopBtn = document.getElementById('save-stop-btn'); // Save and stop button
const recordBtn = document.getElementById('record-btn'); // Toggle recording button
const shareBtn = document.getElementById('share-btn');
const addMemberBtn = document.getElementById('add-member-btn');
const toggleLayoutBtn = document.getElementById('toggle-layout-btn');
const liveControls = document.getElementById('live-controls');
const recordDot = document.getElementById('record-dot');
const timerText = document.getElementById('timer-text');

const streamUuid = document.querySelector('[data-uuid]').getAttribute('data-uuid');
const userId = document.querySelector('[data-user-id]').getAttribute('data-user-id');

let localStream;
let peerConnections = {};
let mediaRecorder;
let recordedChunks = [];
let isRecording = false;
let timerInterval;
let startTime;

// =================================================================
// Core WebRTC and Signaling Logic
// =================================================================

// 1. Get local camera and mic stream
async function getLocalStream() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;
        localVideo.muted = true; // Mute local video to prevent feedback
    } catch (err) {
        console.error("Error accessing local media: ", err);
        alert("Could not access your camera or microphone. Please check permissions.");
    }
}

// 2. Create a peer connection for a new user
function createPeerConnection(userId) {
    const peerConnection = new RTCPeerConnection({
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' } // A public STUN server
        ]
    });

    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });

    peerConnection.ontrack = (event) => {
        const remoteVideo = document.createElement('video');
        remoteVideo.srcObject = event.streams[0];
        remoteVideo.autoplay = true;
        remoteVideo.playsInline = true;
        remoteVideo.classList.add('remote-video');
        remoteVideos.appendChild(remoteVideo);
    };

    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            sendSignal({ type: 'ice-candidate', candidate: event.candidate }, userId);
        }
    };

    return peerConnection;
}

// 3. Send a signaling message to the backend
function sendSignal(data, recipientId) {
    fetch(`/stream/broadcast/${streamUuid}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ data: { signal: data, recipient_id: recipientId } })
    }).catch(err => console.error("Error sending signal: ", err));
}

function broadcastSignal(data) {
    fetch(`/stream/broadcast/${streamUuid}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ data })
    });
}

// 4. Handle incoming signals from the backend
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

window.Echo.private(`stream.${streamUuid}`)
    .listen('StreamSignalEvent', (e) => {
        // handleSignal(e.data); // You need to implement the handleSignal function
    });

// =================================================================
// UI and Control Logic
// =================================================================

function showLiveControls() {
    liveControls.style.display = 'flex';
    startBtn.style.display = 'none';
}

function hideLiveControls() {
    liveControls.style.display = 'none';
    startBtn.style.display = 'block';
}

function stopStream() {
    if (isRecording) {
        stopRecording(); // This will trigger the onstop event and save the recording
    }
    stopTimer();

    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
        localVideo.srcObject = null;
    }
    Object.values(peerConnections).forEach(pc => pc.close());
    peerConnections = {};
    broadcastSignal({ type: 'leave', userId });
    hideLiveControls();
}

// --- Timer ---
function startTimer() {
    if (timerInterval) clearInterval(timerInterval);
    startTime = Date.now();
    timerInterval = setInterval(() => {
        const elapsed = Date.now() - startTime;
        const mins = String(Math.floor(elapsed / 60000)).padStart(2, '0');
        const secs = String(Math.floor((elapsed % 60000) / 1000)).padStart(2, '0');
        timerText.textContent = `${mins}:${secs}`;
    }, 1000);
}

function stopTimer() {
    clearInterval(timerInterval);
    timerText.textContent = '00:00';
}

// --- Recording ---
function startRecording() {
    if (!localStream) {
        alert('No video stream available for recording.');
        return;
    }
    recordedChunks = [];
    const options = { mimeType: 'video/webm; codecs=vp9' };
    if (!MediaRecorder.isTypeSupported(options.mimeType)) {
        console.error(`${options.mimeType} is not supported.`);
        alert('The webm video format is not supported on your browser.');
        return;
    }

    try {
        mediaRecorder = new MediaRecorder(localStream, options);
    } catch (e) {
        console.error('Exception while creating MediaRecorder:', e);
        alert('MediaRecorder failed to initialize.');
        return;
    }

    mediaRecorder.ondataavailable = (event) => {
        if (event.data && event.data.size > 0) {
            recordedChunks.push(event.data);
        }
    };

    mediaRecorder.onstop = () => {
        console.log('Recording stopped. Total chunks:', recordedChunks.length);
        saveRecording(); // This is the ONLY place where saveRecording should be called.
    };

    mediaRecorder.start(1000); // Collect data in chunks
    console.log('Recording started');
}

function stopRecording() {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
        console.log('MediaRecorder.stop() called');
    }
}

async function saveRecording() {
    if (recordedChunks.length === 0) {
        console.warn('No recording data to save.');
        return;
    }

    const blob = new Blob(recordedChunks, { type: 'video/webm' });

    // 1. Download locally
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.style.display = 'none';
    a.href = url;
    a.download = `recorded-stream-${new Date().toISOString()}.webm`;
    document.body.appendChild(a);
    a.click();
    setTimeout(() => {
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }, 100);

    // 2. Upload to server
    try {
        await saveRecordingToServer(blob);
        console.log('Recording uploaded successfully');
    } catch (e) {
        console.error('Recording upload failed', e);
        alert('Failed to upload recording to the server.');
    } finally {
        recordedChunks = []; // Clear chunks after saving
    }
}

async function saveRecordingToServer(blob) {
    const formData = new FormData();
    formData.append('recording', blob, `stream-recording.webm`);
    // You might want to pass the streamUuid as well
    // formData.append('stream_uuid', streamUuid);

    const response = await fetch('/recordings/upload', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            // 'Content-Type' is set automatically by the browser for FormData
        },
        body: formData
    });
    if (!response.ok) {
        throw new Error(`Upload failed with status: ${response.status}`);
    }
    return response.json();
}

// --- Layout ---
function updateLayout() {
    const remoteCount = remoteVideos.querySelectorAll('video').length;
    if (remoteCount > 0) {
        localVideo.classList.add('w-1/2');
        localVideo.classList.remove('w-full');
        remoteVideos.classList.add('grid', 'grid-cols-2');
    } else {
        localVideo.classList.remove('w-1/2');
        localVideo.classList.add('w-full');
        remoteVideos.classList.remove('grid', 'grid-cols-2');
    }
}

// =================================================================
// Event Listeners
// =================================================================

startBtn.addEventListener('click', async () => {
    await getLocalStream();
    if (localStream) {
        showLiveControls();
        broadcastSignal({ type: 'join', userId });
    }
});

stopBtn.addEventListener('click', () => {
    if (confirm('Are you sure you want to stop the stream? Any unsaved recording will be lost.')) {
        stopStream();
    }
});

saveStopBtn.addEventListener('click', () => {
    if (confirm('Are you sure you want to stop the stream? The current recording will be saved.')) {
        stopStream(); // This now correctly handles saving if a recording is in progress
    }
});

recordBtn.addEventListener('click', () => {
    if (!localStream) {
        alert('You must start the stream before you can record.');
        return;
    }

    if (!isRecording) {
        startRecording();
        startTimer();
        recordDot.style.display = 'inline-block';
        recordBtn.title = 'Stop Recording';
        isRecording = true;
    } else {
        stopRecording(); // Triggers onstop, which saves the file
        stopTimer();
        recordDot.style.display = 'none';
        recordBtn.title = 'Start Recording';
        isRecording = false;
    }
});

addMemberBtn.addEventListener('click', () => {
    const guestId = prompt('Enter the user ID or email of the guest to invite:');
    if (guestId) {
        broadcastSignal({ type: 'invite', guestId });
        alert('Invitation sent!');
    }
});

toggleLayoutBtn.addEventListener('click', () => {
    const isPortrait = localVideo.classList.contains('portrait');
    localVideo.classList.toggle('portrait', !isPortrait);
    localVideo.classList.toggle('landscape', isPortrait);
    remoteVideos.classList.toggle('portrait', !isPortrait);
    remoteVideos.classList.toggle('landscape', isPortrait);
});

// --- Observers ---
const observer = new MutationObserver(updateLayout);
observer.observe(remoteVideos, { childList: true });

window.addEventListener('DOMContentLoaded', () => {
    localVideo.classList.add('portrait');
    remoteVideos.classList.add('portrait');
});