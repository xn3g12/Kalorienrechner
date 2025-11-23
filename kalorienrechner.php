<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITL1/2_Kaloreinrechner</title>
</head>
<body>
    <h1>Kaloreinrechner</h1>
    <!--GET Wird über die URL übertragen.-->
    <form action="kalorienrechner.php" method="GET">
        <label>Geschlecht:</label>      <!--Geschlecht angeben-->
        <select name="mann">
            <option value="mann">Mann</option>
            <option value="frau">Frau</option>
        </select><br><br>

    <label>Alter eingeben:</label>
    <input type="number" name="alter" required>

    <br><br><label>Größe eingeben:</label>
    <input type="number" name="größe" required>

    <br><br><label>Gewicht eingeben:</label>
    <input type="number" name="gewicht" required>
    <br><br><button type="submit">Berechnen</button>
</form>

<?php
// Werte aus mit dem GET holen
$mann = $_GET["mann"] ?? null;
$alter = $_GET["alter"] ?? null;
$größe = $_GET["größe"] ?? null;
$gewicht = $_GET["gewicht"] ?? null;
// if alle Werte vorhanden sind
if ($mann && $alter && $größe && $gewicht) {
    if ($mann == "mann") {
        // Männerformel
        $kalorien = 66.47 + (13.7 * $gewicht) + (5 * $größe) - (6.8 * $alter);
    } elseif ($mann == "frau") {
        // Frauenformel
        $kalorien = 655.1 + (9.6 * $gewicht) + (1.8 * $größe) - (4.7 * $alter);
    }
    echo "<h2>Dein Grundumsatz beträgt: <b>" . round($kalorien, 2) . " kcal</b></h2>";
}
?>
<h1>PAL Faktor am Tag </h1>
<form method="post">
    <label>Sitzend (die Stunden angeben):</label>
    <input type="number" name="sitzend" step="0.1"><br><br>

    <label>Büroarbeit (die Stunden angeben):</label>
    <input type="number" name="buero" step="0.1"><br><br>

    <label>Stehend/gehend (die Stunden angeben):</label>
    <input type="number" name="gehend" step="0.1"><br><br>

    <label>Ihr Grundumsatz (kcal):</label>
    <input type="number" name="grundumsatz" step="1"><br><br>

    <button type="submit">Berechnen</button>
</form>
<?php
// Prüfen ob POST-Daten vorhanden sind
if ($_POST) {
    // Eingaben holen oder auslesen
    $sitzend = floatval($_POST['sitzend']);
    $buero   = floatval($_POST['buero']);
    $gehend  = floatval($_POST['gehend']);
    $grundumsatz = floatval($_POST['grundumsatz']); // bereits vorher berechnet

    // PAL-Werte FIX
    $pal_sitzend = 1.2;
    $pal_buero   = 1.45;
    $pal_gehend  = 1.85;
    $pal_schlaf  = 0.95;

    // Gesamtstunden für Aktivitäten
    $aktiv_stunden = $sitzend + $buero + $gehend;

    // Schlafstunden = Rest vom Tag
    $schlaf = 24 - $aktiv_stunden;

    //wenn mehr als 24h angegeben wird soll diese ausgabe augegeben werden
    if ($schlaf < 0) {
        echo "Bitte nicht mehr als 24 Stunden eingeben!";
        exit;
    }

    //Durchschnitt berechnen
    $pal_durchschnitt =
        ($sitzend * $pal_sitzend +
         $buero   * $pal_buero +
         $gehend  * $pal_gehend +
         $schlaf  * $pal_schlaf) / 24;

    //Gesamtenergiebedarf
    $kalorienbedarf = $grundumsatz * $pal_durchschnitt;
    $abnehmen  = $erhaltung - 400;          // Abnehmen
    $zunehmen  = $erhaltung + 400;          // Zunehmen

    //Ausgaben 
    echo "<h3>Ergebnis:</h3>";
    echo "Schlafstunden: $schlaf h<br>";
    echo "PAL-Durchschnitt: " . round($pal_durchschnitt, 2) . "<br>";   //.ound rundet den zahl
    echo "Täglicher Kalorienbedarf: " . round($kalorienbedarf) . " kcal<br>";
    echo "<strong>Zum Abnehmen (–400 kcal):</strong> " . round($abnehmen) . " kcal<br>";
    echo "<strong>Zum Zunehmen (+400 kcal):</strong> " . round($zunehmen) . " kcal<br>";
}
?>
</body>
</html>
