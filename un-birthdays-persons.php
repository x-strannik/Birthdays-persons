<?php 
/*
Plugin Name: Birthdays persons
Plugin URI: https://kodabra.unchikov.ru/birthdays-persons/
Description: Дни рождения персон в текущем месяце (шорткод для текстового виджета [pers_birthd]).
Author: Elena Unchikova
Version: 1.0.0
Author URI: https://kodabra.unchikov.ru/
*/ 

// Выход при прямом доступе.
     if ( ! defined( 'ABSPATH' ) ) { exit; }

add_shortcode( 'pers_birthd', 'per_bshortf' ); // регистрируем шорткод [pers_birthd]
function per_bshortf() {
ob_start();

// *******************************************************
$my_posts = new WP_Query;
// делаем запрос
$myposts = $my_posts->query( [
	'post_type' => 'member', // тип записи персон, имеющих поле "дата рождения" (здесь 'born')
	'posts_per_page'=> '-1'	
] );
// обрабатываем результат
date_default_timezone_set('Asia/Yakutsk'); // ваша временная зона
$tmes_d = date('m-d'); // текущая дата в формате мм-дд
$tmes_m = date('m'); // текущий месяц
$tmes_dm = date('d.m'); // текущая дата в формате дд.мм
function zagolbirthday() // заголовок "В ИЮНЕ РОДИЛИСЬ"
{
$month_list = array(
	1  => 'январе',
	2  => 'феврале',
	3  => 'марте',
	4  => 'апреле',
	5  => 'мае', 
	6  => 'июне',
	7  => 'июле',
	8  => 'августе',
	9  => 'сентябре',
	10 => 'октябре',
	11 => 'ноябре',
	12 => 'декабре'
);
 // стили можно свои поставить
echo '
<link rel="stylesheet" href="' . plugins_url( '/style.css' , __FILE__ ) . '">
<style>
.personbirthdaywidget li {
    list-style-type: none;
}
.personbirthdaywidget li:before {
    content: "\e900";
    font-family: "icomoon" !important;
	color: #9e9e9e !important;
    font-size: 22px !important;
}
.icon-salut .path1:before {
  content: "\e901";
  color: #ff9800;
  font-family: "icomoon" !important;
}
.icon-salut .path2:before {
  content: "\e902";
  font-family: "icomoon" !important;
  color: #ff9800;
  opacity: 0.18;
  margin-left: -1em;
}
.icon-salut .path3:before {
  content: "\e903";
  color: #ff9800;
  margin-left: -1em;
}
.icon-salut .path4:before {
  content: "\e904";
  font-family: "icomoon" !important;
  color: #ff9800;
  margin-left: -1em;
}
.icon-salut .path5:before {
  content: "\e905";
  font-family: "icomoon" !important;
  color: #ff9800;
  opacity: 0.973;
  margin-left: -1em;
}
.icon-salut .path6:before {
  content: "\e906";
  color: #ff9800;
  opacity: 0.996;
  margin-left: -1em;
}
.icon-salut .path7:before {
  content: "\e907";
  font-family: "icomoon" !important;
  color: #ff9800;
  margin-left: -1em;
}
.icon-salut .path8:before {
  content: "\e908";
  font-family: "icomoon" !important;
  color: #ff9800;
  margin-left: -1em;
}
</style>
<div id="personbirthdaywidget" class="personbirthdaywidget">
<div class="birthdays-head" style="margin-top: -10px;margin-bottom: 10px;">
	<span class="icon-salut" style="text-align: center;font-size: 4em;"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></span>
</div>
<div class="intern-box box-title">
    <h3>В ' . $month_list[date('n')] . ' родились</h3>
</div>
<ul>';
}
$output = []; // массив персон с днем рождения в текущем месяце
    $num = 0;
foreach( $myposts as $pst ){
    $pstdr_d = mb_substr(get_post_meta( $pst->ID, 'born', true ), 5, 5); // получаем дату рождения (здесь даты в поле 'born' записаны в формате гггг-мм-дд) и оставляем последние 5 знаков
	$pstdr_m = mb_substr($pstdr_d, 0, 2); // получаем месяц рождения	
if($pstdr_m==$tmes_m){ // если месяц рождения равен текущему месяцу
	$pstdr_dm = "(" . mb_substr($pstdr_d, 3, 2) . "." . $pstdr_m . ") "; // дата рождения дд.мм "(03.06)"
    $num = $num + 1;
if($num == 1){	
    zagolbirthday(); // заголовок выводим 1 раз в начале цикла
	$ulul = '</ul></div>'; // закрывающие тэги выводим 1 раз в конце цикла	
}
$perurl = get_permalink($pst); // ссылка на персону
$postper_title = get_the_title( $pst->ID ); // название записи (имя персоны)
if($pstdr_d==$tmes_d){ // проверяем на совпадение с текущей датой
$output[] = '' . $pstdr_dm . '</span><a href="' . $perurl . '" style="color: #ff9800;"> ' . $postper_title . '</a></li>'; // добавляем в массив
}else{
$output[] = '' . $pstdr_dm . '</span><a href="' . $perurl . '"> ' . $postper_title . '</a></li>'; // добавляем в массив	
}
}
}
natsort($output); // сортируем массив, чтобы даты шли в порядке возрастания
foreach( $output as $pst1 ){
$pstdr_dm1 = mb_substr($pst1, 1, 5);
if($pstdr_dm1==$tmes_dm){ // проверяем на совпадение с текущей датой
echo '<li style="margin-left: 9px;"><span style="color: #ff9800;">' . $pst1;
}else{
echo '<li style="margin-left: 9px;"><span>' . $pst1;
}
}     
echo $ulul;
wp_reset_postdata();
// *******************************************************

return ob_get_clean();
}
?>