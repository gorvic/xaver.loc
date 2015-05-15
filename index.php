<?php
/* 1)Всё что пришло из формы записать в $_SESSION как новое объявление.
  2)Под формой создать вывод всех объявлений, содержащихся в сессии по шаблону:
  Название объявления | Цена | Имя | Удалить
  3)При нажатии на «название объявления» на экран выводится шаблон объявления как из пункта 1, только в места полей подставляются истинные значения
  4)При нажатии на «Удалить», объявление удаляется из сессии */

header("Content-Type: text/html; charset=utf-8");

//создаём или получаем доступ до  сессии
session_start();

//    unset($_SESSION['ads']);
//    exit;

function button_name() {
    if ($_SESSION['action_mode'] == "edit") {

        return "Сохранить";
    } else {

        return "Отправить";
    }
}

function array2table($columns_array, $null = '&nbsp;') {
    global $tableStyle;


    $html_table = "<table " . $tableStyle . ">\n";

    //header
    $html_table .= "\t<tr>";
    $html_table .= '<th> п/п </th>';

    foreach ($columns_array as $rus_heading) {
        $html_table .= '<th>' . $rus_heading . '</th>';
    }

    $html_table .= '<th> Действие </th>';
    $html_table .= "</tr>\n";

    //body
    if (isset($_SESSION['ads'])) { //если есть хоть одно объявление
        $ads = $_SESSION['ads'];

        $counter = 0;
        foreach ($ads as $id => $ad) {

            $html_table .= "\t<tr>"; //открываем строку

            $html_table .= '<td>'; //откроем ячейку
            $html_table .= ++$counter;
            $html_table .= '</td>';

            foreach ($columns_array as $key_of_ad => $value_of_ad) { //выведем только нужные колонки
                $html_table .= '<td>'; //откроем ячейку

                $cell = $ad[$key_of_ad]; //seller_name, title, price, delete_ad
                $cell = (strlen($cell) > 0) ?
                        htmlspecialchars((string) $cell) : //выводим
                        $null;

                if ($key_of_ad == 'title') {

                    $html_table .= "<a href=" . $_SERVER['PHP_SELF'] . "?id=" . $id . "&mode=show>" . $cell . "</a>";
                } else {

                    $html_table .= $cell;
                }
                $html_table .= '</td>';
            }

            $html_table .= '<td>'; //откроем ячейку
            $html_table .= "<a href=" . $_SERVER['PHP_SELF'] . "?id=" . $id . "&mode=delete> Удалить </a>";
            $html_table .= '</td>';
            $html_table .= "</tr>\n"; //закрываем строку
        }
    }
    $html_table .= '</table>';
    return $html_table;
}

//инициализация
$tableStyle = ' border="1px" cellpadding="2"';

//инициализируем массив в любом случае
if (!isset($_SESSION['ads'])) {
    $_SESSION['ads'] = [];
    $_SESSION['max_id'] = 0; //счётчик всегда увеличивается, id всегда уникален
    $_SESSION['action_mode'] = ""; //либо edit либо пусто
    $_SESSION['edit_id'] = ""; //id редактируемого объявления
}

//значения по умолчанию
$form_array = [];
$form_array['seller_name'] = "";
$form_array['phone'] = "";
$form_array['allow_mails'] = "";
$form_array['location_id'] = "";
$form_array['category_id'] = "";
$form_array['title'] = "";
$form_array['description'] = "";
$form_array['price'] = "0";
$form_array['email'] = "";
$form_array['private'] = 1;

//var_dump($_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST['submit'])) {  //пришло от кнопки
        if ($_SESSION['action_mode'] == "") { //заносим объявление
            $cur_index = ++$_SESSION['max_id'];  //увеличили id
        } else {
            $cur_index = $_SESSION['edit_id'];  //редактируемое объявление
            //сбрасываем режим
            $_SESSION['action_mode'] = "";
            $_SESSION['edit_id'] = "";
        }
        //либо добавляется по ключу, либо редактируется текущее
        $_SESSION['ads'][$cur_index] = $_POST;

        //проверим чекбокс. Если нет галки - в ПОСТ не приходит
        if (!isset($_SESSION['ads'][$cur_index]['allow_mails'])) {
            $_SESSION['ads'][$cur_index]['allow_mails'] = "";
        }

        //обработка значений ПОСТ, может быть значительно сложнее
        foreach ($_SESSION['ads'][$cur_index] as $key => $value) {
            $_SESSION['ads'][$cur_index][$key] = strip_tags($value);
        }

        //перезапрос GET
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") { //пришло из ссылок

    switch ($_SESSION['action_mode']) {
        case "edit":

            echo "Сначала сохраните изменения в объявлении!";

            foreach ($_SESSION['ads'][$_SESSION['edit_id']] as $key => $value) {
                $form_array[$key] = $value;
            }
            break;

        default :

            if (isset($_GET['id']) && isset($_GET['mode'])) {

                //проверяем параметры
                $id = (int) $_GET['id'];
                $mode = strip_tags($_GET['mode']);

                if ($mode == "show") { //проставить
                    foreach ($_SESSION['ads'][$id] as $key => $value) {
                        $form_array[$key] = $value;
                    }

                    //выставляем режим редактирования
                    $_SESSION['action_mode'] = "edit";
                    $_SESSION['edit_id'] = $id;
                    
                } elseif ($mode == "delete") { //удалить
                    //проверим, существует ли ключ в соответствии
                    if (array_key_exists($id, $_SESSION['ads'])) {

                        // echo "Хотим удалить объявление: " . $id . "<br />";
                        unset($_SESSION['ads'][$id]);
                    } else {

                        echo "Передан неверный ID объявления";
                    }
                }
            }
    }
}
?>

<h1>Урок 6. Задание № 1</h1>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Тег FORM</title>
    </head>
    <body>
<form  method="post">


    <div> <label><input type="radio" <?php if ($form_array['private'] == 1) {echo "checked=\"checked\"";}?> value="1" name="private">Частное лицо</label> <label><input type="radio" <?php if ($form_array['private'] == 0) {echo "checked=\"checked\"";} ?> value="0" name="private">Компания</label> </div>
    <div> <label><b id="your-name">Ваше имя</b></label>
        <input type="text" maxlength="40" value="<?php echo $form_array['seller_name']?>" name="seller_name" id="fld_seller_name">
    </div>
    <div> <label>Электронная почта</label>
        <input type="text" value="<?php echo  $form_array['email']?>" name="email" id="fld_email">
    </div>
    <div> <label> <input type="checkbox" value="1" <?php if ($form_array['allow_mails'] == 1) {echo "checked=\"checked\"";}?> name="allow_mails" id="allow_mails"><span>Я не хочу получать вопросы по объявлению по e-mail</span> </label> </div>
    <div> <label>Номер телефона</label> <input type="text" value="<?php echo $form_array['phone']?>" name="phone" id="fld_phone">
    </div>
    <div> <label for="region" class="form-label">Город</label> <select title="Выберите Ваш город" name="location_id" id="region" class="form-input-select"> <option value="">-- Выберите город --</option>
            <option <?php if ($form_array['location_id'] == "641780") { echo "selected=\"selected\"";} ?> value="641780">Новосибирск</option>   <option  <?php if ($form_array['location_id'] == "641490") { echo "selected=\"selected\"";} ?> value="641490">Барабинск</option>   <option  <?php if ($form_array['location_id'] == "641510") { echo "selected=\"selected\"";} ?> value="641510">Бердск</option>   <option <?php if ($form_array['location_id'] == "641600") { echo "selected=\"selected\"";} ?>  value="641600">Искитим</option>   <option  <?php if ($form_array['location_id'] == "641630") { echo "selected=\"selected\"";} ?> value="641630">Колывань</option>   <option <?php if ($form_array['location_id'] == "641680") { echo "selected=\"selected\"";} ?>  value="641680">Краснообск</option>   <option <?php if ($form_array['location_id'] == "641710") { echo "selected=\"selected\"";} ?>  value="641710">Куйбышев</option>   <option <?php if ($form_array['location_id'] == "641760") { echo "selected=\"selected\"";} ?>  value="641760">Мошково</option>   <option  <?php if ($form_array['location_id'] == "641790") { echo "selected=\"selected\"";} ?>  value="641790">Обь</option>   <option <?php if ($form_array['location_id'] == "641800") { echo "selected=\"selected\"";} ?>  value="641800">Ордынское</option>   <option <?php if ($form_array['location_id'] == "641970") { echo "selected=\"selected\"";} ?> value="641970">Черепаново</option> </select> </div>
    <!--<div> <label for="fld_category_id">Категория</label> <select title="Выберите категорию объявления" name="category_id" id="fld_category_id" class="form-input-select"> <option value="">-- Выберите категорию --</option><optgroup label="Транспорт"><option value="9">Автомобили с пробегом</option><option value="109">Новые автомобили</option><option value="14">Мотоциклы и мототехника</option><option value="81">Грузовики и спецтехника</option><option value="11">Водный транспорт</option><option value="10">Запчасти и аксессуары</option></optgroup><optgroup label="Недвижимость"><option value="24">Квартиры</option><option value="23">Комнаты</option><option value="25">Дома, дачи, коттеджи</option><option value="26">Земельные участки</option><option value="85">Гаражи и машиноместа</option><option value="42">Коммерческая недвижимость</option><option value="86">Недвижимость за рубежом</option></optgroup><optgroup label="Работа"><option value="111">Вакансии (поиск сотрудников)</option><option value="112">Резюме (поиск работы)</option></optgroup><optgroup label="Услуги"><option value="114">Предложения услуг</option><option value="115">Запросы на услуги</option></optgroup><optgroup label="Личные вещи"><option value="27">Одежда, обувь, аксессуары</option><option value="29">Детская одежда и обувь</option><option value="30">Товары для детей и игрушки</option><option value="28">Часы и украшения</option><option value="88">Красота и здоровье</option></optgroup><optgroup label="Для дома и дачи"><option value="21">Бытовая техника</option><option value="20">Мебель и интерьер</option><option value="87">Посуда и товары для кухни</option><option value="82">Продукты питания</option><option value="19">Ремонт и строительство</option><option value="106">Растения</option></optgroup><optgroup label="Бытовая электроника"><option value="32">Аудио и видео</option><option value="97">Игры, приставки и программы</option><option value="31">Настольные компьютеры</option><option value="98">Ноутбуки</option><option value="99">Оргтехника и расходники</option><option value="96">Планшеты и электронные книги</option><option value="84">Телефоны</option><option value="101">Товары для компьютера</option><option value="105">Фототехника</option></optgroup><optgroup label="Хобби и отдых"><option value="33">Билеты и путешествия</option><option value="34">Велосипеды</option><option value="83">Книги и журналы</option><option value="36">Коллекционирование</option><option value="38">Музыкальные инструменты</option><option value="102">Охота и рыбалка</option><option value="39">Спорт и отдых</option><option value="103">Знакомства</option></optgroup><optgroup label="Животные"><option value="89">Собаки</option><option value="90">Кошки</option><option value="91">Птицы</option><option value="92">Аквариум</option><option value="93">Другие животные</option><option value="94">Товары для животных</option></optgroup><optgroup label="Для бизнеса"><option value="116">Готовый бизнес</option><option value="40">Оборудование для бизнеса</option></optgroup></select> </div>-->

    <div style="display: none;" id="params" class="form-row form-row-required"> <label class="form-label ">
            Выберите параметры
        </label> <div class="form-params params" id="filters">
        </div> </div>
    <div id="f_title" class="form-row f_title"> <label for="fld_title" class="form-label">Название объявления</label> <input type="text" maxlength="50" class="form-input-text-long" value="<?php echo $form_array['title']?>" name="title" id="fld_title"> </div>
    <div class="form-row"> <label for="fld_description" class="form-label" id="js-description-label">Описание объявления</label> <textarea maxlength="3000" name="description" id="fld_description"><?php echo $form_array['description']?></textarea> </div>
    <div> <label>Цена</label> <input type="text" maxlength="9" value="<?php echo $form_array['price']?>" name="price">&nbsp;<span>руб.</span> </div>

    <div>
        <div> <span class="vas-submit-border"></span> <span></span> <input type="submit" value="<?php echo button_name();  ?>" id="form_submit" name="submit"> </div>
    </div>
</form>

<?php
    //exit;
    //Название объявления | Цена | Имя | Удалить
    $columns_array = ['title' => 'Название объявления'
        ,'price' => 'Цена'
        ,'seller_name' => 'Имя'
        ];
   $table = array2table($columns_array);
   echo $table;
?>