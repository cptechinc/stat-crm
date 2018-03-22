<?php $prices = get_commissionprices($item->itemid); ?>
<table class="table table-condensed table-striped table-bordered">
	<tr>
		<th>Percent</th><th>Price</th>
	</tr>
	<?php foreach ($prices as $price) : ?>
		<tr>
			<td><?= $price['percent']; ?></td>
			<td class="text-right">$ <?= $price['price']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>
