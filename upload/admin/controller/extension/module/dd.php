<?php
class ControllerExtensionModuleDd extends Controller {
	private $error = array();
    private $categories = array(); // массив из фида

    private $level_0 = [];
    private $level_1 = [];
    private $level_2 = [];
    private $level_3 = [];
    private $tree;

    private $messages = '';
    private $category_map = [];

//    private $content    = array(); // суммарный массив из фида $categories[] + $offers[]
//    private $catalog    = array(); // массив из магазина
//    private $xml_content;

	public function index() {
		$this->load->language('extension/module/dd');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_dd', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension',
                'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/dd', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/dd', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension',
            'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['module_dd_status'])) {
            $data['module_dd_status'] = $this->request->post['module_dd_status'];
        } else {
            $data['module_dd_status'] = $this->config->get('module_dd_status');
        }

        if (isset($this->request->post['module_dd_xmlurl'])) {
            $data['module_dd_xmlurl'] = $this->request->post['module_dd_xmlurl'];
        } else {
            $data['module_dd_xmlurl'] = $this->config->get('module_dd_xmlurl');
        }

        $data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer']      = $this->load->controller('common/footer');

		$data['user_token']  = $this->session->data['user_token'];

//        $data['error_warning'] = simplexml_load_file("./content_yml.xml");

        $this->getXmlCategories();
		$data['xml_categories'] = $this->categories;

		$data['CategoryTree'] = $this->getCategoryTree();
        $data['tree'] = $this->tree;

        $data['messages'] = $this->messages;
        $data['level_0'] = $this->level_0;
        $data['level_1'] = $this->level_1;
        $data['level_2'] = $this->level_2;

//      echo '<pre>'; print_r($this->categories); echo '</pre>';

		$this->response->setOutput($this->load->view('extension/module/dd', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/information')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	private function getCategoryTree(){

        if( file_exists("./content_yml.xml") ) {
            $feed_file   = file_get_contents("./content_yml.xml");
            $xml_content = simplexml_load_file("./content_yml.xml");
        } else {
//            $feed_file   = file_get_contents($this->config->get('module_dd_xmlurl'));
//            $xml_content = simplexml_load_file($this->config->get('module_dd_xmlurl'));
        }

        if( $xml_content && $this->config->get('module_dd_status') ) {

            $categories = [];
            $int = 0;
            foreach ($xml_content->shop->categories->category as $row) {
                $categories[$int]['id']   = (int)    $row['id'];
                $categories[$int]['name'] = (string) $row[0];
                $int ++;
            }

            $int = 0;
            foreach ($xml_content->shop->categories->category as $row) {
                $categories[$int]['id']         = (int)    $row['id'];
                $categories[$int]['name']       = (string) $row[0];
                $categories[$int]['parentId']   = (int)    $row['parentId'];
                $categories[$int]['parentName'] = $this->getCategoryName($this->categories, $row['parentId']);
                $int ++;
            }

            for($i = 0; $i < count($categories); $i++) {
                // level_0
                if( empty($categories[$i]['parentName']) ){
                    $this->level_0[] = array( 'id' => $categories[$i]['id'], 'name' => $categories[$i]['name']);
                }
            }

            for($i = 0; $i < count($categories); $i++) {
                // level_1
                if( $this->in_level_0($this->level_0, $categories[$i]['parentId']) ){
                    $this->level_1[] = array(
                        'id'         => $categories[$i]['id'],
                        'name'       => $categories[$i]['name'],
                        'parentId'   => $categories[$i]['parentId'],
                        'parentName' => $categories[$i]['parentName']
                    );
                }
            }

            for($i = 0; $i < count($categories); $i++) {
                // level_2
                if( $this->in_level_1($this->level_1, $categories[$i]['parentId']) ){
                    $this->level_2[] = array(
                        'id'         => $categories[$i]['id'],
                        'name'       => $categories[$i]['name'],
                        'parentId'   => $categories[$i]['parentId'],
                        'parentName' => $categories[$i]['parentName']
                    );
                }
            }

            for($i = 0; $i < count($categories); $i++) {
                // level_2
                if( $this->in_level_2($this->level_2, $categories[$i]['parentId']) ){
                    $this->level_3[] = array(
                        'id'         => $categories[$i]['id'],
                        'name'       => $categories[$i]['name'],
                        'parentId'   => $categories[$i]['parentId'],
                        'parentName' => $categories[$i]['parentName']
                    );
                }
            }
        }

        $this->tree = '<form action="" id="form-tree">';

        $count_level2 = 0;

        $this->load->model('catalog/category');
        $local_categories = $this->model_catalog_category->getCategories();

        for($i_0 = 0; $i_0 < count($this->level_0); $i_0++){

            for($i_1 = 0; $i_1 < count($this->level_1); $i_1++) {

                if($this->level_1[$i_1]['parentId'] == $this->level_0[$i_0]['id']) {

                    $data_category_name = $this->level_0[$i_0]['name'].';'.$this->level_1[$i_1]['name'];

                    $this->tree .= '<input type="checkbox" name="category_'.$this->level_1[$i_1]['id'].
                        '" id="category_'.$this->level_1[$i_1]['id'].
                        '" data-category-name="'.$data_category_name.
                        '" data-category-level="1" /> ';

                    $this->tree .= $this->level_0[$i_0]['name'] . ' > '. $this->level_1[$i_1]['name'] . ' ';

//                    $this->tree .= '<input type="text" name="from_category_'.$this->level_1[$i_1]['id'].'" id="from_category_'.$this->level_1[$i_1]['id'].'" />'.'<br>';

                    $this->tree .= ' >>> <select id="local_category_'.$this->level_1[$i_1]['id'].'"><option value="0">'.$this->level_0[$i_0]['name'] . ' > '. $this->level_1[$i_1]['name'].'</option>';
                    foreach ($local_categories as $cat){
                        $this->tree .= '<option value="'.$cat['category_id'].'">'.$cat['name'].'</option>';
                    }
                    $this->tree .= '</select><br>';

                    for($i_2 = 0; $i_2 < count($this->level_2); $i_2++) {

                        if( $this->level_2[$i_2]['parentId'] == $this->level_1[$i_1]['id'] ) {

                                $category_id = $this->level_2[$i_2]['id'];

                                $data_category_name = $this->level_0[$i_0]['name'] .';'.
                                                        $this->level_1[$i_1]['name'] .';'.
                                                        $this->level_2[$i_2]['name'];

                                $this->tree .= '<input type="checkbox" name="category_'.
                                                $category_id.'" id="category_'.$category_id.'"';
                                $this->tree .= ' data-category-name="'.$data_category_name.
                                    '" style="margin-left:10px" data-category-name="'.
                                    $data_category_name.'" data-category-level="2" data-parent_id='.$this->level_2[$i_2]['parentId'].'> ';
                                $this->tree .= $this->level_2[$i_2]['name'] . '<br>';
                                $count_level2++;
                        }
                    }
                    $this->tree .= '<br>';
                }
            }
        }
        $this->tree .= '</form>';
        $this->tree .= 'level_2 categories <pre>'.$count_level2.'</pre>';

        return $this->tree;
	}

    private function in_level_0($level_0, $id){
        foreach ($level_0 as $row) {
            if( $id == $row['id'] ) { return true; }
        }
        return false;
    }

    private function in_level_1($level_1, $id){
        foreach ($level_1 as $row) {
            if( $id == $row['id'] ) { return true; }
        }
        return false;
    }

    private function in_level_2($level_2, $id){
        foreach ($level_2 as $row) {
            if( $id == $row['id'] ) { return true; }
        }
        return false;
    }

    public function ajax_form(){
//        echo json_encode($this->request->post);
        echo 'Run function ajax_form';
        return true;
    }

    private function getXmlCategories(){

        if( file_exists("./content_yml.xml") ) {
            $feed_file   = file_get_contents("./content_yml.xml");
            $xml_content = simplexml_load_file("./content_yml.xml");
        } else {
            $feed_file   = file_get_contents($this->config->get('module_dd_xmlurl'));
            $xml_content = simplexml_load_file($this->config->get('module_dd_xmlurl'));
        }

	    if( $xml_content && $this->config->get('module_dd_status') ){

//            echo '<pre>' . $xml_content . '</pre>';

//            if($xml_content) {
                $int = 0;
                foreach ($xml_content->shop->categories->category as $row) {
                    $this->categories[$int]['id']   = (int)$row['id'];
                    $this->categories[$int]['name'] = (string)$row[0];
                    $int++;
                }
                $int = 0;
                foreach ($xml_content->shop->categories->category as $row) {
                    $this->categories[$int]['id']         = (int)$row['id'];
                    $this->categories[$int]['name']       = (string)$row[0];
                    $this->categories[$int]['parentId']   = (int)$row['parentId'];
                    $this->categories[$int]['parentName'] = $this->getCategoryName($this->categories, $row['parentId']);
                    $int++;
                }
                return $this->categories;
//            } else { echo '<pre>' . $xml_url . '</pre>'; }
        } else {
	        return false;
        }
    }

    private function getCategoryName($categories, $id){
        foreach ($categories as $row) {
            if( $row['id'] == $id){ return $row['name']; break; }
        }
    }

    public function import(){
        /*
     Array $_POST[]
    (
        [0] => Array
            (
                [category_id] => 49
                [data_category_name] => Квадрокоптеры;Детские
                [local_category_id] => 0
                [level] => 1
                [parent_id] => 0
            )

        [1] => Array
            (
                [category_id] => 50
                [data_category_name] => Квадрокоптеры;Детские;Нано
                [local_category_id] => 0
                [level] => 2
                [parent_id] => 49
            )

        category_id - идентификатор категории из фида
        data_category_name - наименование категории вместе с родительскими из фида
        local_category_id - идентификатор локальной категории, которая будет родительской для отмеченных ниже категорий
        level - уровень вложенности, 2 - последний уровень, 1 - родительская
        parent_id - идентификатор родительской категории из фида

        Формируем данные для создания категории.
        Проверяем существование такой категории и при ее отсутсвии - создаем ее.

     Array $local_categories
            (
                [category_id] => 35
                [name] => Components  >  Monitors  >  test 1
                [parent_id] => 28
                [sort_order] => 0
            )
    )
      * */
	    $data = $this->request->post['data'];
	    $decode_data = json_decode(html_entity_decode($data),true);
//        echo print_r($decode_data,true);

        $this->load->model('catalog/category');
        $local_categories = $this->model_catalog_category->getCategories();
//        echo print_r($local_categories,true);

        $category_exists = false;

        foreach ($decode_data as $row) {
            $feed_category_name = explode(";", $row['data_category_name'])[$row['level']];
            $feed_category_id   = $row['category_id'];
            $local_category_id  = $row['local_category_id'];

            $data = [];

            foreach ($local_categories as $local_category) {
                $tmp = explode("  >  ", $local_category['name']);
                $local_cat_name = $tmp[count($tmp)-1];
                if(
                    $local_category['parent_id'] == $local_category_id &&
                    $local_cat_name == $feed_category_name
                ) { $category_exists = true; }
            }

            if( $category_exists ){ next; }

            if ($local_category_id) {
                // если уже есть родительская, создаем дочернюю
                $data['parent_d'] = $local_category_id;

            } else {
                // создаем родительскую, а затем дочернюю
                $data = ['category_description' => [ '1' => ['name' => $feed_category_name,
                        'meta_title' => $feed_category_name, 'description' => '', 'meta_description' => '', 'meta_keyword' => '' ]],
	    	        'category_store' => [ '0' => 0 ],
    		        'top' => 1,
    		        'column' => 1,
    		        'sort_order' => 0,
    		        'status' => 1,
			        'parent_id' => 0
                ];
            }
        }
    }

    public function ajax_jstree($ajax = true)
    {
        // 1. создаем XML объект из фида
        if(file_exists("./content_yml.xml")){
            $xml_content = simplexml_load_file("./content_yml.xml");
        } else {
            // загружаем фид из URL
            exit();
        }

        // Первым проходом создаем массив категорий с идентификаторами и именами
        $categories = [];
        $int = 0;
        foreach ($xml_content->shop->categories->category as $row) {
            $categories[$int]['id']   = (int)$row['id'];
            $categories[$int]['name'] = (string)$row[0];
            $int++;
        }
        // Вторым проходом добавляем ключи для дочерних категорий parentId parentName,
        // второй проход нужен чтобы взять имена родительских категорий
        $int = 0;
        foreach ($xml_content->shop->categories->category as $row) {
            $categories[$int]['id']         = (int)$row['id'];
            $categories[$int]['name']       = (string)$row[0];
            $categories[$int]['parentId']   = (int)$row['parentId'];
            $categories[$int]['parentName'] = $this->getCategoryName($categories, $row['parentId']);
            $int++;
        }

        // Создаем для каждого уровня вложенности свой массив. В фиде всего три уровня вложенности.
        $level_0 = [];
        $level_1 = [];
        $level_2 = [];

        for($i = 0; $i < count($categories); $i++) {
            // level_0
            if( empty($categories[$i]['parentName']) ){
                $level_0[] = array( 'id' => $categories[$i]['id'], 'name' => $categories[$i]['name']);
            }
        }

        for($i = 0; $i < count($categories); $i++) {
            // level_1
            if( $this->in_level_0($level_0, $categories[$i]['parentId']) ){
                $level_1[] = array(
                    'id'         => $categories[$i]['id'],
                    'name'       => $categories[$i]['name'],
                    'parentId'   => $categories[$i]['parentId'],
                    'parentName' => $categories[$i]['parentName']
                );
            }
        }

        for($i = 0; $i < count($categories); $i++) {
            // level_2
            if( $this->in_level_1($level_1, $categories[$i]['parentId']) ){
                $level_2[] = array(
                    'id'         => $categories[$i]['id'],
                    'name'       => $categories[$i]['name'],
                    'parentId'   => $categories[$i]['parentId'],
                    'parentName' => $categories[$i]['parentName']
                );
            }
        }

        $data2 = $this->config->get('module_dd_selected');
        $selected = explode(",", $data2);

        // Создаем массив для кодировки в json для jstree плагина.
        // Первый уровень.
        for($i = 0; $i < count($categories); $i++) {
            if( empty($categories[$i]['parentName']) ){
                $data_json[] = [ 'id' => $categories[$i]['id'], 'text' => $categories[$i]['name'] ];
            }
        }
        for($i = 0; $i < count($categories); $i++) {
            // Второй уровень
            if( ! empty($categories[$i]['parentName']) && $this->in_level_0($level_0, $categories[$i]['parentId']) ){
                for($j = 0; $j < count($data_json); $j++ ){
                    if( $data_json[$j]['id'] == $categories[$i]['parentId'] ){
                        if ( in_array($categories[$i]['id'], $selected ) ) {
                            $data_json[$j]['children'][] = [
                                'id' => $categories[$i]['id'], 'text' => $categories[$i]['name'],
                                'state' => ['selected' => 1, 'opened' => 1]
                            ];
                        } else {
                            $data_json[$j]['children'][] = [
                                'id' => $categories[$i]['id'], 'text' => $categories[$i]['name'],
                            ];
                        }
                    }
                }
            }
            // Третий уровень
            if( ! empty($categories[$i]['parentName']) && $this->in_level_1($level_1, $categories[$i]['parentId']) ){

                for($j = 0; $j < count($data_json); $j++ ){

                    if ( array_key_exists('children', $data_json[$j]) )  {
                        for($k = 0; $k < count($data_json[$j]['children']); $k++){
                            if( $data_json[$j]['children'][$k]['id'] == $categories[$i]['parentId'] ){
                                if( in_array($categories[$i]['id'], $selected) ) {
                                    $data_json[$j]['children'][$k]['children'][] = [
                                        'id' => $categories[$i]['id'],
                                        'text' => $categories[$i]['name'],
                                        'state' => ['selected' => 1, 'opened' => 1]
                                    ];
                                } else {
                                    $data_json[$j]['children'][$k]['children'][] = [
                                        'id' => $categories[$i]['id'],
                                        'text' => $categories[$i]['name']
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        // echo print_r($data_json, true);
        if($ajax) { echo json_encode($data_json); }

        return $data_json;
    }

    public function ajax_jstree_send(){
        $this->load->model('setting/setting');
        $this->load->model('catalog/category');

        // сохраняем в конфигурации список выбранных категорий
        $data = $this->request->post['data'];
        $this->model_setting_setting->editSetting( 'module_dd_selected', ['module_dd_selected' => $data ] );

        $selected_feed_category = explode(",", $this->request->post['data']);
        sort($selected_feed_category, SORT_NUMERIC);
	    $feed_category = $this->ajax_jstree(false);
//	    $this->messages .= print_r($feed_category, true);

	    // Создаем массив выбранных категорий с родительскими категориями
	    $category = [];
	    // первый цикл по выбранным категориям
	    foreach ($selected_feed_category as $selected_id){
	        // следующий цикл по категориям level_0
	        foreach($feed_category as $category_level_0){
	            // наименование категории level_0
	            $level_0 = $category_level_0['text'];
	            if( array_key_exists('children', $category_level_0) ) {
                    foreach ($category_level_0['children'] as $category_level_1) {
                        $level_1 = $category_level_1['text'];
                        if (array_key_exists('children', $category_level_1)) {
                            foreach ($category_level_1['children'] as $category_level_2) {
                                $level_2 = $category_level_2['text'];
                                if ($category_level_2['id'] == $selected_id) {
                                    $category[] = [
                                        'id' => $selected_id,
                                        'level_0' => $level_0,
                                        'level_1' => $level_1,
                                        'level_2' => $level_2
                                    ];
                                }
                            }
                        } else {
                            if ($category_level_1['id'] == $selected_id) {
                                $category[] = [
                                    'id' => $selected_id,
                                    'level_0' => $level_0,
                                    'level_1' => $level_1
                                ];
                            }
                        }
                    }
                } else {
                    if ($category_level_0['id'] == $selected_id) {
                        $category[] = [
                            'id' => $selected_id,
                            'level_0' => $level_0
                        ];
                    }
                }
            }
        }

	    $language_id = (int)$this->config->get('config_language_id');
        $data = [
            'category_description' => [ $language_id =>
                [
                    'description' => '',
                    'meta_description' => '',
                    'meta_keyword' => ''
                ]
            ],
            'category_store' => [ '0' => 0 ],
            'top' => 1,
            'column' => 1,
            'sort_order' => 0,
            'status' => 1,
            'parent_id' => 0,
            'image' => '',
        ];

//        $this->messages .= print_r($category, true);

//        $category_map = [];

	    foreach ($category as $cat){
            $local_category = $this->model_catalog_category->getCategories( [ 'parent_id' => 0,
                'filter_name' => $cat['level_0'] ] );
            if( empty($local_category) ){
                // создаем категорию с именем $cat['level_0']
                $data['category_description'][$language_id]['name'] = $cat['level_0'];
                $data['category_description'][$language_id]['meta_title'] = $cat['level_0'];
                $data['parent_id'] = 0;
                $id = $this->model_catalog_category->addCategory($data);
                $data['parent_id'] = $id;
                $this->category_map[$cat['id']] = $id;
                $this->messages .= "создаем категорию: ".$cat['level_0']." id = ".$id." level_0<br>";
            } else {
                    $data['parent_id'] = $local_category[0]['category_id'];
                    $this->category_map[$cat['id']] = $local_category[0]['category_id'];
//                    $this->messages .= $cat['level_0'] . ' => $this->category_map[' . $cat['id'] . '] = ' . $local_category[0]['category_id'] . ' level_0<br>';
            }

            if( array_key_exists('level_1', $cat) ) {
                $local_category = $this->model_catalog_category->getCategories( [ 'parent_id' => $data['parent_id'],
                    'filter_name' => $cat['level_1'] ] );
                if ( empty($local_category) ) {
                    // создаем категорию с именем $cat['level_1']
                    $data['category_description'][$language_id]['name'] = $cat['level_1'];
                    $data['category_description'][$language_id]['meta_title'] = $cat['level_1'];
                    $id = $this->model_catalog_category->addCategory($data);
                    $data['parent_id'] = $id;
                    $this->category_map[$cat['id']] = $id;
                    $this->messages .= "создаем категорию: ".$cat['level_1']." id = ".$id." level_1<br>";
                } else {
                    $data['parent_id'] = $local_category[0]['category_id'];
                    $this->category_map[$cat['id']] = $local_category[0]['category_id'];
//                        $this->messages .= $cat['level_1'] . ' => $this->category_map[' . $cat['id'] . '] = ' . $local_category[0]['category_id'] . ' level_1<br>';
                }
            }

            if( array_key_exists('level_2', $cat) ) {
                $local_category = $this->model_catalog_category->getCategories( [ 'parent_id' => $data['parent_id'],
                    'filter_name' => $cat['level_2'] ] );
                if ( empty($local_category) ) {
                    // создаем категорию с именем $cat['level_2']
                    $data['category_description'][$language_id]['name'] = $cat['level_2'];
                    $data['category_description'][$language_id]['meta_title'] = $cat['level_2'];
                    $id = $this->model_catalog_category->addCategory($data);
                    $this->category_map[$cat['id']] = $id;
                    $this->messages .= "создаем категорию: ".$cat['level_2']." id = ".$id." level_2<br>";
                } else {
                    $this->category_map[$cat['id']] = $local_category[0]['category_id'];
//                    $this->messages .= $cat['level_2'] . ' => $this->category_map[' . $cat['id'] . '] = ' . $local_category[0]['category_id'] . ' Level_2<br>';
                }
            }
        }

	    $this->messages .= print_r($this->category_map, true);
        echo print_r($this->messages, true);
    }

    // для проверки сохранения конфигурации
    public function ajax_jstree_save(){

        $this->load->model('setting/setting');
        $data = $this->request->post['data'];
        $this->model_setting_setting->editSetting( 'module_dd_selected', ['module_dd_selected' => $data ] );

        echo print_r($this->request->post['data'], true);
    }

}
