<?php

header("Content-Type: text/html; charset=utf-8");

$project_root = $_SERVER['DOCUMENT_ROOT'];
$smarty_dir = $project_root.'/smarty/';
//var_dump($smarty_dir.'libs/Smarty.class.php');
//var_dump (file_exists($smarty_dir.'libs/Smarty.class.php'));
require($smarty_dir.'libs/Smarty.class.php');

define('PATH_TO_ADS', 'ads/ads.txt');

//Smarty initialization
$smarty = new Smarty();

$smarty->compile_check = true;
$smarty->debugging = false;

$smarty->template_dir = $smarty_dir.'templates';
$smarty->compile_dir = $smarty_dir.'templates_c';
$smarty->cache_dir = $smarty_dir.'cache';
$smarty->config_dir = $smarty_dir.'configs';

//header
$smarty->assign('lesson_number', 8);

//cities
$arr_cities =['-- Выберите город --',
                '641780' => 'Новосибирск',
                '641490' => 'Барабинск',
                '641510' => 'Бердск',
                '641600' => 'Искитим',
                '641630' => 'Колывань',
                '641680' => 'Краснообск',
                '641710' => 'Куйбышев',
                '641760' => 'Мошково',
                '641790' => 'Обь',
                '641800' => 'Ордынское',
                '641970' => 'Черепаново'
            ];
$smarty->assign('cities', $arr_cities);

//organization form
$arr_organization_form = ['0'=>'Частное лицо','1'=>'Организация'];
$smarty->assign('organization_form', $arr_organization_form);



function write_to_file($array) {
    if (file_exists(PATH_TO_ADS)) {
        file_put_contents(PATH_TO_ADS, serialize($array));
    }
}

//имя кнопки по умолчанию
$default_button_value = 'Отправить';
$default_button_name = 'submit';
$default_edit_id = '';


//для заполнения пустого значения в тэгах хтмл
//иначе конструкция value= , а нужно value = ""

//$emtpy_value_option = "\"\"";

$form_array = [];
$form_array['seller_name'] = "";
$form_array['phone'] = "";
$form_array['allow_mails'] = "";
$form_array['location_id'] = "";
$form_array['title'] = "";
$form_array['description'] = "";
$form_array['price'] = "0";
$form_array['email'] = "";
$form_array['private'] = 1;

//каждый раз инициализируем массив текущих объявлений
//если файл не существует - создадим его
if (!file_exists('ads/')) {
    mkdir('ads');
}

if (file_exists(PATH_TO_ADS)) {
    $ads_array = file_get_contents(PATH_TO_ADS);
    if ($ads_array === false) { //инициализируем массив
        $ads_array = [];
    } elseif (strlen($ads_array) == 0) {
        $ads_array = [];
    }
    else {
        $ads_array = unserialize($ads_array);
    }

} else {
    $handle = fopen(PATH_TO_ADS, "w");
    if ($handle) {
        fclose($handle);
    }
    $ads_array = [];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $from_submit = isset($_POST['submit']);
    $from_edit = isset($_POST['edit']);

    if ($from_submit || $from_edit) {

        $tmp_post = $_POST; //можно ли сразу перезаписывать в пост или лучше через буферную переменную?

        //проверим чекбокс. Если нет галки - в ПОСТ не приходит
        if (!isset($tmp_post['allow_mails'])) {
            $tmp_post['allow_mails'] = "";
        }

        //обработка значений ПОСТ; может быть значительно сложнее
        foreach ($tmp_post as $key => $value) {
           $tmp_post[$key] = strip_tags($value);
        }

        if ($from_submit) {
            //добавляем новое объявление, если не было
            $ads_array[] = $tmp_post;
        } elseif ($from_edit) {

            $id = (int)$_POST['edit_id'];
            $ads_array[$id] = $tmp_post;
        }

        write_to_file($ads_array);
        //перезапрос GET
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;

    }

} elseif ($_SERVER["REQUEST_METHOD"] == "GET") { //пришло из ссылок

    if (isset($_GET['id']) && isset($_GET['mode'])) {

        //проверяем параметры
        $id = (int) $_GET['id'];
        $mode = strip_tags($_GET['mode']);

        if ($mode == "show") { //проставить

            //заполним массив для вывода html
            foreach ($ads_array[$id] as $key => $value) {
                $form_array[$key] = $value;
            }

            $default_button_value = 'Записать изменения';
            $default_button_name = 'edit';
            $default_edit_id = (int)$_GET['id']; //для прописи в хидден

        } elseif ($mode == "delete") { //удалить

            //проверим, существует ли ключ в соответствии
            if (array_key_exists($id,  $ads_array)) {

                unset($ads_array[$id]); //обнуляем объявление, в случае единств. объявления -в файле сериал. пустой массив

                //поместим в файл
                write_to_file($ads_array);

             } else {

                echo "Передан неверный ID объявления";
            }
        }
    }
}

$smarty->assign('form_array', $form_array);

//email, значение для checked
$smarty->assign('is_allow_mail', $form_array['allow_mails'] == 1 ? 'checked' :'');

//город, значение для selected
$smarty->assign('city_id', $form_array['location_id']);



//button
$smarty->assign('button_name', $default_button_name);
$smarty->assign('button_value', $default_button_value);
$smarty->assign('default_edit_id',$default_edit_id);

/*
//создадим массив для вывода
$arr_all_ads = [];

//можно добавлять таблицу {foreach}
//можно сделать сквозной массив  и его поместить в {html_table}

foreach ($ads_array as $ad_key => $ad) {

    $arr_one_ad = [];
    foreach ($ad as $key => $value) {
        switch ($key) {
            case 'title':
                $arr_one_ad[$key] = '<a href="?id=' . $ad_key . '&mode=show">' . $value . '</a>';
                break;
            case 'price':
            case 'seller_name':
                $arr_one_ad[$key] = $value;
                break;
        }
    }
    $arr_one_ad['action']='<a href="?id='.$ad_key. '&mode=delete">Удалить</a>';
    $arr_all_ads[] = $arr_one_ad;
}*/

$smarty->assign('arr_ads', $ads_array);
// $smarty->assign('qty_of_ads', count($arr_all_ads));

$smarty->display('index.tpl');
?>