<!DOCTYPE html>
<html>
	<head>

		<meta charset="utf-8">
		<title>Dump Examples</title>
		
		<link rel="stylesheet" href="../src/css/dump.css">

		<style type="text/css">

			/* CSS Reset */

			html, body, div, span, p, img, a, center, ul, ol, li, h1, h2, h3, h4, h5, h6, blockquote, fieldset, legend, form, textarea, tr, th, td, tt, hr, thead, tbody, tfoot, select, option, pre {
				border: 0 none;
				font: inherit;
				margin: 0;
				padding: 0;
				vertical-align: baseline;
				color: inherit;
			}
			table {
				border-collapse: collapse;
				border-spacing: 0;
			}
			input {
				font: inherit;
				margin: 0;
				padding: 0;
				vertical-align: baseline;
				color: inherit;
			}
			h1, h2, h3, h4, h5, h6, th, td {
				font-size: 100%;
				font-weight: normal;
				text-align: inherit;
			}
			ul, ol {
				list-style: none;
			}
			a {
				text-decoration: none;
				cursor: pointer;
			}

			/* CSS formatting */

			body {
				background-color: #f3ebf5;
				font-family: "Lucida Grande", Verdana, Arial, sans-serif;
				font-size: 14px;
				line-height: 26px;
				color: #777777;
				padding-bottom: 50px;
			}
			#container {
				width: 1100px;
				margin: 0 auto;
			}
			.left {
				width: 540px;
				float: left;
			}
			.right {
				width: 540px;
				float: right;
			}
			.align-center {
				text-align: center;
			}
			.clear {
				clear: both;
			}
			pre.var_dump {
				margin: 0 auto;
				padding: 5px;
				background-color: #f6f6f6;
				border: 1px dotted #d2d2d2;
				font-size: 13px;
				color: #666666;
				overflow-y: auto;
			}
			pre.dump, pre.var_dump {
				margin-top: 20px;
			}
			h1 {
				text-align: center;
				font-size: 42px;
				margin: 1.4em auto 1.4em;
				color: #444444;
			}
			h2 {
				text-align: center;
				font-size: 26px;
				margin-top: 1.4em;
				color: #444444;
			}
			h3 {
				text-align: center;
				font-size: 22px;
				margin-top: 1em;
				color: #444444;
			}
			p {
				margin-top: 10px;
				color: #555555;
				line-height: 20px;
				font-size: 14px;
			}
			a {
				color: #dd4444;
			}
			a:hover {
				color: #aa3333;
			}
		</style>

	</head>
	
	
	<body>
		<div id="container">

			<?php

			require '../src/lib/dump.php';


			// Classe basique pour le test d'expression complet
			Class Color {

				public static $representation = 'RGBA';
				public $label;
				private $red;
				private $green;
				private $blue;
				private $alpha; // 0 = Opaque, 255 = Transparent

				public function __construct($l = 'black', $r = 0, $g = 0, $b = 0, $a = 0) {
					$this->label = $l;
					$this->red = $r;
					$this->green = $g;
					$this->blue = $b;
					$this->alpha = $b;
				}
			}

			$expression = array(
				'colors' => array(
					'rgbaArray' => array(
						'red' => array('red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0),
						'plum' => array('red' => 221, 'green' => 160, 'blue' => 221, 'alpha' => 0),
						'black' => array('red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 0)
					),
					'hexaString' => array(
						'red' => '#ff0000',
						'plum' => '#dda0dd',
						'black' => '#000000'
					),
					'rgbaClass' => array(
						'color1' => new Color('red', 255, 0, 0),
						'color2' => new Color('plum', 221, 160, 221),
						'color3' => new Color()
					)
				)
			);


			// Double classe pour le test de boucle infini (récursion des deux classe s'incluant l'une l'autre)
			class Recursive1 {

				public $Recursive2;

			}

			class Recursive2 {
			
				public $Recursive1;

			}

			$Recursive1 = new Recursive1();
			$Recursive2 = new Recursive2();

			$Recursive1->Recursive2 = $Recursive2;
			$Recursive2->Recursive1 = $Recursive1;

			?>


			<!-- ---------------------------------------------------------------------------- -->
			<!-- TITRE -->

			<a href="https://github.com/Julien-Marcou/Dump">
				<img style="position: absolute; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_darkblue_121621.png" alt="Fork me on GitHub">
			</a>
			<h1>dump() <strong><i>VS</i></strong> &lt;pre&gt;var_dump()&lt;/pre&gt;</h1>



			<!-- ---------------------------------------------------------------------------- -->
			<!-- Comparaison pour les types -->

			<h2>Les différents types</h2>
			<div class="left">
				<?php
					dump(
						'Hello World !',
						39,
						3.14,
						null,
						false,
						true,
						array(),
						new stdClass()
					);
				?>
			</div>
			<div class="right">
				<?php
					echo '<pre class="var_dump">';
					var_dump(
						'Hello World !',
						39,
						3.14,
						null,
						false,
						true,
						array(),
						new stdClass()
					);
					echo '</pre>';
				?>
			</div>
			<div class="clear"></div>



			<!-- ---------------------------------------------------------------------------- -->
			<!-- Comparaison pour un tableau simple -->

			<h2>Un tableau classique</h2>
			<div class="left">
				<?php
					dump(
						array(
							null,
							0,
							false,
							''
						)
					);
				?>
			</div>
			<div class="right">
				<?php
					echo '<pre class="var_dump">';
					var_dump(
						array(
							null,
							0,
							false,
							''
						)
					);
					echo '</pre>';
				?>
			</div>
			<div class="clear"></div>



			<!-- ---------------------------------------------------------------------------- -->
			<!-- Comparaison avancée pour les chaines de caractères -->

			<h2>Chaines de caractères</h2>
			<div class="left">
				<?php
					dump(
						'<script>console.log("Dump log");</script>',
						'<?php echo "Dump echo"; ?>',
						'<p><strong>Dump</strong><br><u>HTML</u></p>',
						'Chaine de plus de 255 caractère de long (tronquée, allez voir)... Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
					);
				?>
				<p>
					dump() n'exécute pas le code et l'affichera sans problème,
					de plus, les chaines de plus de 255 caractères seront tronquées
					à 255 caractères pour l'affichage (paramétrable).
				</p>
			</div>
			<div class="right">
				<?php
					echo '<pre class="var_dump">';
					var_dump(
						'<script>console.log("var_dump log");</script>',
						'<?php echo "Var_dump echo"; ?>',
						'<p><strong>Var_dump</strong><br><u>HTML</u></p>',
						'Chaine de plus de 255 caractère de long (non tronquée, n\'allez pas voir)... Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
					);
					echo '</pre>';
				?>
				<p>
					var_dump() n'affichera aucun code, executera le JavaScript (regardez dans votre console)
					mais pas le PHP et mettra en forme l'HTML.
				</p>
			</div>
			<div class="clear"></div>



			<!-- ---------------------------------------------------------------------------- -->
			<!-- Comparaison d'une expression avancée -->

			<h2>Expression avancée</h2>
			<p class="align-center">
				Exemple sur différentes représentations possibles pour les couleurs
				<span style="color: red;">red</span>, <span style="color: plum;">plum</span> et <span style="color: black;">black</span>.<br>
				Tableau RGBA, chaine Hexadécimale et classe RGBA.
			</p>
			<div class="left">
				<?php
					dump($expression);
				?>
			</div>
			<div class="right">
				<?php
					echo '<pre class="var_dump">';
					var_dump($expression);
					echo '</pre>';
				?>
			</div>
			<div class="clear"></div>



			<!-- ---------------------------------------------------------------------------- -->
			<!-- Comparaison sur la récurrence -->

			<h2>Récursion infinie</h2>
			<p class="align-center">
				Exemple d'affiche d'une instance de la classe "Recursive1"
				dont la seul propriété fait référence à une instance de la classe "Recursive2".<br>
				Avec la classe "Recursive2" ayant pour seul propriété l'instance de classe créée pour "Recursive1".
			</p>
			<div class="left">
				<?php
					dump($Recursive1);
				?>
			</div>
			<div class="right">
				<?php
					echo '<pre class="var_dump">';
					var_dump($Recursive1);
					echo '</pre>';
				?>
			</div>
			<div class="clear"></div>

		</div>				
	</body>
</html>
