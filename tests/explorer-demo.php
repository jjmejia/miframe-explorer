<?php
/**
 * Script para probar implementación de clase Explorer.php
 *
 * @author John Mejía
 * @since Julio 2022
 */

include __DIR__ . '/../src/explorer.php';

$explorer = new \miFrame\Utils\Explorer();

$explorer->setRoot('..');
$infopath = $explorer->getRoot();
$documento = $explorer->exploreHTML(basename(__FILE__));

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Test File Explorer</title>
	</head>
<body>

<style>
body {
	font-family: "Segoe UI",Helvetica,Arial,sans-serif;
	font-size: 14px;
	line-height: 1.5;
	word-wrap: break-word;
	/* padding:0 20px; */
	}
h1 {
    padding-bottom: .3em;
    font-size: 2em;
    border-bottom: 1px solid hsla(210,18%,87%,1);
	}
.x-explorer {
	background-color: #f4f4f4;
	padding:24px;
	border:1px solid #777;
}
</style>

<h1>Test DocSimple</h1>

<p>Uso:</p`>
<pre class="code">
	$explorer = new \miFrame\Utils\Explorer();
	$documento = $explorer->exploreHTML();
</pre>
<p>
	Explorar: <b><?= $infopath ?></b>
</p>

<?= $documento ?>

</body>
</html>
