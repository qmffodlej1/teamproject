<? 
	session_start(); 
	$table = "download";
	$num = $_GET['num'];
	$page = $_GET['page'];
	if (isset($_SESSION['userid']))
	{
			$userid = $_SESSION['userid'];
			$username = $_SESSION['username'];
			$usernick = $_SESSION['usernick'];
			$userlevel = $_SESSION['userlevel'];
	}
	include "../lib/dbconn.php";

	$sql = "select * from $table where num=$num";
//	$result = mysql_query($sql, $connect);
$result = mysqli_query($connect, $sql);

 //   $row = mysql_fetch_array($result);       
 $row = mysqli_fetch_array($result);

	$item_num     = $row['num'];
	$item_id      = $row['id'];
	$item_name    = $row['name'];
  	$item_nick    = $row['nick'];
	$item_hit     = $row['hit'];

	$file_name[0]   = $row['file_name_0'];
	$file_name[1]   = $row['file_name_1'];
	$file_name[2]   = $row['file_name_2'];

	$file_type[0]   = $row['file_type_0'];
	$file_type[1]   = $row['file_type_1'];
	$file_type[2]   = $row['file_type_2'];

	$file_copied[0] = $row['file_copied_0'];
	$file_copied[1] = $row['file_copied_1'];
	$file_copied[2] = $row['file_copied_2'];

    $item_date    = $row['regist_day'];
	$item_subject = str_replace(" ", "&nbsp;", $row['subject']);

	$item_content = str_replace(" ", "&nbsp;", $row['content']);
	$item_content = str_replace("\n", "<br>", $item_content);
	$new_hit = $item_hit + 1;

	$sql = "update $table set hit=$new_hit where num=$num";   // 글 조회수 증가시킴
//	mysql_query($sql, $connect);
	$result = $connect->query($sql); // 옛날 코드라서 바꿔줘야한다

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head> 
<meta charset="utf-8">
<link href="../css/common.css" rel="stylesheet" type="text/css" media="all">
<link href="../css/board3.css" rel="stylesheet" type="text/css" media="all">
<script>
    function del(href) 
    {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                document.location.href = href;
        }
    }
</script>
</head>
<div id="container">
    <body>
        <header class="header">
		<a href="../index.php"> <!-- 로고를 클릭하면 현재 페이지(index.php)로 연결되도록 설정 -->
                <img src="../img/logo2.png" class="logo" alt="로고">
            </a>
            <?php
            if (empty($userid)) {
                echo '<div id="top_login"><a href="../login/login_form.php">로그인</a> | <a href="../member/member_form.php">회원가입</a></div>';
            } else {
                echo '<div id="top_login">' . $usernick . ' (level: ' . $userlevel . ') | <a href="../login/logout.php">로그아웃</a> | <a href="../login/member_form_modify.php">정보수정</a></div>';
            }
            ?>
        </header>
		<div id="body">
        <div id="wrap">
            <div id="menu">
                <?php include "../lib/top_menu2.php"; ?>
            </div> <!-- end of menu -->
        </div> <!-- end of wrap -->
	<div id="col2">     
		<div id="title">
			<img src="../img/title_download.gif">
		</div>
		<div id="view_comment"> &nbsp;</div>

		<div id="view_title">
			<div id="view_title1"><?= $item_subject ?></div><div id="view_title2"><?= $item_nick ?> | 조회 : <?= $item_hit ?>  
			                      | <?= $item_date ?> </div>	
		</div>

		<div id="view_content">
<?
	for ($i=0; $i<3; $i++)
	{
		if (@$userid && $file_copied[$i])
		{
			$show_name = $file_name[$i];
			$real_name = $file_copied[$i];
			$real_type = $file_type[$i];
			$file_path = "./data/".$real_name;
			$file_size = filesize($file_path);

			echo "▷ 첨부파일 : $show_name ($file_size Byte) &nbsp;&nbsp;&nbsp;&nbsp;
			       <a href='download.php?table=$table&num=$num&real_name=$real_name&show_name=$show_name&file_type=$real_type'>[저장]</a><br>";
		}
	}
?>
		    <br>
			<?= $item_content ?>
		</div>

		<div id="view_button">
				<a href="list.php?table=<?=$table?>&page=<?=$page?>"><img src="../img/list.png"></a>&nbsp;
<!-- <? 
	if($userid && $userid==$item_id)
	{
?>
				<a href="write_form.php?table=<?=$table?>&mode=modify&num=<?=$num?>&page=<?=$page?>"><img src="../img/modify.png"></a>&nbsp;
				<a href="javascript:del('delete.php?table=<?=$table?>&num=<?=$num?>')"><img src="../img/delete.png"></a>&nbsp;
<?
	}
?> -->
<? 
	if(@$userid==$item_id || @$userlevel==1 || @$userid=="admin")
	{
?>
				<a href="write_form.php?table=<?=$table?>&mode=modify&num=<?=$num?>&page=<?=$page?>"><img src="../img/modify.png"></a>&nbsp;
				<a href="javascript:del('delete.php?num=<?=$num?>')"><img src="../img/delete.png"></a>&nbsp;
<?
	}
?>

<? 
	if(@$userid)
	{
?>
				<a href="write_form.php?table=<?=$table?>"><img src="../img/write.png"></a>
<?
	}
?>
		</div>
		<div class="clear"></div>

	</div> <!-- end of col2 -->
  </div> <!-- end of content -->
</div> <!-- end of wrap -->

</body>
</html>
