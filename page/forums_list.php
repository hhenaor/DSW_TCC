<?php

	require_once 'php/db_manager.php';

	connect([
		'dbname' => 'foro_e'
	]);

	$forums = query(
		"SELECT * FROM forums ORDER BY forum_id DESC",
		[]
	);

	disconnect();

	echo "<h2>Foros disponibles</h2>";

	if ( $forums && !empty($forums) ) {
		foreach ( $forums as $forum ) {
			?>
			<article class="forum-box frow" >
				<div>
					<h3><?php echo htmlspecialchars($forum['name']) ?></h3>
					<p><?php echo nl2br(htmlspecialchars($forum['description'])) ?></p>
				</div>
				<a href="foros.php?id=<?php echo $forum['forum_id'] ?>">
					<button class="btn blu"> Visitar </button>
				</a>
			</article>
			<?php
		}
	} else {
?>
		<p>No hay foros disponibles.</p>
<?php
	}