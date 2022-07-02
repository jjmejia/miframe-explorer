# miframe-explorer

Clase para explorar directorios y consultar archivos en línea.

Permite declarar enlaces favoritos para accesos rápidos, que son almacenados en el archivo "favoritos.ini", por defecto generado en el directorio raíz (requiere permisos de escritura).

La navegación en línea se realiza mediante el uso de los parámetros post:

- dir: Path a explorar (descendiente del directorio raíz)
- addfav: URL a adicionar a favoritos.
- remfav: URL a remover de favoritos.

Se puede generar toda la navegación usando los estilos propietarios o pueden ser personalizados.

Uso:

    $explorer = new \miFrame\Utils\Explorer();
    echo $doc->exploreHTML();

Puede mejorar la interpretación del contenido de archivos con formato Markdown, usando una librería externa para tal fin. Por ejemplo:

    $parser = new Parsedown();
    $explorer->parserTextFunction = array($parser, 'text');

Puede consultar y descargar la librería *Parsedown* de [https://github.com/erusev/parsedown/](https://github.com/erusev/parsedown/).

## Class miFrame\Utils\Explorer

Las siguientes propiedades públicas pueden ser usadas:

* `$fileFavorites`: Path del archivo "favoritos.ini" (por defecto se almacena en el directorio raíz).
* `$useFavoritos`: TRUE para habilitar las opciones de Favoritos, esto es, visualizar en la navegación y actualizar archivo .ini.
* `$parserTextFunction`: Función a usar para interpretar el texto (asumiendo formato Markdown). Retorna texto HTML. Ej:

    function (text) { ... return $html; }

* `$stylesCSS`: string. Estilos CSS a usar. Si emplea un archivo externo, use: "url:(path)".
* `$styles_ignore`: boolean. Indica si debe ignorar estilos internos (automáticamente se fija a TRUE luego de imprimir estilos).
* `$showContentsFor`: array. Extensiones para las que se muestra el contenido. Por defecto se habilita para las siguientes extensiones: 'txt', 'jpg', 'jpeg', 'gif', 'svg', 'pdf', 'ico', 'png', 'md', 'ini', 'json'.

Métodos relevantes:

* `explore` -- Recupera el listado de archivos o contenido asociado a un archivo.
* `exploreHTML` -- Recupera el listado de archivos o contenido asociado a un archivo, en formato HTML.
* `getRoot` -- Retorna el Path real usado como directorio raíz.
* `setRoot` -- Define el directorio raíz.

## Demo

El script [tests/explorer-demo.php](https://github.com/jjmejia/miframe-explorer/blob/main/tests/explorer-demo.php) contiene una demostración completa de la funcionalidad de esta clase.

## Importante!

Los iconos SVG usados han sido tomados de la página [Bootstrap Icons](https://icons.getbootstrap.com/).

Esta librería forma parte de los módulos PHP incluidos en [micode-manager](https://github.com/jjmejia/micode-manager).