<div class="message">
	<div class="prefix">
		<div class="author noselect">
			@<?= $message->author->username ?> says
		</div>
		<div class="time noselect">
			at <?= $message->date ?>
		</div>
	</div>
	<div class="text">
		<?= $message->text ?>
	</div>
</div>