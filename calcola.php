<?php
// Controllo di sicurezza lato server: se l'utente tenta di accedere al file senza inviare il form via POST, lo reindirizza alla pagina iniziale index.html
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit();
}

// Estrazione dei valori dai campi del form recepiti tramite l'array superglobale $_POST
// htmlspecialchars() rimuove possibili caratteri dannosi (prevenzione attacchi XSS); trim() elimina spazi vuoti
$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
// Conversione esplicita a numero float dei valori numerici
$weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
$heightCm = isset($_POST['height']) ? floatval($_POST['height']) : 0;

// Validazione lato server: se il nome è vuoto o peso/altezza sono minori o uguali a 0, interrompe l'esecuzione
if (empty($name) || $weight <= 0 || $heightCm <= 0) {
    die("Dati non validi inseriti. Torna indietro e riprova.");
}

// Conversione dell'altezza da centimetri a metri e calcolo matematico del BMI
$heightM = $heightCm / 100;
$bmiCalculated = $weight / ($heightM * $heightM);
// Arrotondamento del risultato a 1 sola cifra decimale tramite number_format()
$bmi = number_format($bmiCalculated, 1);

// Determinazione della categoria di appartenenza e assegnazione del relativo colore visivo
if ($bmiCalculated < 18.5) {
    $category = 'Sottopeso';
    $categoryColor = '#b45309'; // Colore arancione scuro/ambra
} elseif ($bmiCalculated >= 18.5 && $bmiCalculated < 25) {
    $category = 'Normopeso';
    $categoryColor = '#15803d'; // Colore verde
} elseif ($bmiCalculated >= 25 && $bmiCalculated < 30) {
    $category = 'Sovrappeso';
    $categoryColor = '#b45309'; // Colore arancione scuro/ambra
} else {
    $category = 'Obesità';
    $categoryColor = '#b91c1c'; // Colore rosso
}
?>
<!DOCTYPE html>
<!-- Documento HTML restituito dal server con il risultato del calcolo -->
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato BMI</title>
    <!-- Tailwind CSS CDN per gestione stili moderna e responsive -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Importazione dello stesso foglio di stile esterno 'style.css' -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Contenitore unico centrato a singola colonna -->
    <main class="flex flex-col items-center justify-center w-full">
        
        <!-- Card Principale -->
        <div class="bmi-card">
            
            <!-- Sezione Risultato (Sempre visibile in calcola.php dopo l'invio) -->
            <div id="result" class="result-box" style="display: block;">
                <!-- Messaggio con il nome dell'utente -->
                <p id="greetingText" class="text-xs uppercase font-bold tracking-wider text-gray-500 mb-1">
                    Risultato per <?php echo $name; ?>
                </p>
                <!-- Stampa del valore del BMI calcolato dal server -->
                <div class="text-3xl font-extrabold my-1 text-slate-800" id="bmiValue">
                    <?php echo $bmi; ?>
                </div>
                <!-- Stampa della categoria con il colore dinamicamente impostato da PHP -->
                <div id="bmiCategory" class="category-text text-sm" style="color: <?php echo $categoryColor; ?>;">
                    <?php echo $category; ?>
                </div>
            </div>

            <!-- Pulsante per tornare al modulo e fare un nuovo calcolo -->
            <a href="index.html" class="back-btn">← Calcola di nuovo</a>

        </div>

    </main>

</body>
</html>