<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache">
<title>用户信息</title>
<style>
html,body,table,form{width:100%;padding:0;margin:0;}
.sub{ padding: 5px; font-size: 12px;  }
.input-text{ border:#d0d0d0 1px solid; padding: 4px ;background:#FFF; height: 17px}
</style>
<?php 
$Today = date('Y-m-d');
;?>
</head>
	<body>
		<div class="">
			<form name="form" method="post" action="./save.php">
				<table>
					<tr><th>mac地址</th><td><input class="input-text" type="text" name="mac" maxlength="100"></td></tr>
					<tr><th>ip地址</th><td><input class="input-text" type="text" name="ip" maxlength="100"></td></tr>
					<tr>
						<th>时间</th>
						<td>
							<select name="cTime">
								<?php for($i = 0; $i<9 ; $i++){ ?>
									<option value="<?php echo date('Y-m-d', strtotime('-'.$i.' day'));?>" ><?php echo date('Y-m-d', strtotime('-'.$i.' day'));?></option>
								<?php }?>
							</select>
						</td>
					</tr>
					<tr><th></th><td><input class="sub" type="submit" value="搜索" ></td></tr>
				</table>
			</form>
		</div>
	</body>
</html>