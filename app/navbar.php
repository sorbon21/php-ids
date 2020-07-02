<?php
$navbar_left='';
$navbar_right='';
$user=$_SESSION['user']??NULL;

if (isset($user->id,$user->role,$user->login)) 
{

	
	if ($user->role=='Администратор')
	 {

        $navbar_left.='<li class="nav-item active">
                <a class="nav-link" href="/rules">Правила<span class="sr-only">(current)</span></a>
          </li>';
          $navbar_left.='<li class="nav-item active">
          <a class="nav-link" href="/tags">Теги<span class="sr-only">(current)</span></a>
    </li>';
    $navbar_left.='<li class="nav-item active">
    <a class="nav-link" href="/sites">Сайты клиентов<span class="sr-only">(current)</span></a>
</li>';
          $navbar_left.='<li class="nav-item active">
                    <a class="nav-link" href="/users">Пользователи<span class="sr-only">(current)</span></a>
            </li>';

			$navbar_left.='<li class="nav-item active">
			<a class="nav-link" href="/logs">Логи<span class="sr-only">(current)</span></a>
			</li>';
			$navbar_left.='<li class="nav-item active">
			<a class="nav-link" href="/statistic">Статистика<span class="sr-only">(current)</span></a>
			</li>';
			$navbar_left.='<li class="nav-item active">
			<a class="nav-link" href="/test">Тест инъекции<span class="sr-only">(current)</span></a>
			</li>';


            $navbar_right.='<li class="nav-item active">
						<a class="nav-link" href="/users?action=logout">('.$user->login.') Выход<span class="sr-only">(current)</span></a>
   					   </li>';


	 }
	 if ($user->role=='Клиент')
	 {
		

	$navbar_left.='<li class="nav-item active">
	<a class="nav-link" href="/sites?list=my">Мои сайты<span class="sr-only">(current)</span></a>
	</li>';
	$navbar_left.='<li class="nav-item active">
	<a class="nav-link" href="/test">Тест инъекции<span class="sr-only">(current)</span></a>
	</li>';
	$navbar_left.='<li class="nav-item active">
	<a class="nav-link" href="/statistic">Статистика<span class="sr-only">(current)</span></a>
	</li>';
	$navbar_right.='<li class="nav-item active">
					<a class="nav-link" href="/users?action=logout">('.$user->login.') Выход<span class="sr-only">(current)</span></a>
				  </li>';


	 }





}else{
	$navbar_left='';
	$navbar_right='<li class="nav-item active">
					<a class="nav-link" href="/auth">Вход<span class="sr-only">(current)</span></a>
	  			  </li>';

}

?>

		<nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top">
				 
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu">
					<span class="navbar-toggler-icon"></span>
				</button> 
				<a class="navbar-brand" href="/">
					<img src="/public/logo.png" widht="50" alt="" srcset="">
				</a>
				<div class="collapse navbar-collapse" id="menu">
					<ul class="navbar-nav">
						<?=$navbar_left?>
					</ul>
					<ul class="navbar-nav ml-md-auto">
							<?=$navbar_right?>
					</ul>
				</div>
			</nav>