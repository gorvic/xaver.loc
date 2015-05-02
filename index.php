<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<h1>Урок 4. Задание № 1</h1>
<?php
/*
 * Следующие задания требуется воспринимать как ТЗ (Техническое задание)
 * p.s. Разработчик, помни!
 * Лучше уточнить ТЗ перед выполнением у заказчика, если ты что-то не понял, чем сделать, переделать, потерять время, деньги, нервы, репутацию.
 * Не забывай о навыках коммуникации :)
 *
 * Задание 1
 * - Вы проектируете интернет магазин. Посетитель на вашем сайте создал следующий заказ (цена, количество в заказе и остаток на складе генерируются автоматически):
 */
function mb_ucfirst($string, $encoding = 'UTF-8')
{
    return mb_strtoupper(mb_substr($string, 0, 1, $encoding),$encoding).mb_strtolower(mb_substr($string, 1,strlen($string) - 1 ,$encoding),$encoding);
}

function array2table($table_array, $null = '&nbsp;')
{
    global $first_column_name;
    global $tableStyle;

    // Sanity check
    if (empty($table_array) || !is_array($table_array)) {
        return false;
    }

    // Start the table
    $html_table = "<table ".$tableStyle.">\n";

    // The header
    $html_table .= "\t<tr>";
    //выведем первую колонку
    $html_table .= '<th>' . $first_column_name. '</th>';

    // Take the keys from the first row as the headings

    $first_item_keys = array_keys($table_array);
    $first_item = $table_array[$first_item_keys[0]];

     //получим первый элемент по ключу и пройдёмся по ключам его значений
     foreach ($first_item as $heading => $value) {
         $heading = str_replace('diskont', 'Скидка', $heading);
         //$heading = mb_convert_case($heading, MB_CASE_TITLE, 'UTF-8');
         $html_table .= '<th>' . mb_ucfirst($heading ). '</th>';
    }
    $html_table .= "</tr>\n";

    // The body
    foreach ($table_array as $name => $row) {
        $html_table .= "\t<tr>" ; //открываем строку
        $html_table .= '<td>'.$name.'</td>'; //выводим ключ исходного массива



        foreach ($row as $cell) { //идём по значению-массиву исходного массива

            $html_table .= '<td>';

            $pos = strpos($cell,'diskont');
            if($pos !== FALSE){
                $cell = str_replace('diskont','', $cell);
            }

            // Cast objects
            if (is_object($cell)) { $cell = (array) $cell; }

            $html_table .= (strlen($cell) > 0) ?
                    htmlspecialchars((string) $cell) : //выводим
                    $null; //иначе пусто

            $html_table .= '</td>';
        }

        $html_table .= "</tr>\n";
    }

    $html_table .= '</table>';
    return $html_table;
}


function section_output ($array, $section_name){

    switch (count($array))
    {
        case 0:
            break;
        default:
            $number_of_section = get_number_of_section();
            echo '<h3>'.$number_of_section.". ".$section_name.':</h3>';
            foreach ($array as $elem)
            echo $elem;
    }
 }

function get_number_of_section()
{
    static $number_of_section = 0;
    return ++$number_of_section;
}

$ini_string='
[игрушка мягкая мишка белый]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';

[одежда детская куртка синяя синтепон]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';

[игрушка детская велосипед]
цена = '.  mt_rand(1, 10).';
количество заказано = '.  mt_rand(1, 10).';
осталось на складе = '.  mt_rand(0, 10).';
diskont = diskont'.  mt_rand(0, 2).';

';
$bd =  parse_ini_string($ini_string, true);

$first_column_name = 'Наименование товара';
$tableStyle = ' border="1px" cellpadding="2"';

/*
 *
 * - Вам нужно вывести корзину для покупателя, где указать:
 * 1) Перечень заказанных товаров, их цену, кол-во и остаток на складе
 *
 */

$table = array2table($bd);
echo $table;

$total_items_ordered = 0;
$total_qty = 0;
$total_sum = 0;

//пройдёмся по массиву ещё раз и посчитаем  все итоговые значения
$array_notifications = array();
$array_discounts = array();


foreach ($bd as $name => $row) {

        //инициализация
        $cur_discount = (int) str_replace('diskont','',$row['diskont'])*10;
        $cur_qty = (int) $row['количество заказано'];
        $cur_price = (int) $row['цена'];
        $cur_remain = (int) $row['осталось на складе'];

        //условия
        if ($cur_qty < $cur_remain){
            $array_notifications[] = "Товар  <i>\"".$name."\"</i> в наличии на складе только в количестве <b>".$cur_remain."</b>, при заказе <b>".$cur_qty."</b> штук.<br>";
        }

        if ($name == 'игрушка детская велосипед' && $cur_qty >=3 ){ //случай спец. предложения
            $array_discounts[] = "Для товара <i>\"".$name."\"</i> Вы получаете <b><i>специальную</i></b> скидку в <b>30%</b>!";
        }elseif ($cur_discount != 0) {  //другие случаи скидки
            $array_discounts[] = "Для товара <i>\"".$name."\"</i> Вам предоставлена скидка в <b>".$cur_discount."%</b>!<br>";
        }
        else{
            $array_discounts[] = "Для товара <i>\"".$name."\"</i> скидка отсутствует.<br>";
        }


        //итоговый подсчёт после всех изменений
        $total_qty += $cur_qty;
        $total_sum +=  $cur_qty * $cur_price * (100 - $cur_discount)/100 ;
}
/*
 * 2) В секции ИТОГО должно быть указано: сколько всего наименовний было заказано, каково общее количество товара, какова общая сумма заказа
 *
 */

/*
 * - Вам нужно сделать секцию "Уведомления", где необходимо извещать покупателя о том, что нужного количества товара не оказалось на складе
 * - Вам нужно сделать секцию "Скидки", где известить покупателя о том, что если он заказал "игрушка детская велосипед" в количестве >=3 штук, то на эту позицию ему
 * автоматически дается скидка 30% (соответственно цены в корзине пересчитываются тоже автоматически)
 * 3) у каждого товара есть автоматически генерируемый скидочный купон diskont, используйте переменную функцию, чтобы делать скидку на итоговую цену в корзине
 * diskont0 = скидок нет, diskont1 = 10%, diskont2 = 20%
 *
 * В коде должно быть использовано:
 * - не менее одной функции
 * - не менее одного параметра для функции
 * операторы if, else, switch
 * статические и глобальные переменные в теле функции
 *
 */

section_output($array_discounts, 'Скидки');
section_output($array_notifications, 'Уведомления');

$number_of_section = get_number_of_section();
echo "<h3>".$number_of_section.". Итого:</h3>";
echo 'Заказано наименований: <b>' . count($bd) . '</b><br>'; //т.к. значения всегда 1 - 10
echo 'Общее количество товара: <b>' . $total_qty . '</b><br>';
echo 'Общая сумма заказа : <b>' . $total_sum . '</b>';
?>