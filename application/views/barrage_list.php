<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>弹幕管理</title>
    <script type="text/javascript" src="<?php echo base_url('public/js/jquery.min.js');?>"></script>
</head>
<body>
    <table>
        <tr>
            <td>编号</td>
            <td>内容</td>
            <td></td>
        </tr>
        <?php foreach($barrageList as $val){?>
        <tr>
            <td><?php echo $val['id'];?></td>
            <td><?php echo $val['content'];?></td>
            <td><a href="javascript:;" onclick="del(<?php echo $val['id'];?>)">删除</a></td>
        </tr>
        <?php };?>

        <tr>
            <td></td>
            <td><?php echo $pageInfo;?></td>
            <td></td>
        </tr>
    </table>
</body>
</html>

<script type="text/javascript">
    function del(id){
        $.post("<?php echo site_url('barrage/delBarrage');?>", { id: id},function(data){
            if(data == 1){
                location.reload();
            }else{
                alert('删除失败');
            }
        });
    }
</script>
