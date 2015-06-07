<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<h1>Задание № 1</h1>
<?php
//    header('Content-Type: text/html; charset=utf-8'); //иначе кракозябры

    $name = 'Victor';
    $age = '34';

    //\r\n или \n не работают. Если не ставить хедер, идут кракозябры
    echo "Меня зовут ".$name.".<br>Мне ".$age." года.";

    unset($name, $age);
?>
<h1>Задание № 2</h1>
<?
    define("CITY", 'Samara');
    if (defined('CITY')) echo 'Константа определена: '.constant('CITY');
    else echo 'Константа НЕ определена';

    echo "<br>".CITY;
    define("CITY", 'Moscow');

?>

<h1>Задание № 3</h1>
<?
$book = [
    "author" => 'Дольник',
    "title" => 'Непослушное дитя биосферы',
    "pages" => 384
];
//Недавно я прочитал книгу 'title', написанную автором author, я осилил все pages страниц, мне она очень понравилась"
echo "Недавно я прочитал книгу \"".$book['title']."\", написанную автором ".$book['author'].", я осилил все ".$book['pages']." страниц, мне она очень понравилась"
?>
<h1>Задание № 4</h1>
<?

$books = [
    array(
        "author" => 'Дольник',
        "title" => 'Непослушное дитя биосферы',
        "pages" => 384
    ),
    array(
    "author" => 'Дилтс',
    "title" => 'Изменение убеждений с помощью НЛП',
    "pages" => 256
)
];

 echo "Недавно я прочитал книги \"".$books[0]['title']."\" и \"".$books[1]['title']."\",
   написанные соответственно авторами ".$books[0]['author']." и ".$books[1]['author'].", я осилил в сумме ".
           ($books[0]['pages'] + $books[1]['pages'])." страниц, не ожидал от себя.";

?>