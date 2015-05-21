<?php
namespace CAP\Controller;

use CAP\Model\Mapper\CategoryMapper;
use CAP\Model\Category;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryController
 *
 * @author Eric
 */
class CategoriesController extends AbstractController {
    private $categoryMapper;
    
    private function getCategoryMapper(){
        if(! $this->categoryMapper){
            $this->categoryMapper = new CategoryMapper();
        }
        return $this->categoryMapper;
    }
    
    public function getAllAction(){
        $res = $this->getCategoryMapper()->fetchAllCategories();
        echo json_encode($res);
    }
    
    public function getAction(){
        $id = (int)$_GET['id'];
        try{
            $res = $this->getCategoryMapper()->getCategory($id);
        }catch(\Exception $e){
            if($e->getMessage() == "Row $id not found"){
                header('HTTP/1.0 404 Not Found');
                exit;
            }
        }
        echo json_encode($res);
    }
    
    public function deleteAction(){
        $params = $this->getParams();
        
        if(isset($params['id'])){
            $id = (int)$params['id'];
            if($this->getCategoryMapper()->deleteCategory($id)){
                echo json_encode(array('data' => 'deleted'));
            }else{
                echo json_encode(array('data' => 'undeleted','errors' => 'Wrong id provided'));
                return;
            }
        }else{
            echo json_encode(array('data' => 'undeleted','errors' => 'No id provided'));
            return;
        }
    }
    
    public function addAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oCat = json_decode($json);
            $cat = new Category((array)$oCat);
        }else{
            $params = $this->getParams();
            $cat = new Category($params);
        }

        $map = $this->getCategoryMapper();
        $id = $map->saveCategory($cat);
        $rtrvCat = $map->getCategory($id);
        
        echo json_encode($rtrvCat);
    }
    
    public function updateAction(){
        if( strpos($_SERVER['CONTENT_TYPE'],strtolower('application/json')) !== FALSE  ){
            $json = file_get_contents('php://input');
            $oCat = json_decode($json);
            $cat = new Category((array)$oCat);
        }else{
            $params = $this->getParams();
            $cat = new Category($params);
        }

        $map = $this->getCategoryMapper();
        $map->saveCategory($cat);
        $rtrvCat = $map->getCategory($cat->id);
        
        echo json_encode($rtrvCat);
    }
}