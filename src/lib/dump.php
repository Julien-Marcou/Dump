<?php
/**
 * PHP var_dump() like function
 *
 * Permet d'afficher une ou plusieurs variables et d'afficher leur contenu de manière récursive et structurée.
 * Semblable à var_dump() avec une mise en page plus avancée.
 *
 * @param mixed $expression La variable que vous voulez afficher.
 * @param mixed $... Autres variables que vous voulez afficher...
 * @return void
 */
function dump() {
	$args = func_get_args();
	$dump = array();

	foreach($args as $arg) {
		$dump[] = _dump($arg);
	}

	echo '<pre class="dump">'.implode("\n", $dump).'</pre>';
}


/**
 * Recursive function for dump()
 * 
 * Méthode de récursivité appelée par dump().
 * Cette fonction retourne une variable structurée pour l'affichage.
 *
 * @param mixed $var Variable à structurer pour l'affichage.
 * @param int $iterator Nombre d'indentation de départ.
 * @param array $references Tableau des références déjà parcourues pour éviter des boucles infinies.
 * @return string La variable $var après structuration pour l'affichage.
 */
function _dump($var, $iterator = 0, $references = array()) {

	// INITIALISATION

	// Longueur maximal d'affichage d'une chaine (au delà la chaine est tronqué, mais la vrai longueur est affichée)
	$maxStrLength = 255;

	// Représentation d'un tabulation
	//$tabulation = '<span class="indentation">|</span>    ';
	$tabulation = '    ';

	// Décalage des parenthèse ouvrante et fermante par rapport l'indentation courante
	$shift = '';

	// Représentation d'un saut de ligne
	$br = "\n";

	// Balises HTML englobantes pour le mise en page
	$beforeAccess = '<em class="access">';
	$afterAccess = '</em>';
	$beforeVar = '<strong class="var">';
	$afterVar = '</strong>';
	$equal = '<span class="equal">=></span>';
	$beforeType = '<span class="type">';
	$afterType = '</span>';
	$beforeBlock = '<span class="bracket">(</span>';
	$afterBlock = '<span class="bracket">)</span>';

	// Caractères englobants 
	$strQuoteBefore = "'";
	$strQuoteAfter = "'";

	// Utilisé pour montrer que la chaine a été tronquée
	$hellip = '<span class="hellip">[<span class="hellip">&hellip;</span>]</span>';


	// MISE EN FORME

	// Variable tempon de retour
	$output = array();

	// Indentation courante
	$indentation = '';

	// On commence par créer l'indentation
	for($i = 0; $i < $iterator; $i++){
		$indentation .= $tabulation;
	}

	// Type null
	if($var === null) {
		$output[] = '<span class="value null">Null</span>';
	}

	// Type boolean
	else if(is_bool($var)) {
		$output[] = $beforeType.'boolean'.$afterType.' <span class="value bool">'.($var ? 'True' : 'False').'</span>';
	}

	// Type integer
	else if(is_int($var)) {
		$output[] = $beforeType.'int'.$afterType.' <span class="value int">'.$var.'</span>';
	}

	// Type float
	else if(is_float($var)) {
		$output[] = $beforeType.'float'.$afterType.' <span class="value float">'.$var.'</span>';
	}

	// Type string
	else if(is_string($var)) {
		// Chaine plus longue que la valeur autorisée
		if(strlen($var) > $maxStrLength) {
			// On condense la chaine pour l'affichage
			$search = array(
				'#[ \t]+[\r\n]#' => "", // leading whitespace after line end
				'#[\n\r]+#' => "\n", // multiple newlines
				'# {2,}#' => " ", // multiple spaces
				'#\t{2,}#' => "\t", // multiple tabs
				'#\t | \t#' => "\t" // tabs and spaces together
			);

			$str = preg_replace(array_keys($search), $search, trim($var));
			$str = htmlspecialchars(substr($str, 0, $maxStrLength), ENT_NOQUOTES);
			$str = $str.$hellip;
		}
		else {
			$str = htmlspecialchars($var, 0);
		}
		$output[] = $beforeType.'string'.$afterType.' <span class="value string">'.$strQuoteBefore.$str.$strQuoteAfter.'</span> <span class="info-string">(length='.strlen($var).')</span>';
	}

	// Type array - récursivité
	else if(is_array($var)) {
		$output[] = '<span class="info-type array">Array</span>';
		$output[] = $indentation.$shift.$beforeBlock;

		if(empty($var)) {
			$output[] = $indentation.$tabulation.'<span class="value empty">Empty</span>';
		}
		else {
			foreach($var as $key=>$value) {
				// Pour masquer la valeur d'un tableau indexée par "password" pour éviter l'affichage de données privées par mégarde
				/*if(is_string($key) && $key == 'password') {
					$output[] = $indentation.$tabulation.$beforeVar.'['.$key.']'.$afterVar.' '.$equal.' Private content [Censored]';
				}
				else {*/
					$output[] = $indentation.$tabulation.$beforeVar.'['.$key.']'.$afterVar.' '.$equal.' '._dump($value, $iterator+1, $references);
				//}
			}
		}

		$output[] = $indentation.$shift.$afterBlock;
	}

	// Type object (class) - récursivité
	else if(is_object($var)) {

		$output[] = $beforeType.get_class($var).$afterType.' <span class="info-type object">Object</span>';
		$output[] = $indentation.$shift.$beforeBlock;

		// On récupère la référence
		$reference = &$var;

		// Si la référence existe déjà, on stop
		if(in_array($reference, $references)) {
			$output[] = $indentation.$tabulation.'Recursive reference [Stopped]';
		}
		else {
			// Ajout de la référence, pour éviter une boucle infini entre 2 classes se faisant référence l'une l'autre
			$references[] = $reference;

			// Pour masquer les données d'une class qui s'appellerait "Config" pour éviter l'affichage de données privées par mégarde
			/*if(get_class($var) == 'Config') {
				$output[] = $indentation.$tabulation.'Private content [Censored]';
			}
			else {*/
				// On récupère une réflection de la classe
				$reflection = new ReflectionClass($var);

				// On récupère les propriétés de tout types
				$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED);

				// S'il n'y a aucune propriété
				if(empty($properties)) {
					$output[] = $indentation.$tabulation.'<span class="value empty">Empty</span>';
				}
				else {
					// On parcours les propriétés
					foreach($properties as $property) {

						if($property->isPrivate()) {
							$property->setAccessible(true);
							$access = 'private';
						}
						else if($property->isProtected()) {
							$property->setAccessible(true);
							$access = 'protected';
						}
						else {
							$access = 'public';
						}

						if($property->isStatic()) {
							$access .= ' static';
						}

						$value = $property->getValue($var);
						$key = $property->getName();
						$_key = $beforeVar.$key.$afterVar;
						$access = $beforeAccess.$access.$afterAccess;

						// Pour masquer la propriété d'une classe nommée "password" pour éviter l'affichage de données privées par mégarde
						/*if(is_string($key) && $key == 'password') {
							$output[] = $indentation.$tabulation.$access.' '.$_key.' '.$equal.' Private content [Censored]';
						}
						else {*/
							$output[] = $indentation.$tabulation.$access.' '.$_key.' '.$equal.' '._dump($value, $iterator+1, $references);
						//}
					}
				}
			//}
		}

		$output[] = $indentation.$shift.$afterBlock;
	}

	// Tous les autres types, ne devrait jamais se produire
	else {
		$output[] = gettype($var).' '.htmlspecialchars(var_export($var, true), ENT_NOQUOTES);
	}

	return implode($br, $output);
}
