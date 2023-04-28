<?php
/**
 * Clase para generar salida HTML a los datos generados por la clase Explorer.
 *
 * @micode-uses miframe/common/shared
 * @author John Mejia
 * @since Julio 2022
 */
namespace miFrame\Explorer;

class ExplorerHTML extends Explorer {

	use \miFrame\Traits\HTMLSupportTrait;

	private $baselink = '';

	public function __construct() {
		parent::__construct();
		// Para uso en HTMLSupportTrait
		$this->setFilenameCSS(__DIR__ . '/../public/explorer-styles.css');
	}

	/**
	 * Asigna valor para el enlace principal.
	 * A este enlace se suman los parámetros para navegación en línea.
	 */
	public function setBaseLink(string $baselink) {

		$this->baselink = trim($baselink);
		// Complementa el enlace base (aún si está en blanco)
		if (strpos($this->baselink, '?') !== false) { $this->baselink .= '&'; }
		else { $this->baselink .= '?'; }
	}

	/**
	 * Genera presentación del listado de archivos o contenido asociado a un archivo, en formato HTML.
	 *
	 * @return string HTML.
	 */
	public function render() {

		$listado = $this->explore();

		// Adiciona estilos en líne (si previamente no han sido incluidos)
		$salida = $this->getStylesCSS(true);

		$salida .= '<div class="x-explorer">';

		if (isset($listado['favorites']) && count($listado['favorites']) > 0) {
			// Listado de favoritos
			$salida .= '<div class="x-favorites">';
			foreach ($listado['favorites'] as $k => $info) {
				$target = '';
				$color = 'bi-star-indirect';
				if (!$info['indirect']) {
					// No es un direccionamiento indirecto
					$target = ' target="xfav_' . $k . '"';
					$color = 'bi-star-fill';
				}
				$salida .= '<div><i class="bi ' . $color . '"></i> ' .
					// Enlace para abrir favorito
					'<a href="' . $info['url'] . '"' . $target . '>' . $info['title'] . '</a>' .
					// Enlace para remover de la lista
					' <a href="' . $this->baselink . $info['rem'] . '" class="x-favlink" title="' . miframe_text('Retirar de favoritos') . '"><i class="bi bi-dash-circle"></i></a>' .
					'</div>';
			}
			$salida .= '</div>' . PHP_EOL;
		}

		$dir_superior = '';

		if (isset($listado['paths'])) {
			// Arma los paths asociados a la posición actual
			$salida .= '<p class="x-folder">';
			$primer_path = true;
			foreach ($listado['paths'] as $path => $enlace) {
				if ($path == '.') {
					// En este caso, $enlace no contiene información útil
					$dir_superior = substr($this->baselink, 0, -1);
					$salida .= "<a href=\"{$dir_superior}\" class=\"root\">" . miframe_text('Inicio') . "</a> ";
				}
				else {
					if ($enlace != '') {
						$dir_superior = $this->baselink . $enlace;
						$enlace_path = "<a href=\"{$dir_superior}\">{$path}</a>";
						if ($primer_path) {
							// El primer enlace lo resalta
							$salida .= '<b>' . $enlace_path . '</b>';
							$primer_path = false;
						}
						else {
							$salida .= $enlace_path;
						}
						$salida .= ' / ';
					}
					else {
						$salida .= "<b>{$path}</b>";
						if ($listado['type'] != 'file') {
							$salida .= ' / ';
						}
					}
				}
			}
			if ($listado['type'] == 'file' && $listado['add-fav'] != '') {
				// Adiciona enlace para adicionar a favoritos
				$salida .= ' <a href="' . $this->baselink . $listado['add-fav'] . '" class="x-favlink" title="' . miframe_text('Adicionar a favoritos') . '"><i class="bi bi-plus-circle"></i></a>';
			}
			$salida .= '</p>' . PHP_EOL;
		}

		$totaldirs = 0;
		$totalfiles = 0;
		$errores = $this->getError();

		if ($errores !== false) {
			// Hay errores reportados
			if ($errores['code'] == 3) {
				$detalles = $errores['details'];
				$salida .= "<b class=\"x-error\">" . miframe_text('Referencia no encontrada') . "</b>" .
							"<p class=\"x-info\">{$detalles['param']} = {$detalles['value']}</p>";
			}
			elseif ($errores['code'] == 4) {
				$salida .= "<b class=\"x-error\">" . miframe_text('No pudo guardar enlaces favoritos') . "</b>" .
							"<p class=\"x-info\">" . miframe_text('Ubicación del archivo: $1', $errores['favorites']) . "</p>";
			}
			else {
				$salida .= "<b class=\"x-error\">" . miframe_text('Ha ocurrido un error ($1)', $errores['code']) . "</b>" .
							"<div style=\"margin-top:10px\" class=\"x-info\">" .
							miframe_text('Los siguientes detalles están disponibles: $1', "<pre>" . htmlspecialchars(print_r($errores['details'], true)) . "</pre>") .
							"</div>";
			}
			$main_url = substr($this->baselink, 0, -1); // $errores['main-url']
			$salida .= "<a href=\"{$main_url}\">" . miframe_text('Volver al inicio') . "</a>";
		}
		elseif (isset($listado['type']) && $listado['type'] == 'file') {
			// Muestra contenido de archivo
			$salida .= '<div class="x-info">' .
				'<table><tr><td><b>' . miframe_text('Creado en') . ':</b></td><td>' . $listado['date-creation'] . '</td></tr>' .
				'<tr><td><b>' . miframe_text('Última modificación') . ':</b></td><td>' . $listado['date-modified'] . '</td></tr>' .
				'<tr><td><b>' . miframe_text('Tamaño') . ':</b></td><td>' . miframe_bytes2text($listado['size'], true) . '</td></tr></table>' .
				'</div>';
			$salida .= '<div class="x-' . $listado['class'] . '">' . $listado['content'] . '</div>';
		}
		else {
			// Directorio superior
			if ($dir_superior != '') {
				$salida .= '<div class="x-folder"><i class="bi bi-folder-fill"></i> ' .
				'<a href="' . $dir_superior . '"><b> . . </b></a>' .
				'</div>';
			}

			// Directorios y archivos
			if (isset($listado['dirs'])) {
				$totaldirs = count($listado['dirs']);
				foreach ($listado['dirs'] as $ufilename => $info) {
					// Listado de directorios
					$salida .= '<div class="x-folder"><i class="bi bi-folder-fill"></i> ' .
						'<a href="' . $this->baselink . $info['url-content'] . '"> ' . $info['name'] . '</a>' .
						'</div>';
				}
			}

			if (isset($listado['files'])) {
				$totalfiles = count($listado['files']);
				foreach ($listado['files'] as $ufilename => $info) {
					// Listado de archivos
					$enlace = $info['file'];
					if ($info['url-content'] != '') {
						// Enlace para visualizar contenido
						$enlace = '<a href="' . $this->baselink . $info['url-content'] . '">' . $enlace . '</a>';
					}
					if ($info['url'] != '') {
						// Enlace para ejecutar el archivo en el navegador (follow-link)
						$target = 'x-' . md5($info['url']);
						$enlace .= ' <a href="' . $info['url'] . '" class="x-favlink" title="' . miframe_text('Ejecutar') . '" target="' . $target . '"><i class="bi bi-box-arrow-up-right"></i></a>';
					}
					if ($info['add-fav'] != '') {
						$enlace .= ' <a href="' . $this->baselink . $info['add-fav'] . '" class="x-favlink" title="' . miframe_text('Adicionar a favoritos') . '"><i class="bi bi-plus-circle"></i></a>';
					}
					$file_class = 'bi-file';
					if ($info['in-fav']) {
						$file_class = 'bi-file-check';
					}
					elseif ($info['class'] != '') {
						$file_class = 'bi-file-' . $info['class'];
					}
					$salida .= '<div class="x-file"><i class="bi ' . $file_class . '"></i> ' .
								$enlace .
								'</div>';
				}
			}
		}

		$salida .= '</div>';

		// Total de elementos
		if ($totaldirs + $totalfiles > 0) {
			$salida .= '<div class="x-totales">' . miframe_text('Encontrados $1 elemento(s)', ($totaldirs + $totalfiles));
			$salida .= ': ';
			if ($totaldirs > 0) {
				$salida .= ' ' . miframe_text('$1 directorio(s)', $totaldirs);
			}
			if ($totalfiles > 0) {
				$salida .= ' ' . miframe_text('$1 archivo(s)', $totalfiles);
			}
			$salida .= '</div>';
		}

		return $salida;
	}
}