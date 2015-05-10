<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<h1>Урок 5. Задание № 1</h1>
<?php
//GET/POST

$news='Четыре новосибирские компании вошли в сотню лучших работодателей
Выставка университетов США: открой новые горизонты
Оценку «неудовлетворительно» по качеству получает каждая 5-я квартира в новостройке
Студент-изобретатель раскрыл запутанное преступление
Хоккей: «Сибирь» выстояла против «Ак Барса» в пятом матче плей-офф
Здоровое питание: вегетарианская кулинария
День святого Патрика: угощения, пивной теннис и уличные гуляния с огнем
«Красный факел» пустит публику на ночные экскурсии за кулисы и по закоулкам столетнего здания
Звезды телешоу «Голос» Наргиз Закирова и Гела Гуралиа споют в «Маяковском»';
$news=  explode("\n", $news);
//print_r($news);

// Функция вывода всего списка новостей.
function print_all_news(){
    global $news;

    foreach ($news as $key => $value) {
        echo $value.'<br>';
    }
}
// Функция вывода конкретной новости.
function print_news($array, $id){
    echo $array[$id].'<br>';
}

//function url_get_param($url, $name) {
//    parse_str(parse_url($url, PHP_URL_QUERY), $vars);
//    return isset($vars[$name]) ? $vars[$name] : null;
//}

// Если новость присутствует - вывести ее на сайте, иначе мы выводим весь список
// Был ли передан id новости в качестве параметра?
// если параметр не был передан - выводить 404 ошибку
//
// Точка входа.

if(isset($_GET['id'])) { //проверка, что есть параметр id
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
//    echo 'GET parameter';
//    var_dump($id);
   if($id === FALSE){ //то не инт
       header("HTTP/1.0 404 Not Found"); //выставили header
       //вывели сообщения
       echo 'GET: Error 404 Not Found<br>';
       echo 'The page that you have requested could not be found.<br>';
   }  elseif  ($id <= (count($news)-1)){
        print_news($news, $id);
   }  else {
       print_all_news();
   }
}
else{
    echo 'GET В адресной строке не указан параметр!<br>';
}

if(isset($_POST['id'])) { //проверка, что есть параметр id
   $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
//   echo 'POST parameter';
//   var_dump($id);
   if($id === FALSE){ //то не инт
       header("HTTP/1.0 404 Not Found"); //выставили header
       //вывели сообщения
       echo "POST: Error 404 Not Found<br>";
       echo "The page that you have requested could not be found.";
   }  elseif  ($id <= (count($news)-1)){
        print_news($news, $id);
   }  else {
       print_all_news();
   }
}
else{
    echo 'POST В адресной строке не указан параметр!';
}

?>

<!DOCTYPE HTML>
<html>
 <head>
  <meta charset="utf-8">
  <title>Тег FORM</title>
 </head>
 <body>

     <form method="POST">
         <p><b>Введите ID новости</b></p>
         <p><input type="text" name="id" value="">ID новости<Br>
         <p><input type="submit"></p>
     </form>

 </body>
</html>