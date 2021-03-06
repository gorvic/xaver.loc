<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<h1>Урок 3. Задание № 1</h1>
<?php
    /*
 * - Создайте массив $date с пятью элементами
 * - C помощью генератора случайных чисел забейте массив $date юниксовыми метками
 *
 */
    $date = array();
    //получим текущее количество секунд
    $timestamp_now = time();
    for ($i = 0; $i < 5; $i++)
    {
        array_push($date, mt_rand(1, $timestamp_now));
    }
    echo "Исходное состояние:";
    var_dump($date);

    /*
     * - Сделайте вывод сообщения на экран о том, какой день в сгенерированном массиве получился наименьшим, а какой месяц
     * наибольшим
     *
     */

/*    $min_day = 32;
    $max_month = 0;

    foreach ($date as $value) {
         //не знаю, что быстрее, один раз объявить, потом использовать, либо в условии
        //второй раз использовать date()
        $cur_day = date('j',$value);
        $cur_month = date('n',$value);
//        echo "Тек. день: ".$cur_day." Мин. день:".$min_day."<br>";
//        echo "Тек. месяц: ".$cur_month." Макс. месяц:".$max_month."<br><br>";

        if ($cur_day < $min_day)
                $min_day = $cur_day;

        if ($cur_month > $max_month)
                $max_month = $cur_month;

        array_push($date_normal, date('d-m-Y',$value));
    }
*/
    //вместо цикла
    //для контроля
    $date_normal = array();
    array_push($date_normal, date('d-m-Y',$date[0]),date('d-m-Y',$date[1]),date('d-m-Y',$date[2])
            ,date('d-m-Y',$date[3]),date('d-m-Y',$date[4]));

    $date_array = array(date('j',$date[0]),date('j',$date[1]),date('j',$date[2]),date('j',$date[3]),date('j',$date[4]));
    $month_array = array(date('n',$date[0]),date('n',$date[1]),date('n',$date[2]),date('n',$date[3]),date('n',$date[4]));

    $min_day = min($date_array);
    $max_month = max($month_array);

    echo "Даты в привычном представлении:";
    var_dump($date_normal);
    echo "Наименьший день:".$min_day;
    echo "<br>Наибольший месяц:".$max_month;

    //* - Отсортируйте массив по возрастанию дат
    //array_multisort($date,SORT_ASC);
    sort($date);
    echo "<br>Отсортировано по возрастанию:";
    var_dump($date);

    // * - С помощью функция для работы с массивами извлеките последний элемент массива в новую переменную $selected
    $selected = array_pop($date);
    echo "Вытолкнут последний элемент:";
    var_dump($date);

    // * - C помощью функции date() выведите $selected на экран в формате "дд.мм.ГГ ЧЧ:ММ:СС"
    echo "Вывод конвертированной даты вытолкнутого элемента:<br>";
    echo date('d-m-Y h:m:s', $selected);

    // * - Выставьте часовой пояс для Нью-Йорка, и сделайте вывод снова, чтобы проверить, что часовой пояс был изменен успешно
    // */
    echo "<br>Вывод конвертированной даты вытолкнутого элемента по времени Нью-Йорка (-8):<br>";
    date_default_timezone_set('America/New_York');
    echo date('d-m-Y h:m:s', $selected);
?>