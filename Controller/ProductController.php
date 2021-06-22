<?php
require("Repository/IMySQLDriver.php");
require("Repository/QueryBuilder.php");
require("config/db.php");
/**
 * Class ProductController
 */
class ProductController implements IMySQLDriver
{
    /**
     * @param $id
     * @return array
     */
    public function detail($id){
        $product = $this->findProduct($id);
        if(empty($product)) {
            print('Product not existing.');
        }else{
            return $product;
        }

    }

    /**
     * @param $id
     * @return array
     */
    public function findProduct($id): array
    {
        if(empty($this->verifyCache($id))) {
            $query = (new QueryBuilder())->select('*')->from('products')->where('id = ?');
            $statement = (new QueryBuilder())->createConn()->prepare($query);
            $statement->bindParam(1, $id, PDO::PARAM_INT);
            try {
                if ($statement->execute()) {
                    if ($statement->rowCount() > 0) {
                        $row = $statement->fetch();
                        $json = $this->getJSON();
                        $product = array('id'=>(int)$row['id'],'name'=>$row['name'],'inquiry'=>1);
                        array_push($json['products'], $product);
                        $this->setJSON($json);
                        return $product;
                    } else {
                        return array();
                    }
                }
            } catch (PDOException $e) {
                print "Error: " . $e->getMessage();
                die();
            }
        }else{
            $this->addInquiry($id);
            return $this->verifyCache($id);
        }
        return array();
    }


    /**
     * @param $id
     * @return array
     */
    private function verifyCache($id): array
    {
        $json = $this->getJSON();
        $filter = array_filter($json['products'], function ($products) use ($id) {return $products['id'] == $id;});
        if($filter == null){
            return array();
        }else{
            return $filter;
        }
    }

    private function addInquiry($id){
        $json = $this->getJSON();
        $filter = array_filter($json['products'], function ($products) use ($id) {return $products['id'] == $id;});
        $json['products'][key($filter)]['inquiry']++;
        $this->setJSON($json);
    }
    private function getJSON(): array
    {
        $file = file_get_contents("config/cache.json");
        return json_decode($file,true);
    }

    private function setJSON($array){
        $encode = json_encode($array);
        file_put_contents("config/cache.json",$encode);
    }
}
