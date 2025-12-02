<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Términos y Condiciones - Foro E</title>
	<link rel="stylesheet" href="style.css">
	<style>
		.legal-doc {
			background-color: #fff;
			padding: 2em;
			border-radius: 0.5em;
			margin: 2em;
			line-height: 1.6;
			color: #333;
		}
		.legal-doc h2 {
			color: #444;
			border-bottom: 2px solid #eee;
			padding-bottom: 0.5em;
			margin-top: 1.5em;
			margin-bottom: 1em;
		}
		.legal-doc p {
			margin-bottom: 1em;
			text-align: justify;
		}
		.legal-doc ul {
			margin-bottom: 1em;
			padding-left: 2em;
		}
	</style>
</head>

<body>
	<?php include "page/header.php"; ?>
	<main>

		<div class="legal-doc">
			<h1>Términos y Condiciones de Uso</h1>
			<p><i>Última actualización: <?php echo date("d/m/Y"); ?></i></p>

			<p>Bienvenido a <b>Foro E</b>. A continuación se describen los términos y condiciones que rigen el uso de este sitio web y los servicios ofrecidos por <b>Foros LLC.</b> (en adelante, "los Autores"). Al acceder o utilizar este sitio web, usted acepta estar legalmente vinculado por estos términos.</p>

			<h2>1. Términos Generales</h2>
			<p>Foros LLC. y sus Autores son los encargados exclusivos del desarrollo, mantenimiento y actualización de la plataforma, así como de proveer el acceso a los servicios de "Foro E" (en adelante, "el Sitio Web").</p>
			<p>Los Autores se reservan el derecho de modificar, suspender o discontinuar cualquier aspecto del servicio en cualquier momento. Asimismo, Foros LLC. se reserva el derecho de actualizar estos Términos y Condiciones a su entera discreción. Se notificará a los usuarios sobre cambios significativos para que puedan aceptar las nuevas condiciones y continuar utilizando el servicio.</p>

			<h2>2. Condiciones de Uso y Conducta del Usuario</h2>
			<p>Al utilizar el Sitio Web, usted se compromete a:</p>
			<ul>
				<li>Tratar a todos los usuarios de forma respetuosa, amable y amigable.</li>
				<li>Utilizar los servidores y el Sitio Web de la forma prevista y legítima.</li>
				<li>No abusar de exploits, vulnerabilidades o intentar realizar acciones malintencionadas contra el personal administrativo o usuarios normales.</li>
			</ul>
			<p>Los administradores del sitio tienen completo derecho, bajo justa causa, de eliminar, suspender o banear a cualquier usuario sin previo aviso si se determina que ha violado estas normas. No obstante, se ofrece una opción de apelación para rehabilitar cuentas desactivadas, sujeto a revisión administrativa.</p>
			<p>A los usuarios se les provee un acceso ilimitado a sus cuentas, a menos que el servicio no se encuentre disponible por razones técnicas o sus cuentas hayan sido deshabilitadas por violación de estos términos.</p>

			<h2>3. Política de Privacidad</h2>
			<p>La privacidad de sus datos es importante para nosotros. En cumplimiento con nuestras políticas de protección de datos:</p>
			<p>Todo el contenido generado en el Sitio Web es almacenado en una base de datos privada. Foros LLC. garantiza que:</p>
			<ul>
				<li>El contenido del sitio web no es utilizado ni compartido con terceros externos sin su consentimiento explícito, salvo requerimiento legal.</li>
				<li>Se implementan medidas de seguridad razonables para proteger la información almacenada.</li>
			</ul>
			<p>Al registrarse y utilizar nuestros servicios, usted acepta el almacenamiento y procesamiento de su información de acuerdo con esta política.</p>

			<h2>4. Anuncios y Publicidad</h2>
			<p>El Sitio Web puede mostrar anuncios provistos por plataformas externas (como Google Ads, Microsoft Advertising, entre otros). Estos anuncios son necesarios para el mantenimiento y operación gratuita del sitio.</p>
			<p>Al respecto, usted entiende y acepta lo siguiente:</p>
			<ul>
				<li>Si encuentra algún anuncio inapropiado o extraño, puede reportarlo, aunque la gestión final depende del proveedor del anuncio.</li>
				<li>No ofrecemos una opción nativa para deshabilitar los anuncios, ya que son nuestra fuente de sustento. Sin embargo, no penalizamos ni prohibimos el uso de bloqueadores de anuncios (AdBlockers) por parte de los usuarios.</li>
				<li>Los anuncios pueden incluir rastreadores (trackers) y herramientas de analítica de terceros sobre los cuales no tenemos control directo. "No hay nada que podamos hacer" respecto a cómo operan estos terceros técnicamente.</li>
				<li>Al usar el sitio, usted acepta la visualización de estos anuncios y se sujeta también a las políticas de privacidad y términos de los proveedores externos de publicidad.</li>
			</ul>

			<br>
			<hr>
			<br>
			<p align="center"><small>Foros LLC. &copy; <?php echo date("Y"); ?>. Todos los derechos reservados.</small></p>
		</div>

	</main>
	<?php include "page/footer.php"; ?>
</body>

</html>
