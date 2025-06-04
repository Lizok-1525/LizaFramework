const config = {
    type: Phaser.AUTO,
    width: 400,
    height: 700,
    backgroundColor: "#56ca0b",
    physics: {
        default: "arcade",
        arcade: {
            gravity: { y: 0 }, // Empezamos sin gravedad
            debug: false,
        },
    },
    scene: {
        preload,
        create,
        update,
    },
};
const game = new Phaser.Game(config);
let bee;
let cursors;
let mielGroup;
let spiderGroup;
let score = 0;
let highScore = localStorage.getItem("highScore") || 0;
let totalMiel = parseInt(localStorage.getItem("totalMiel")) || 0;
let totalMielText;
let scoreText;
let startButton, titleText;
let soundOnButton, soundOffButton;
let timerText;
let gameStarted = false;
let gameOver = false;
let endText;
let restartButton;
let backToMenuButton;
let musicEnabled = false;
let backgroundMusic;
function preload() {
    this.load.image("bee", "https://lizok1525.files.show/bee-removebg-preview.png");
    this.load.image("miel", "https://lizok1525.files.show/miel-removebg-preview.png");
    this.load.image("spider", "https://lizok1525.files.show/araña.jpg");
    this.load.image("boton", "https://lizok1525.files.show/jugar1.png");
    this.load.image("boton2", "https://lizok1525.files.show/jugar2.png")
    this.load.image("logo", "https://lizok1525.files.show/logobeerush.png")
    this.load.audio("musicaFondo", "https://lizok1525.files.show/castle_stage.mp3");
    this.load.image("soundon", "https://lizok1525.files.show/soundon.png");
    this.load.image("soundoff", "https://lizok1525.files.show/soundoff.png");
}
function create() {
    score = 0;
    gameStarted = false;
    gameOver = false;
    bee = null;
    titleText = this.physics.add.sprite(190, 100, "logo").setScale(0.5);
    bee = this.physics.add.sprite(200, 570, "bee").setScale(0.4);
    bee.setCollideWorldBounds(true);
    bee.body.allowGravity = false;
    bee.body.moves = false;
    cursors = this.input.keyboard.createCursorKeys();
    startButton = this.add.sprite(200, 350, "boton").setScale(0.7)
        .setInteractive({ useHandCursor: true });
    mielGroup = this.physics.add.group();
    spiderGroup = this.physics.add.group();
    scoreText = this.add.text(10, 10, "Puntos: 0", {
        fontSize: "20px",
        fill: "white",
    }).setVisible(false);
    timerText = this.add.text(250, 10, "Tiempo: 60", {
        fontSize: "20px",
        fill: "white",
    });
    timerText.setVisible(false); // Lo mostramos solo cuando empiece el juego
    totalMielText = this.add.text(10, 200, "Miel total: " + totalMiel, {
        fontSize: "20px",
        fill: "white",
    });
    soundOnButton = this.add.sprite(50, 650, "soundon").setScale(0.5).setInteractive();
    soundOffButton = this.add.sprite(100, 650, "soundoff").setScale(0.5).setInteractive();
    soundOnButton.on("pointerdown", () => {
        musicEnabled = true;
        soundOnButton.setAlpha(1);
        soundOffButton.setAlpha(0.5);
    });
    soundOffButton.on("pointerdown", () => {
        musicEnabled = false;
        soundOnButton.setAlpha(0.5);
        soundOffButton.setAlpha(1);
    });
    // Al hacer clic en "Empezar"
    startButton.on("pointerdown", () => {
        // Cambiar imagen del botón
        startButton.setTexture("boton2");
        // Esperar un poquito antes de empezar el juego (por ejemplo, 500ms)
        this.time.delayedCall(200, () => {
            startGame.call(this);
        });
    })
}
function startGame() {
    // Cambiar estado del juego
    gameStarted = true;
    this.time.delayedCall(60000, () => {
        endGame.call(this);
    });
    // Activar físicas
    this.physics.world.gravity.y = 200;
    bee.body.allowGravity = true;
    bee.body.moves = true;
    // Ocultar pantalla de inicio
    startButton.setVisible(false);
    titleText.setVisible(false);
    totalMielText.setVisible(false);
    soundOnButton.setVisible(false);
    soundOffButton.setVisible(false);
    scoreText.setVisible(true);
    timerText.setVisible(true);
    if (musicEnabled) {
        backgroundMusic = this.sound.add("musicaFondo", {
            loop: true,
            volume: 0.3
        });
        backgroundMusic.play();
    }
    let tiempoRestante = 60;
    this.tiempoEvento = this.time.addEvent({
        delay: 1000,
        callback: () => {
            tiempoRestante--;
            timerText.setText("Tiempo: " + tiempoRestante);
            if (tiempoRestante <= 0) {
                endGame.call(this);
            }
        },
        loop: true,
    });
    this.spawnEvent = this.time.addEvent({
        delay: 800,
        callback: dropItem,
        callbackScope: this,
        loop: true,
    });
    // Movimiento táctil: mover la abeja con el dedo
    this.input.on("pointerdown", (pointer) => {
        if (bee) {
            bee.x = pointer.x;
        }
    });
    this.input.on("pointermove", (pointer) => {
        if (pointer.isDown && bee) {
            bee.x = pointer.x;
        }
    });
    // Activar colisiones
    this.physics.add.overlap(bee, mielGroup, collectMiel, null, this);
    this.physics.add.overlap(bee, spiderGroup, hitSpider, null, this);
}
function update() {
    if (!gameStarted || !bee || !bee.body) return;
    bee.setVelocityX(0);
    if (cursors.left.isDown) {
        bee.setVelocityX(-200);
    } else if (cursors.right.isDown) {
        bee.setVelocityX(200);
    }
}
function dropItem() {
    const x = Phaser.Math.Between(50, 350);
    const isMiel = Math.random() < 0.4;
    if (isMiel) {
        const miel = mielGroup.create(x, 0, "miel").setScale(0.3);
        miel.setVelocityY(200);
    } else {
        const spider = spiderGroup.create(x, 0, "spider").setScale(0.3);
        spider.setVelocityY(200);
    }
}
function collectMiel(bee, miel) {
    miel.destroy();
    score += 10;
    scoreText.setText("Puntos: " + score);
}
function hitSpider(bee, spider) {
    if (gameOver) return;
    gameOver = true;
    this.physics.pause();
    bee.setTint(0xff0000);
    this.spawnEvent?.remove(false);
    this.tiempoEvento?.remove(false);
    if (backgroundMusic && backgroundMusic.isPlaying) {
        backgroundMusic.stop();
    }
    // 🧹 Eliminar colisiones y enemigos
    //this.physics.world.colliders.destroy(); // elimina todos los overlaps
    mielGroup.clear(true, true);
    spiderGroup.clear(true, true);
    bee.destroy();
    bee = null;
    scoreText.setText("¡Has perdido!");
    totalMiel += score;
    localStorage.setItem("totalMiel", totalMiel);
    showEndScreen.call(this, "¡Has perdido!", "#ff0000");
}

function endGame() {
    if (gameOver) return;
    gameOver = true;
    this.physics.pause();
    bee?.setTint(0x00ff00);
    this.spawnEvent?.remove(false);
    this.tiempoEvento?.remove(false);
    if (backgroundMusic && backgroundMusic.isPlaying) {
        backgroundMusic.stop();
    }
    // 🧹 Eliminar colisiones y enemigos
    //this.physics.world.colliders.destroy();
    mielGroup.clear(true, true);
    spiderGroup.clear(true, true);
    bee?.destroy();
    bee = null;
    scoreText.setText("Puntos: " + score);
    totalMiel += score;
    localStorage.setItem("totalMiel", totalMiel);
    showEndScreen.call(this, "¡Ganaste! Miel: " + score, "#00ff00");
}


function showEndScreen(message, color = "#fff") {
    // Actualiza récord si es necesario
    if (score > highScore) {
        highScore = score;
        localStorage.setItem("highScore", highScore);
    }
    endText = this.add.text(200, 280, message, {
        fontSize: "32px",
        fill: color,
        fontFamily: "Arial",
        backgroundColor: "#000",
        padding: { x: 10, y: 10 },
    }).setOrigin(0.5);

    // Mostrar récord
    this.add.text(200, 330, "Récord: " + highScore, {
        fontSize: "20px",
        fill: "#fff",
        backgroundColor: "#333",
        padding: { x: 8, y: 5 },
    }).setOrigin(0.5);
    restartButton = this.add.text(200, 400, "Reintentar", {
        fontSize: "24px",
        fill: "#000",
        backgroundColor: "#fff",
        padding: { x: 15, y: 10 },
    }).setOrigin(0.5).setInteractive();

    backToMenuButton = this.add.text(200, 460, "Volver al inicio", {
        fontSize: "20px",
        fill: "#000",
        backgroundColor: "#fff",
        padding: { x: 10, y: 8 },
    }).setOrigin(0.5).setInteractive();
    restartButton.on("pointerdown", () => {
        this.scene.restart();
    });
    backToMenuButton.on("pointerdown", () => {
        gameOver = false;
        gameStarted = false;
        score = 0;
        this.scene.restart();
    });
}
