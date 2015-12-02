<table class="table table-bordered table-striped additional_checkout">
	<tr>
		<th>Order number</th>
		<th>Week number</th>
		<th>Total price</th>
		<th>Date</th>
		<th>User</th>
	</tr>
<?php foreach($orders as $order):?>
	<tr>
		<td><?=$order['id']?></td>
		<td><?=$order['week_number']?></td>
		<td><?=$order['total_price']?></td>
		<td><?=$order['order_date']?></td>
		<td><?=$order['first_name'].' '.$order['last_name']?></td>
	</tr>
<?php endforeach;?>
</table>

<?php if($pagination):?>
	<div class="pagination text-center">
		<ul>
		<?php foreach($pagination['pages'] as $page):?>
			<li <?php if($page == $pagination['current']) echo 'class="current disabled"';?>><a href="/test/orders/?page=<?=$page?>"><?=$page?></a></li>
		<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>