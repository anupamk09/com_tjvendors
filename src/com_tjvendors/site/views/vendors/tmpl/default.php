<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_tjvendors
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

JHtml::_('formbehavior.chosen', 'select');

$listOrder     = $this->state->get('list.ordering');
$listDirn      = $this->state->get('list.direction');

?>
<script type="text/javascript">


	jQuery(document).ready(function ()
	{
		jQuery('#clear-search-button').on('click', function ()
		{
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});
	jQuery(document).ready(function ()
	{
		jQuery('#clear-calendar').on('click', function ()
		{
			jQuery('#date').val('');
			jQuery('#dates').val('');
			jQuery('#adminForm').submit();
		});
	});
</script>
<?php
$user_id = JFactory::getUser()->id;
if ( $user_id && !empty($this->vendor_id))
{?>
	<form action="<?php
		echo JRoute::_('index.php?option=com_tjvendors&view=vendors');
	?>" method="post" id="adminForm" name="adminForm">

			<div btn-group pull-left hidden-phone>

				<div class="btn-group hidden-phone">
					<?php
						echo JHTML::_('calendar',$this->state->get('filter.fromDate'), 'fromDates', 'dates', '%Y-%m-%d',array( 'class' => 'inputbox', 'onchange' => 'document.adminForm.submit()' ));
					?>
				</div>
				<div class="btn-group  hidden-phone">
					<?php
						echo JHTML::_('calendar',$this->state->get('filter.toDate'), 'toDates', 'date', '%Y-%m-%d',array( 'class' => 'inputbox', 'onchange' => 'document.adminForm.submit()' ));
					?>
				</div>
				<div class="btn-group pull-left hidden-phone">
					<button class="btn hasTooltip" id="clear-calendar" type="button" title="<?php echo JText::_('JSEARCH_CALENDAR_CLEAR'); ?>">
						<i class="icon-remove"></i>
					</button>
				</div>
			</div>

				<div class="btn-group pull-left hidden-phone">
					<input type="text" name="filter_search" id="filter_search"placeholder="<?php echo JText::_('COM_TJVENDOR_PAYOUTS_SEARCH_BY_VENDOR_TITLE');?>"
						value="<?php
						echo $this->escape($this->state->get('filter.search'));
						?>"title="<?php echo JText::_('JSEARCH_FILTER');
					?>"/>
				</div>
				<div class="btn-group pull-left hidden-phone">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT');?>">
						<i class="icon-search"></i>
					</button>
					<button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR');?>">
						<i class="icon-remove"></i>
					</button>
				</div>
			<div class="btn-group pull-left hidden-phone">
				<div class="btn-group  hidden-phone">
				<label for="limit" class="element-invisible">
					<?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
				</label>
				<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<div class="btn-group  hidden-phone">
						<?php
							echo JHtml::_('select.genericlist', $this->currencies, "currency", 'class="input-medium" size="1" onchange="document.adminForm.submit();"', "currency", "currency", $this->state->get('filter.currency'));
							$currency = $this->state->get('filter.currency');
						?>
				</div>
				<div class="btn-group  hidden-phone">
						<?php
							echo JHtml::_('select.genericlist', $this->uniqueClients, "vendor_client", 'class="input-medium" size="1" onchange="document.adminForm.submit();"', "client", "client", $this->state->get('filter.vendor_client'));
							$client = $this->state->get('filter.vendor_client');
						?>
				</div>
				<div class="btn-group hidden-phone">
					<?php
					$transactionType[] = array("transactionType"=>JText::_('COM_TJVENDORS_REPORTS_FILTER_ALL_TRANSACTIONS'),"transactionValue" => "0");
					$transactionType[] = array("transactionType"=>JText::_('COM_TJVENDORS_REPORTS_FILTER_CREDIT'),"transactionValue" => JText::_('COM_TJVENDORS_REPORTS_FILTER_CREDIT'));
					$transactionType[] = array("transactionType"=>JText::_('COM_TJVENDORS_REPORTS_FILTER_DEBIT'),"transactionValue" => JText::_('COM_TJVENDORS_REPORTS_FILTER_DEBIT'));
					echo JHtml::_('select.genericlist', $transactionType, "transactionType", 'class="input-medium" size="1" onchange="document.adminForm.submit();"', "transactionValue", "transactionType", $this->state->get('filter.transactionType'));?>
				<?php $transactionType = $this->state->get('filter.transactionType'); ?>
				</div>
			</div>
			<?php
				if (empty($this->items))
				{?>
					<div class="clearfix">&nbsp;</div>
					<div class="alert alert-no-items">
					<?php echo JText::_('COM_TJVENDOR_NO_MATCHING_RESULTS');?>
					</div>
				<?php }
				else
				{
				?>
		<table class="table table-striped table-hover">
			<thead>
					<tr>
						<th width="1%">
							<?php echo "Sr.No";?>
					   </th>
						<th width="5%">
							<?php echo JHtml::_('grid.sort', 'Transaction ID', 'pass.`transaction_id`', $listDirn, $listOrder);?>
						</th>
					<?php if($client == '0')
						{?>
						<th width="5%">
							<?php echo JHtml::_('grid.sort', 'Client', 'vendors.`vendor_client`', $listDirn, $listOrder);?>
						</th>
					<?php }
						if ($currency == '0')
						{?>

						<th width="5%">
							<?php echo JHtml::_('grid.sort', 'Currency', 'pass.`currency`', $listDirn, $listOrder); ?>
					   </th>
					<?php }
						if($transactionType == "credit" || empty($transactionType))
						{?>
							<th class='left' width="10%">
								<?php echo JHtml::_('grid.sort',  'COM_TJVENDORS_REPORTS_CREDIT_AMOUNT', 'pass.`credit`', $listDirn, $listOrder);?>
							</th>
					<?php
						}
						if($transactionType == "debit" || empty($transactionType))
						{?>
							<th class='left' width="10%">
								<?php	echo JHtml::_('grid.sort',  'COM_TJVENDORS_REPORTS_DEBIT_AMOUNT', 'pass.`debit`', $listDirn, $listOrder);?>
							</th>
					<?php
						}
					?>
						<th width="10%">
							<?php echo JHtml::_('grid.sort', 'Reference Order ID', 'pass.`reference_order_id`', $listDirn, $listOrder);?>
						</th>
						<th width="15%">
							<?php echo JHtml::_('grid.sort', 'Transaction Time', 'pass.`transaction_time`', $listDirn, $listOrder);?>
						</th>
						<th width="10%">
							<?php echo JHtml::_('grid.sort', 'Pending Amount', 'pass.`total`', $listDirn, $listOrder);?>
						</th>
						<th>
							<?php echo JText::_('COM_TJVENDORS_REPORTS_ENTRY_STATUS'); ?>
						</th>
					<th>
						<?php echo JText::_('COM_TJVENDORS_REPORTS_CUSTOMER_NOTE'); ?>
					</th>
					</tr>
			</thead>
			<?php if($currency != '0'):?>
			<tfoot>
					<td colspan="5">
						<?php echo $this->pagination->getListFooter();?>
				   </td>
					<td colspan="5">
						<div class="pull-right">
							<div>
								<?php echo "<h4>".JText::_('COM_TJVENDORS_REPORTS_CREDIT_AMOUNT'). '&nbsp ' .$this->totalDetails['creditAmount']. '&nbsp' . $currency ."</h4>";?>
							</div>
							<div>
								<?php echo "<h4>".JText::_('COM_TJVENDORS_REPORTS_DEBIT_AMOUNT'). '&nbsp ' . $this->totalDetails['debitAmount']. '&nbsp' . $currency ."</h4>"; ?>
							</div>
							<div>
								<?php echo "<h4>".JText::_('COM_TJVENDORS_REPORTS_PENDING_AMOUNT') . '&nbsp ' . $this->totalDetails['pendingAmount']. '&nbsp' . $currency ."</h4>";?>
						   </div>
						</div>
					</td>
			</tfoot>
			<?php endif;?>
			<tbody>
						<?php
						foreach ($this->items as $i => $row)
						{
						?>
							<tr>
								<td>
									<?php echo $this->pagination->getRowOffset($i);?>
								</td>
								<td>
									<?php echo $row->transaction_id;?>
								</td>
							<?php if($client == '0')
								{?>
								<td>
									<?php	echo JText::_("COM_TJVENDORS_VENDOR_CLIENT_".strtoupper($row->client));?>
								</td>
							<?php }
								if($currency == '0')
								{
								?>
								<td>
									<?php echo $row->currency;?>
								</td>
							<?php }
								if($transactionType == "credit" || empty($transactionType))
						{ ?>
							<td>
								<?php
									if($row->credit <='0')
									{
										echo "0";
									}
									else
									{
										echo $row->credit;
									}
								?>
							</td>
						<?php
						}
						if($transactionType == "debit" || empty($transactionType))
						{
						?>
							<td>
							<?php echo $row->debit;?>
							</td>
						<?php
						}
						?>
								<td>
									<?php echo $row->reference_order_id;?>
								</td>
								<td>
									<?php echo $row->transaction_time;?>
								</td>
								<td>
									<?php echo abs($row->total);?>
								</td>
								<td>
								<?php
								$status = json_decode($row->params, true);
								if($status['entry_status'] == "debit_payout")
								{
									if($row->status == 1)
									{
										echo "Payout Done";
									}
									else
									{
										echo "Payout Pending";
									}
								}
								elseif($status['entry_status'] == "credit_for_ticket_buy")
								{
									echo "Credit Done";
								}
								?>
								</td>
								<td class="center">
								<?php
									if(!empty($status['customer_note']))
									{
										echo $status['customer_note'];
									}
									else
									{
										echo "-";
									}
								?>
								</td>
							</tr>
							<?php
						}
					?>
			</tbody>
		</table>
	<?php
	}
	?>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="filter_order" value="<?php echo $listOrder;?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
		<?php echo JHtml::_('form.token');?>
	</form>
<?php
}
else
{
	$link =JRoute::_('index.php?option=com_users');
	$app = JFactory::getApplication();
	$app->redirect($link);
}?>
