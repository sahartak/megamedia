<h4>Week number: <input type="text" value="<?=$order_weeks[0]?>" class="edit_week" data-type="0" /> </h4>
<table class="table table-bordered table-striped">
	<tr>
		<th rowspan="2">Store</th>
		<?php
		$b_count = $p_count = $r_count = 0;
		$j = 0;
		foreach($campaign_orders as $type => $order){
			$count = count($order);

			switch($type){
				case 1:
					$name = 'Banner';
					$b_count = $count;
					break;

				case 2:
					$name = 'Poster';
					$p_count = $count;
					break;

				case 3:
					$name = 'Roll up';
					$r_count = $count;
					break;
			}
			for($i=1; $i<=$count; $i++) {
				echo '<th colspan="4" class="campaign_'.$campaign_orders_list[$j].'">',$name,' ',$i,'</th>';
				$j++;
			}

		}
		$total_columns = $b_count + $p_count + $r_count;
		?>
	</tr>
	<tr>
		<?php
		$j=0;
		if($b_count) {
			for($i=0; $i<$b_count; $i++) {?>
				<td class="campaign_<?=$campaign_orders_list[$j]?> small-tr">A390x300</td>
				<td class="campaign_<?=$campaign_orders_list[$j]?> small-tr">A100x100</td>
				<td class="campaign_<?=$campaign_orders_list[$j]?> small-tr">A300x290</td>
				<td class="campaign_<?=$campaign_orders_list[$j]?> small-tr">A580x450</td>
				<?php
				$j++;
			}
		}

		if($p_count) {
			for($i=0; $i<$p_count; $i++) {?>
				<td class="campaign_<?=$campaign_orders_list[$j]?> td_width" colspan="4">69x99</td>
				<?php
				$j++;
			}

		}

		if($r_count) {
			for($i=0; $i<$r_count; $i++) {?>
				<td class="campaign_<?=$campaign_orders_list[$j]?> td_width" colspan="4">85x200</td>
				<?php
				$j++;
			}

		}
		?>
	</tr>
	<?php foreach($campaign_stores as $store): ?>
		<tr>
			<td><?=$store['name']?></td>
			<?php
			$i = 0;
			foreach($campaign_orders as &$campaign) {
				foreach($campaign as &$order) {

					$store_order = isset($order[$store['id']]) ? $order[$store['id']] : false;
					if(!$store_order){?>
						<td colspan="4" class="campaign_<?=$campaign_orders_list[$i]?>"></td>
					<?php				} else { ?>
						<td class="campaign_<?=$campaign_orders_list[$i]?> campaign_edit" data-campaign="<?=$campaign_orders_list[$i]?>" data-id="<?=$store_order['id']?>" data-type="1">
							<?=$store_order['type_1']?>
						</td>
						<td class="campaign_<?=$campaign_orders_list[$i]?> campaign_edit" data-campaign="<?=$campaign_orders_list[$i]?>" data-id="<?=$store_order['id']?>" data-type="2">
							<?=$store_order['type_2']?>
						</td>
						<td class="campaign_<?=$campaign_orders_list[$i]?> campaign_edit" data-campaign="<?=$campaign_orders_list[$i]?>" data-id="<?=$store_order['id']?>" data-type="3">
							<?=$store_order['type_3']?>
						</td>
						<td class="campaign_<?=$campaign_orders_list[$i]?> campaign_edit" data-campaign="<?=$campaign_orders_list[$i]?>" data-id="<?=$store_order['id']?>" data-type="4">
							<?=$store_order['type_4']?>
						</td>
					<?php
					}
					$i++;
				}
			}
			?>
		</tr>
	<?php endforeach;?>
	<tr>
		<td colspan="<?=($total_columns * 4 + 1)?>"></td>
	</tr>
	<tr>
		<th>Material</th>
		<?php
		$j = 0;
		foreach($campaign_orders as &$campaign) {
			foreach($campaign as &$order) {
				echo '<td class="campaign_'.$campaign_orders_list[$j].'" colspan="4">';
				$j++;
				$materials_ids = array();
				foreach($order as $store_order) {
					if($store_order['material_id'])
						$materials_ids[] = $store_order['material_id'];
				}
				$materials_ids = array_unique($materials_ids);
				foreach($materials_ids as $m_id) {
					echo $materials[$m_id]. '<br />';
				}
				echo '</td>';
			}
		}
		?>
	</tr>
	<tr>
		<th>Total</th>
		<?php $j=0; foreach($campaign_orders as $type => &$campaign)
			for($i=0;$i<count($campaign);$i++):
				if($type == 1):?>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_1" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=get_price_by_square(390,300,$prices[1])?>">
						<?=$campaign_totals[$j]['s_type_1']?>
					</td>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_2" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=get_price_by_square(100,100,$prices[1])?>">
						<?=$campaign_totals[$j]['s_type_2']?>
					</td>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_3" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=get_price_by_square(300,290,$prices[1])?>">
						<?=$campaign_totals[$j]['s_type_3']?>
					</td>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_4" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=get_price_by_square(580,450,$prices[1])?>">
						<?=$campaign_totals[$j]['s_type_4']?>
					</td>
				<?php else:?>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_1" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=$prices[$type]?>">
						<?=$campaign_totals[$j]['s_type_1']?>
					</td>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_2" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=$prices[$type]?>">
						<?=$campaign_totals[$j]['s_type_2']?>
					</td>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_3" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=$prices[$type]?>">
						<?=$campaign_totals[$j]['s_type_3']?>
					</td>
					<td class="campaign_<?=$campaign_orders_list[$j]?> s_totals s_type_4" data-campaign="<?=$campaign_orders_list[$j]?>" data-price="<?=$prices[$type]?>">
						<?=$campaign_totals[$j]['s_type_4']?>
					</td>
				<?php endif;?>
				<?php $j++; endfor;?>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<?php $j=0; foreach($campaign_orders_list as $ord_list):?>
			<td class="campaign_<?=$campaign_orders_list[$j]?>" colspan="4">
				<button class="btn btn-danger delete_campaign" data-campaign="<?=$ord_list?>"><i class="icon-trash icon-white"></i></button>
				&nbsp;
				<button class="btn btn-primary edit_campaign" data-campaign="<?=$ord_list?>"><i class="icon-pencil icon-white"></i></button>
			</td>
			<?php $j++; endforeach;?>
	</tr>
	<tr>
		<th>Price</th>
		<?php $j=0; $total_price = 0;
		foreach($campaign_orders as $type => &$campaign)
			for($i=0; $i<count($campaign); $i++):
				?>
				<td colspan="4" class="campaign_<?=$campaign_orders_list[$j]?> campaign_price">
					<?php
					if($type==1) {
						echo $price = round(
							$campaign_totals[$j]['s_type_1']*get_price_by_square(390,300,$prices[1]) +
							$campaign_totals[$j]['s_type_2']*get_price_by_square(100,100,$prices[1]) +
							$campaign_totals[$j]['s_type_3']*get_price_by_square(300,290,$prices[1]) +
							$campaign_totals[$j]['s_type_4']*get_price_by_square(580,450,$prices[1])
						);
						$total_price += $price;
					}
					else {
						echo $price = round(($campaign_totals[$j]['s_type_1']+$campaign_totals[$j]['s_type_2']+$campaign_totals[$j]['s_type_3']+$campaign_totals[$j]['s_type_4'])*$prices[$type]);
						$total_price += $price;
					}
					?>
				</td>
				<?php $j++; endfor;?>
	</tr>
</table>