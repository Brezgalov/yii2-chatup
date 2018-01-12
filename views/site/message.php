<div class="message">
	<div class="prefix">
		<div class="author">
			<?= $message->author->username ?>
		</div>
		<div class="time">
			at <?= strtotime($message->date) ?>
		</div>
	<div>
	<div class="text">
		<?= $message->text ?>
	</div>
</div>