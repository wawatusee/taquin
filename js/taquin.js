let nbrPiecesPerLine = 0
let pieceInvisible = null
let stylePieceInvisible = null
let displayedNumero = false
let activeTimeouts = [];
// Variables globales pour l'audio
let audioCtx = null;
let audioBuffer = null;
let segmentDuration = 0;

document.body.onload = setBoard

// 1. Déclarer loadAudio à l'extérieur (plus propre et réutilisable)
async function loadAudio(url) {
    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    try {
        const response = await fetch(url);
        const arrayBuffer = await response.arrayBuffer();
        audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);

        // On calcule la durée d'un segment de son
        let numberOfPieces = nbrPiecesPerLine * nbrPiecesPerLine;
        segmentDuration = audioBuffer.duration / numberOfPieces;

        console.log("Audio chargé avec succès ! Durée d'un segment :", segmentDuration, "s");
    } catch (e) {
        console.error("Impossible de charger le fichier audio :", e);
    }
}

function setBoard() {
    nbrPiecesPerLine = Number(getComputedStyle(document.documentElement).getPropertyValue('--nbrPiecesPerLine'));

    // 2. On LANCE le chargement du son ici (remplace 'chemin/vers/ton/son.mp3')
    loadAudio('sons/ad1.mp3');

    let numberOfPieces = nbrPiecesPerLine * nbrPiecesPerLine
    let scene = document.getElementById("scene")
    let board = document.createElement("div")
    board.classList.add("taquin")

    for (let i = 1; i < numberOfPieces + 1; i++) {
        let piece = document.createElement("div");
        piece.classList.add("piece")
        board.appendChild(piece)
    }
    scene.appendChild(board)
    pieceInvisible = board.lastChild
    pieceInvisible.id = "pieceInvisible"

    taquin();
}

function taquin() {
    let lesPieces = document.getElementsByClassName("piece");
    pieceInvisible = document.querySelector("#pieceInvisible");
    stylePieceInvisible = getComputedStyle(pieceInvisible);
    let shuffleArray = getShuffleArray();

    for (let i = 0; i < lesPieces.length; i++) {
        let chaquePiece = lesPieces[i];
        let col = i % nbrPiecesPerLine;
        let row = Math.floor(i / nbrPiecesPerLine);

        if (nbrPiecesPerLine > 1) {
            chaquePiece.style.backgroundPositionX = `${(col / (nbrPiecesPerLine - 1)) * 100}%`;
            chaquePiece.style.backgroundPositionY = `${(row / (nbrPiecesPerLine - 1)) * 100}%`;
        } else {
            chaquePiece.style.backgroundPositionX = '0%';
            chaquePiece.style.backgroundPositionY = '0%';
        }

        chaquePiece.style.order = shuffleArray[i];
        chaquePiece.addEventListener("click", joue);
    };
};

function getShuffleArray() {
    let n = nbrPiecesPerLine * nbrPiecesPerLine;

    // 1. Au départ, l'ordre CSS correspond exactement à la position des pièces (1, 2, 3...)
    // arr[0] est la pièce 1, avec un style.order = 1, etc.
    let arr = Array.from({ length: n }, (_, i) => i + 1);

    // La pièce invisible est TOUJOURS la dernière du DOM (index n - 1)
    let blankDOMIndex = n - 1;

    // On va suivre la valeur CSS 'order' actuelle de cette pièce invisible
    // Au départ, son ordre visuel est égal à sa position (n)
    let blankCurrentOrder = n;

    // 2. On simule des mélanges (ex: 100 mouvements)
    let moves = 100;
    for (let m = 0; m < moves; m++) {
        let possibleOrdersToSwap = [];

        // On calcule la position de la case vide sur la grille VISUELLE (grâce à son .order)
        let visualPos = blankCurrentOrder - 1;
        let row = Math.floor(visualPos / nbrPiecesPerLine);
        let col = visualPos % nbrPiecesPerLine;

        // On identifie les positions visuelles voisines (Haut, Bas, Gauche, Droite)
        if (row > 0) possibleOrdersToSwap.push(blankCurrentOrder - nbrPiecesPerLine);
        if (row < nbrPiecesPerLine - 1) possibleOrdersToSwap.push(blankCurrentOrder + nbrPiecesPerLine);
        if (col > 0) possibleOrdersToSwap.push(blankCurrentOrder - 1);
        if (col < nbrPiecesPerLine - 1) possibleOrdersToSwap.push(blankCurrentOrder + 1);

        // On choisit une valeur d'order voisine au hasard
        let targetOrder = possibleOrdersToSwap[Math.floor(Math.random() * possibleOrdersToSwap.length)];

        // On cherche quelle pièce (dans le DOM) possède actuellement cet 'order' cible
        let targetDOMIndex = arr.indexOf(targetOrder);

        // On inverse les valeurs d'order dans notre tableau
        arr[blankDOMIndex] = targetOrder;
        arr[targetDOMIndex] = blankCurrentOrder;

        // La case vide a maintenant pris la valeur d'order de sa cible
        blankCurrentOrder = targetOrder;
    }

    return arr;
}


function jouerSequenceAudioDepuis(orderClic) {
    // 1. ARRÊT DU SON EN COURS
    if (audioCtx) {
        audioCtx.close();
    }
    audioCtx = new (window.AudioContext || window.webkitAudioContext)();

    let lesPieces = Array.from(document.getElementsByClassName("piece"));
    lesPieces.sort((a, b) => Number(a.style.order) - Number(b.style.order));

    // 2. RESET DES LUMIÈRES & DES MINUTEURS PRÉCÉDENTS
    // On annule tous les setTimeout qui attendaient leur tour
    activeTimeouts.forEach(timeoutId => clearTimeout(timeoutId));
    activeTimeouts = []; // On vide le tableau

    // On éteint de force toutes les pièces actuellement allumées
    lesPieces.forEach(p => p.classList.remove("en-lecture"));

    let tempsEcoule = 0;

    for (let i = 0; i < lesPieces.length; i++) {
        let piece = lesPieces[i];
        let currentOrder = Number(piece.style.order);

        if (currentOrder < orderClic) continue;

        let domIndex = Array.from(piece.parentNode.children).indexOf(piece);
        let startTimeDuSegment = domIndex * segmentDuration;

        // La pièce invisible est sourde : on passe au segment suivant en restant muet
        if (piece.id === "pieceInvisible") {
            tempsEcoule += segmentDuration;
            continue;
        }

        let source = audioCtx.createBufferSource();
        source.buffer = audioBuffer;
        source.connect(audioCtx.destination);

        source.start(audioCtx.currentTime + tempsEcoule, startTimeDuSegment, segmentDuration);

        let debutEffet = tempsEcoule * 1000;
        let finEffet = (tempsEcoule + segmentDuration) * 1000;

        // Étape A : Allumer la pièce
        let idAllumage = setTimeout(() => {
            piece.classList.add("en-lecture");
        }, debutEffet);
        activeTimeouts.push(idAllumage); // On enregistre le minuteur

        // Étape B : Éteindre la pièce
        let idExtinction = setTimeout(() => {
            piece.classList.remove("en-lecture");
        }, finEffet);
        activeTimeouts.push(idExtinction); // On enregistre le minuteur

        tempsEcoule += segmentDuration;
    }
}
function joue(evt) {
    let sonStyle = getComputedStyle(evt.target);

    // 1. Logique de déplacement classique du Taquin
    if (pieceCliquable(stylePieceInvisible.order, sonStyle.order)) {
        let temporaryOrder = pieceInvisible.style.order;
        pieceInvisible.style.order = sonStyle.order;
        evt.target.style.order = temporaryOrder;
    }

    // 2. Logique Audio : Jouer le son "dans l'ordre du désordre"
    if (audioBuffer && audioCtx) {
        jouerSequenceAudioDepuis(Number(sonStyle.order));
    }

    if (testIssue()) {
        endOfGame();
    }
}

function pieceCliquable(pieceInvisible, pieceAtester, largueurTaquin = nbrPiecesPerLine) {
    pieceInvisible = Number(pieceInvisible);
    pieceAtester = Number(pieceAtester);
    let jouable = (pieceAtester == (pieceInvisible - 1) && (pieceInvisible % largueurTaquin != 1))
        || (pieceAtester == (pieceInvisible + 1) && (pieceInvisible % largueurTaquin != 0))
        || (pieceAtester == (pieceInvisible + largueurTaquin))
        || (pieceAtester == (pieceInvisible - largueurTaquin));
    return jouable;
}

function aGetPiecesOrder() {
    let lesPieces = document.getElementsByClassName("piece");
    let orderArray = [];
    stylePieceInvisible = getComputedStyle(pieceInvisible);
    for (let i = 0; i < lesPieces.length; i++) {
        let chaquePiece = lesPieces[i];
        let sonStyle = getComputedStyle(chaquePiece);
        orderArray.push(Number(sonStyle.order));
    };
    return orderArray
};

function testIssue() {
    let nbrPiecesOrdonnees = 0;
    let lesPieces = document.getElementsByClassName("piece");
    let nbrPieces = lesPieces.length;
    for (let i = 0; i < nbrPieces; i++) {
        let chaquePiece = lesPieces[i];
        let sonStyle = getComputedStyle(chaquePiece);
        if (sonStyle.order == i + 1) {
            nbrPiecesOrdonnees += 1;
        }
        if (nbrPiecesOrdonnees > (nbrPiecesPerLine * nbrPiecesPerLine) - 2) {
            return true;
        }
    }
}

function displayPiecesNumber() {
    let pieces = document.getElementsByClassName("piece");
    if (displayedNumero === false) {
        for (let i = 0; i < pieces.length; i++) {
            let piece = pieces[i]
            piece.textContent = i + 1;
        }
        displayedNumero = true;
    } else {
        for (let i = 0; i < pieces.length; i++) {
            let piece = pieces[i]
            piece.textContent = " ";
        }
        displayedNumero = false;
    }
}

function endOfGame() {
    // Arrêter le mode auto si actif
    if (autoRunning) toggleAuto();
    let lesPieces = document.getElementsByClassName("piece");
    document.getElementById("pieceInvisible").style.visibility = "visible";
    for (let i = 0; i < lesPieces.length; i++) {
        let chaquePiece = lesPieces[i];
        chaquePiece.removeEventListener("click", joue);
    };
    // Afficher le bouton de partage
    document.getElementById("shareButton").style.display = "flex";
}

function shareGame() {
    const url = `${location.origin}${location.pathname}?nbrPieces=${nbrPiecesTotal}&image=${encodeURIComponent(nomImage)}`;
    const shareData = {
        title: document.title,
        text: "J'ai réussi ce taquin, à toi de jouer !",
        url: url
    };
    // Web Share API sur mobile, clipboard en fallback
    if (navigator.share && navigator.canShare && navigator.canShare(shareData)) {
        navigator.share(shareData).catch(() => { });
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const btn = document.getElementById("shareButton");
            const original = btn.innerHTML;
            btn.textContent = "Lien copié !";
            setTimeout(() => { btn.innerHTML = original; }, 2000);
        });
    }
}

// MODE AUTO
let autoInterval = null;
let autoRunning = false;

function getCliquables() {
    // Retourne la liste des pièces cliquables à ce moment
    let lesPieces = document.getElementsByClassName("piece");
    let cliquables = [];
    stylePieceInvisible = getComputedStyle(pieceInvisible);
    for (let i = 0; i < lesPieces.length; i++) {
        let sonStyle = getComputedStyle(lesPieces[i]);
        if (pieceCliquable(stylePieceInvisible.order, sonStyle.order)) {
            cliquables.push(lesPieces[i]);
        }
    }
    return cliquables;
}

function autoPlay() {
    let cliquables = getCliquables();
    if (cliquables.length === 0) return;
    // Choisir une pièce au hasard parmi les cliquables
    let piece = cliquables[Math.floor(Math.random() * cliquables.length)];
    piece.click();
}

function toggleAuto() {
    let btn = document.getElementById("autoButton");
    if (autoRunning) {
        clearInterval(autoInterval);
        autoInterval = null;
        autoRunning = false;
        btn.textContent = "Auto";
        btn.classList.remove("auto-on");
    } else {
        autoRunning = true;
        btn.textContent = "Stop";
        btn.classList.add("auto-on");
        autoInterval = setInterval(autoPlay, 300);
    }
}


