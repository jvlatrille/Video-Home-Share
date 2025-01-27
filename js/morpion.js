// Bloquer la page à 2 personnes avec code d'acces ou ID de session ou autre
const cells = document.querySelectorAll('.cell');
const message = document.getElementById('message');
const restartButton = document.getElementById('restart');

let board = ['', '', '', '', '', '', '', '', ''];
let currentPlayer = 'X'; // Ici je peux définir le pseudo du joueur avec les variables de sessions php
let isGameActive = true;

const winningCombinations = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6]
];

function handleCellClick(e) {
    const cellIndex = e.target.getAttribute('data-index');

    if (board[cellIndex] || !isGameActive) {
        return;
    }

    board[cellIndex] = currentPlayer;
    e.target.textContent = currentPlayer;

    if (checkWinner()) {
        message.textContent = `${currentPlayer} gagne!`;
        isGameActive = false;
        restartButton.classList.remove('hidden');
    } else if (board.every(cell => cell !== '')) {
        message.textContent = "Match nul!";
        isGameActive = false;
        restartButton.classList.remove('hidden');
    } else {
        currentPlayer = currentPlayer === 'X' ? 'O' : 'X'; // Change de joueur
        message.textContent = `C'est au tour de ${currentPlayer}`;
    }
}

// Créer une fonction checkPlayer pour savoir qui doit jouer

function checkWinner() {
    return winningCombinations.some(combination => {
        const [a, b, c] = combination;
        return board[a] && board[a] === board[b] && board[a] === board[c];
    });
}

function restartGame() { // Il faut attendre les deux joueurs pour relancer
    board = ['', '', '', '', '', '', '', '', ''];
    isGameActive = true;
    currentPlayer = 'X';
    message.textContent = `C'est au tour de ${currentPlayer}`;
    cells.forEach(cell => cell.textContent = '');
    restartButton.classList.add('hidden');
}

cells.forEach(cell => {
    cell.addEventListener('click', handleCellClick);
});

restartButton.addEventListener('click', restartGame);

message.textContent = `C'est au tour de ${currentPlayer}`;
