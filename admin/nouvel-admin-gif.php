<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location: login.php"); exit();
}
if(isset($_GET['logout'])){
    unset($_SESSION['user']);
    header("location: login.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un GIF — Taquin</title>
    <script src="js/gifshot.min.js"></script>
    <style>
        :root {
            --rose: rgb(190, 119, 119);
            --rose-muted: rgb(212, 160, 160);
            --rose-light: rgb(245, 234, 234);
            --rose-bg: rgb(250, 244, 244);
            --text: rgb(80, 55, 55);
            --text-muted: rgb(160, 130, 130);
            --border: rgba(190, 119, 119, 0.25);
            --radius: 8px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Georgia', serif;
            background-color: var(--rose-bg);
            color: var(--text);
            min-height: 100vh;
        }
        .page {
            max-width: 560px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }

        /* HEADER */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1.25rem;
            border-bottom: 0.5px solid var(--border);
            margin-bottom: 2.5rem;
        }
        .header-left { display: flex; align-items: center; gap: 1rem; }
        .back-link, .logout-link {
            font-size: 12px;
            color: var(--text-muted);
            text-decoration: none;
            border: 0.5px solid var(--border);
            border-radius: 6px;
            padding: 5px 12px;
            transition: background 0.15s;
        }
        .back-link:hover, .logout-link:hover { background: var(--rose-light); }
        header h1 {
            font-size: 1.1rem;
            font-weight: normal;
            color: var(--rose);
            letter-spacing: 0.02em;
        }

        /* SECTIONS */
        section { margin-bottom: 2rem; }
        h2 {
            font-size: 0.7rem;
            font-weight: normal;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        /* VIDEO INPUT */
        .file-input-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 0.5px solid var(--border);
            border-radius: var(--radius);
            font-size: 13px;
            color: var(--text-muted);
            cursor: pointer;
            background: white;
            transition: background 0.15s;
        }
        .file-input-label:hover { background: var(--rose-light); }
        .file-input-label input { display: none; }
        .file-name {
            font-size: 12px;
            color: var(--text-muted);
            font-style: italic;
            margin-top: 6px;
        }

        /* PREVIEW VIDEO */
        #videoPreview {
            display: none;
            width: 100%;
            max-height: 260px;
            object-fit: contain;
            border-radius: var(--radius);
            border: 0.5px solid var(--border);
            background: white;
            margin-top: 1rem;
        }

        /* CONTROLS */
        .controls-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 1.25rem;
        }
        .control-item label {
            display: block;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .control-item input[type="number"],
        .control-item input[type="range"] {
            width: 100%;
            padding: 7px 10px;
            border: 0.5px solid var(--border);
            border-radius: var(--radius);
            background: white;
            color: var(--text);
            font-size: 13px;
            font-family: 'Georgia', serif;
        }
        .control-item input[type="range"] {
            padding: 4px 0;
            accent-color: var(--rose);
        }
        .range-value {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            text-align: right;
        }

        /* BOUTON GÉNÉRER */
        .btn-generate {
            width: 100%;
            padding: 11px;
            background: var(--rose);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 14px;
            font-family: 'Georgia', serif;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .btn-generate:hover { opacity: 0.85; }
        .btn-generate:disabled { opacity: 0.5; cursor: not-allowed; }

        /* PROGRESS */
        .progress-wrap {
            display: none;
            margin-top: 1rem;
        }
        .progress-bar-bg {
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            width: 0%;
            background: var(--rose);
            border-radius: 2px;
            transition: width 0.3s ease;
        }
        .progress-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 6px;
            font-style: italic;
        }

        /* MESSAGE */
        .message {
            padding: 10px 16px;
            border-radius: var(--radius);
            font-size: 13px;
            margin-top: 1rem;
            display: none;
        }
        .message.success {
            background: var(--rose-light);
            color: var(--rose);
            border: 0.5px solid var(--rose-muted);
            display: block;
        }
        .message.error {
            background: #fdecea;
            color: #c0392b;
            border: 0.5px solid #e8b4b0;
            display: block;
        }

        /* APERÇU GIF */
        #gifResult {
            display: none;
            margin-top: 1.5rem;
        }
        #gifResult img {
            width: 100%;
            border-radius: var(--radius);
            border: 0.5px solid var(--border);
        }
        .gif-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-save {
            flex: 1;
            padding: 9px;
            background: var(--rose);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 13px;
            font-family: 'Georgia', serif;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .btn-save:hover { opacity: 0.85; }
        .btn-reset {
            padding: 9px 16px;
            border: 0.5px solid var(--border);
            border-radius: var(--radius);
            background: transparent;
            color: var(--text-muted);
            font-size: 13px;
            font-family: 'Georgia', serif;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-reset:hover { background: var(--rose-light); }
    </style>
</head>
<body>
<div class="page">

    <header>
        <div class="header-left">
            <a href="admin.php" class="back-link">← Admin</a>
            <h1>Créer un GIF</h1>
        </div>
        <a href="?logout" class="logout-link">Déconnexion</a>
    </header>

    <!-- ÉTAPE 1 : Vidéo -->
    <section>
        <h2>1. Choisir la vidéo</h2>
        <label class="file-input-label">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="4" width="10" height="8" rx="1"/>
                <path d="M12 7l3-2v6l-3-2"/>
            </svg>
            Choisir une vidéo
            <input type="file" id="videoInput" accept="video/*"/>
        </label>
        <div class="file-name" id="fileName">mp4, mov, webm…</div>
        <video id="videoPreview" controls playsinline></video>
    </section>

    <!-- ÉTAPE 2 : Réglages -->
    <section>
        <h2>2. Réglages</h2>
        <div class="controls-grid">
            <div class="control-item">
                <label>Début (secondes)</label>
                <input type="number" id="startTime" value="0" min="0" step="0.5"/>
            </div>
            <div class="control-item">
                <label>Durée (secondes)</label>
                <input type="number" id="duration" value="2" min="1" max="6" step="0.5"/>
            </div>
            <div class="control-item">
                <label>Largeur (px)</label>
                <input type="number" id="gifWidth" value="400" min="100" max="600" step="50"/>
            </div>
            <div class="control-item">
                <label>Qualité</label>
                <input type="range" id="quality" min="1" max="20" value="10"
                       oninput="document.getElementById('qualityVal').textContent=this.value"/>
                <div class="range-value">
                    <span id="qualityVal">10</span> / 20
                </div>
            </div>
        </div>

        <button class="btn-generate" id="btnGenerate" onclick="createGif()" disabled>
            Générer le GIF
        </button>

        <div class="progress-wrap" id="progressWrap">
            <div class="progress-bar-bg">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            <div class="progress-label" id="progressLabel">Traitement en cours…</div>
        </div>
    </section>

    <!-- ÉTAPE 3 : Résultat -->
    <div id="gifResult">
        <h2>3. Aperçu &amp; enregistrement</h2>
        <img id="gifPreview" src="" alt="GIF généré"/>
        <div class="gif-actions">
            <button class="btn-save" onclick="saveGif()">Enregistrer dans la galerie</button>
            <button class="btn-reset" onclick="resetAll()">Recommencer</button>
        </div>
    </div>

    <div class="message" id="message"></div>

</div>
<script>
let currentGifData = null;
let videoRatio = 1; // ratio largeur/hauteur, mis à jour au chargement

// Sélection vidéo
document.getElementById('videoInput').addEventListener('change', function(){
    const file = this.files[0];
    if(!file) return;
    document.getElementById('fileName').textContent = file.name;
    const video = document.getElementById('videoPreview');
    video.src = URL.createObjectURL(file);
    video.style.display = 'block';
    document.getElementById('btnGenerate').disabled = false;
    video.onloadedmetadata = function(){
        document.getElementById('startTime').max = Math.floor(video.duration);
        // Stocker le ratio réel pour calculer gifHeight
        videoRatio = video.videoWidth / video.videoHeight;
    };
});

function createGif(){
    const fileInput = document.getElementById('videoInput');
    if(!fileInput.files[0]) return;

    const start    = parseFloat(document.getElementById('startTime').value);
    const duration = parseFloat(document.getElementById('duration').value);
    const width    = parseInt(document.getElementById('gifWidth').value);
    const quality  = parseInt(document.getElementById('quality').value);
    const numFrames = Math.round(duration * 10);

    // UI : lancement
    document.getElementById('btnGenerate').disabled = true;
    document.getElementById('progressWrap').style.display = 'block';
    document.getElementById('gifResult').style.display = 'none';
    setMessage('', '');

    let fakeProgress = 0;
    const progressBar = document.getElementById('progressBar');
    const progressLabel = document.getElementById('progressLabel');
    // Progression simulée pendant la génération
    const ticker = setInterval(() => {
        if(fakeProgress < 85){ fakeProgress += 2; }
        progressBar.style.width = fakeProgress + '%';
        progressLabel.textContent = 'Traitement en cours… ' + fakeProgress + '%';
    }, 300);

    const height = Math.round(width / videoRatio);
    const video = document.getElementById('videoPreview');

    // Seek manuel avant de lancer gifshot — plus fiable que le paramètre offset
    video.currentTime = start;
    video.onseeked = function(){
        video.onseeked = null;
        gifshot.createGIF({
            video: [URL.createObjectURL(fileInput.files[0])],
            offset: 0,
            interval: 0.1,
            numFrames: numFrames,
            gifWidth: width,
            gifHeight: height,
            sampleInterval: quality
        }, function(obj){
            clearInterval(ticker);
            progressBar.style.width = '100%';

            if(!obj.error){
                currentGifData = obj.image;
                progressLabel.textContent = 'Génération terminée.';
                setTimeout(() => {
                    document.getElementById('progressWrap').style.display = 'none';
                    document.getElementById('gifPreview').src = currentGifData;
                    document.getElementById('gifResult').style.display = 'block';
                    document.getElementById('btnGenerate').disabled = false;
                }, 400);
            } else {
                progressLabel.textContent = '';
                document.getElementById('progressWrap').style.display = 'none';
                setMessage('Erreur lors de la génération : ' + obj.errorMsg, 'error');
                document.getElementById('btnGenerate').disabled = false;
            }
        });
    };
}

function saveGif(){
    if(!currentGifData) return;
    const formData = new FormData();
    formData.append('gif', currentGifData);

    setMessage('Enregistrement…', 'success');

    fetch('upload-gif.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                setMessage('✓ ' + data.fileName + ' enregistré dans la galerie.', 'success');
            } else {
                setMessage('Erreur : ' + data.error, 'error');
            }
        })
        .catch(() => setMessage('Erreur réseau lors de l\'enregistrement.', 'error'));
}

function resetAll(){
    currentGifData = null;
    document.getElementById('videoInput').value = '';
    document.getElementById('fileName').textContent = 'mp4, mov, webm…';
    document.getElementById('videoPreview').style.display = 'none';
    document.getElementById('videoPreview').src = '';
    document.getElementById('gifResult').style.display = 'none';
    document.getElementById('progressWrap').style.display = 'none';
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('btnGenerate').disabled = true;
    setMessage('', '');
}

function setMessage(text, type){
    const el = document.getElementById('message');
    el.textContent = text;
    el.className = 'message' + (type ? ' ' + type : '');
}
</script>
</body>
</html>