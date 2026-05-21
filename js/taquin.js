let nbrPiecesPerLine = 0
let pieceInvisible = null
let stylePieceInvisible = null
let displayedNumero = false

document.body.onload = setBoard

function setBoard(){
    nbrPiecesPerLine = Number(getComputedStyle(document.documentElement).getPropertyValue('--nbrPiecesPerLine'));
    let numberOfPieces = nbrPiecesPerLine * nbrPiecesPerLine
    let scene = document.getElementById("scene")
    let board = document.createElement("div")
    board.classList.add("taquin")
    for (let i = 1; i < numberOfPieces + 1; i++){
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
    let arr = Array.from({length: n}, (_, i) => i + 1);

    // Mélange Fisher-Yates
    for (let i = n - 1; i > 0; i--) {
        let j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }

    // Correction de parité — on permute les deux premières pièces qui ne sont pas la pièce invisible
    if (!isSolvable(arr)) {
        let candidates = [];
        for (let i = 0; i < arr.length && candidates.length < 2; i++) {
            if (arr[i] !== n) candidates.push(i);
        }
        [arr[candidates[0]], arr[candidates[1]]] = [arr[candidates[1]], arr[candidates[0]]];
    }

    return arr;
}

function isSolvable(arr) {
    let n = nbrPiecesPerLine * nbrPiecesPerLine;
    // arr[i] = ordre flex de la pièce i (i = position DOM, valeur = position visuelle)
    // On reconstitue le tableau visuel : visualArr[position visuelle - 1] = numéro de pièce (i+1)
    let visualArr = new Array(n);
    for (let i = 0; i < n; i++) {
        visualArr[arr[i] - 1] = i + 1;
    }

    let blankVisualPos = visualArr.indexOf(n); // position visuelle de la case vide
    let inversions = 0;
    for (let i = 0; i < n - 1; i++) {
        if (visualArr[i] === n) continue;
        for (let j = i + 1; j < n; j++) {
            if (visualArr[j] === n) continue;
            if (visualArr[i] > visualArr[j]) inversions++;
        }
    }

    let blankRowFromBottom = nbrPiecesPerLine - Math.floor(blankVisualPos / nbrPiecesPerLine);

    if (nbrPiecesPerLine % 2 === 1) {
        return inversions % 2 === 0;
    } else {
        return (inversions + blankRowFromBottom) % 2 === 0;
    }
}

function joue(evt) {
    let sonStyle = getComputedStyle(evt.target);
    if (pieceCliquable(stylePieceInvisible.order, sonStyle.order)) {
        let temporaryOrder = pieceInvisible.style.order;
        pieceInvisible.style.order = sonStyle.order;
        evt.target.style.order = temporaryOrder;
    }
    if (testIssue()) {
        endOfGame();
    };
};

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

function testIssue(){
    let nbrPiecesOrdonnees = 0;
    let lesPieces = document.getElementsByClassName("piece");
    let nbrPieces = lesPieces.length;
    for (let i = 0; i < nbrPieces; i++){
        let chaquePiece = lesPieces[i];
        let sonStyle = getComputedStyle(chaquePiece);
        if (sonStyle.order == i + 1){
            nbrPiecesOrdonnees += 1;
        }
        if (nbrPiecesOrdonnees > (nbrPiecesPerLine * nbrPiecesPerLine) - 2){
            return true;
        }
    }
}

function displayPiecesNumber(){
    let pieces = document.getElementsByClassName("piece");
    if (displayedNumero === false){
        for (let i = 0; i < pieces.length; i++){
            let piece = pieces[i]
            piece.textContent = i + 1;
        }
        displayedNumero = true;
    } else {
        for (let i = 0; i < pieces.length; i++){
            let piece = pieces[i]
            piece.textContent = " ";
        }
        displayedNumero = false;
    }
}

function endOfGame(){
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

function shareGame(){
    const url = `${location.origin}${location.pathname}?nbrPieces=${nbrPiecesTotal}&image=${encodeURIComponent(nomImage)}`;
    const shareData = {
        title: document.title,
        text: "J'ai réussi ce taquin, à toi de jouer !",
        url: url
    };
    // Web Share API sur mobile, clipboard en fallback
    if(navigator.share && navigator.canShare && navigator.canShare(shareData)){
        navigator.share(shareData).catch(() => {});
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


