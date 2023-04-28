# miframe-explorer

Clase para explorar directorios y consultar archivos en línea.

Permite declarar enlaces favoritos para accesos rápidos, que son almacenados en el archivo "favoritos.ini", por
defecto generado en el directorio raíz (requiere permisos de escritura).

La navegación en línea se realiza mediante el uso de los parámetros post:

- dir: Path a explorar (descendiente del directorio raíz)
- favadd: URL a adicionar a favoritos.
- favrem: URL a remover de favoritos.
- file: Nombre del archivo a mostrar información del contenido (según el tipo de archivo).

Se puede generar toda la navegación usando los estilos propietarios o pueden ser personalizados.

Uso:

    $explorer = new \miFrame\Utils\Explorer();
    $files = $explorer->explore();

Se provee igualmente una clase para su visualización y uso directo en la pantalla del navegador, que puede
ser usada como guía para desarrollar su propia librería de presentación. Puede usarse así:

    $explorer = new \miFrame\Utils\ExplorerHTML();
    echo $explorer->render();

También puede mejorar la visualización del contenido de archivos con formato Markdown, relacionando una librería
externa para tal fin. El siguiente ejemplo, relaciona la librería *Parsedown* (que puede descargar desde [https://github.com/erusev/parsedown/](https://github.com/erusev/parsedown/)):

    $parser = new Parsedown();
    $explorer->parserTextFunction = array($parser, 'text');
    echo $explorer->render();

## Class miFrame\Explorer\Explorer

Las siguientes propiedades públicas pueden ser usadas:

* `$baselink`: Enlace principal. A este enlace se suman los parámetros para navegación en línea
* `$useFavoritos`: TRUE para habilitar las opciones de Favoritos, esto es, visualizar en la navegación y actualizar archivo .ini.
* `$parserTextFunction`: Función a usar para interpretar el texto (asumiendo formato Markdown). Retorna texto HTML. Ej:

    function (text) { ... return $html; }

Métodos relevantes:

* `explore` -- Recupera el listado de archivos o contenido asociado a un archivo.
* `getRoot` -- Retorna el Path real usado como directorio raíz.
* `setRoot` -- Asigna el directorio raíz (sólo muestra directorios y archivos contenidos en este directorio).


## Class miFrame\Explorer\ExplorerHTML

Métodos relevantes:

* `setBaseLink` -- Enlace principal. A este enlace se suman los parámetros para navegación en línea.
* `setFilenameCSS`-- Asigna la ubicación del archivo de estilos CSS a usar. Puede indicarse una URL usando el prefijo "url:[path]".
* `render` -- Genera presentación del listado de archivos o contenido asociado a un archivo, en formato HTML.

## Demo

El script [tests/explorer-demo.php](https://github.com/jjmejia/miframe-explorer/blob/main/tests/explorer-demo.php) contiene una demostración completa de la funcionalidad de esta clase.

## Importante!

Los iconos SVG usados han sido tomados de la página [Bootstrap Icons](https://icons.getbootstrap.com/).

Esta librería forma parte de los módulos PHP incluidos en [micode-manager](https://github.com/jjmejia/micode-manager).

Documentación adicional, anécdotas y temas relacionados en el [Blog micode-manager](https://micode-manager.blogspot.com/)