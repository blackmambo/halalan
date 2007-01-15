<div class="content">
<h1>{$smarty.const.ELECTION_NAME}</h1>
</div>
<div class="content">
<h2>Add Voter</h2>
<div class="error">
{errors all='error'}
	{$error}<br />
{/errors}
</div>
<form action="addvoter.do">
<table>
	<tr>
		<td>First Name: <span style="color:red;">*</span></td>
		<td><input type="text" name="firstname" /></td>
	</tr>
	<tr>
		<td>Last Name: <span style="color:red;">*</span></td>
		<td><input type="text" name="lastname" /></td>
	</tr>
	<tr>
		<td>Email: <span style="color:red;">*</span></td>
		<td><input type="text" name="email" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" value="Add Voter" /></td>
	</tr>
	<tr>
		<td colspan="2"><span style="color:red;">* -required fields</span></td>
	</tr>
	<tr>
		<td colspan="2"><span style="color:red;">note: password and pin will be automatically</span></td>
	</tr>
	<tr>
		<td colspan="2"><span style="color:red;">{if $smarty.const.ELECTION_PIN_PASSWORD_GENERATION|lower eq "email"}generated and emailed to the voter{elseif $smarty.const.ELECTION_PIN_PASSWORD_GENERATION|lower}displayed on the next page{/if}</span></td>
	</tr>
</table>
</form>
<p>back to <a href="voters">voters</a></p>
</div>