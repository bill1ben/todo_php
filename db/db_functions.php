<?php
include "DbPdo.php";

class DB_TODO {


    private static function db_connexion()
    {
        return DbPDO::pdoConnexion();
    }

    public static function login($identifiant, $mdp)
    {
        $con = self::db_connexion();
        $query = $con->prepare("SELECT * FROM `user` WHERE username= :identifiant and password= :password;");
        $query->execute(array(':identifiant' => $identifiant, ':password' => sha1($mdp) ));
        $count = $query->rowCount();
        if($count == 1){

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION["userId"] = $row["id_user"];
                $_SESSION['user'] = $row["username"];
                $_SESSION["userEmail"] = $row["email"];
            }
        }
        return $count == 1;
    }

    public static function createTODO($title, $id_priority, $description = 'NULL', $done = '0', $lastInsertIdUrlImage = 'NULL')
    {
        $con = self::db_connexion();
        $query = $con->prepare("INSERT INTO todo (`id_todo`, `title`, `description`, `done`, `id_priority`, `id_url_image`) 
                                VALUES ( NULL, :title , :description, :done, :id_priority, :id_url_image);");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":title" => $title,
            ":description" => $description,
            ":done"=>$done,
            ":id_priority"=> $id_priority,
            ":id_url_image" => $lastInsertIdUrlImage
        ));

        return $con->lastInsertId();
    }

    public static function getTODOById($id_todo)
    {
        $con = self::db_connexion();
        $query = $con->prepare("SELECT * 
                                            FROM `todo`
                                            LEFT JOIN `url_image` ON `todo`.`id_url_image` = `url_image`.`id_url_image` 
                                            WHERE `todo`.`id_todo` = :id_todo;");
        $query->execute(array( ":id_todo" => $id_todo ));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $row['items'] = DB_ITEM::getAllByTodo($id_todo);
        return $row;
    }

    public static function deleteTodoImageById($id_todo)
    {
        $con = self::db_connexion();
        $query = $con->prepare("UPDATE todo
                                SET `id_url_image` = NULL
                                WHERE `id_todo` = :id_todo;");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":id_todo" => $id_todo
        ));

        return $result;
    }
    public static function updateTODOById($id_todo, $title, $id_priority, $description, $done, $id_url_image = 'NULL')
    {
        $con = self::db_connexion();
        $query = $con->prepare("UPDATE todo
                                SET `title` = :title, 
                                    `description`= :description, 
                                    `done` = :done,
                                    `id_priority` = :id_priority,
                                    `id_url_image` = :id_url_image
                                WHERE `id_todo` = :id_todo;");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":id_todo" => $id_todo,
            ":title" => $title,
            ":description" => $description,
            ":done"=>$done,
            ":id_priority"=>$id_priority,
            ":id_url_image"=>$id_url_image
        ));

        return $result;
    }

    public static function deleteTODO($id_todo)
    {
        $con = self::db_connexion();
        $query = $con->prepare("DELETE FROM todo 
                                        WHERE `id_todo` = :id_todo ;");
        $result = $query->execute(array(":id_todo"=>$id_todo));
        return $result;
    }

    public static function getAllTODO()
    {
        $con = self::db_connexion();
        $request =
            "SELECT * , COALESCE(`nb_items`, 0) AS `nb_comments`
                FROM `todo` 
                LEFT JOIN `url_image` ON `todo`.`id_url_image` = `url_image`.`id_url_image`
                LEFT JOIN ( SELECT `item`.`id_todo` AS `id_todo_item`, COUNT(*) AS `nb_items` 
                           FROM `item` GROUP BY `item`.`id_todo`) AS `nb_items`
                           ON `todo`.`id_todo` = `nb_items`.`id_todo_item`
                INNER JOIN `priority` HAVING `todo`.`id_priority` = `priority`.`id_priority` 
                ORDER BY `priority`.`value` DESC, `todo`.`id_todo` DESC;";
        $queryTodos = $con->prepare($request);
        $queryTodos->execute();

        $rows = [];
        while( $row = $queryTodos->fetch(PDO::FETCH_ASSOC) )
        {
            $id_todo = $row['id_todo'];
            $row['labels'] = DB_LABEL::getTodoLabelByTodo($id_todo);
            array_push($rows, $row);
        }
        return $rows;
    }

    public static function getAllPriorities()
    {
        $con = self::db_connexion();
        $queryTodos = $con->prepare("SELECT * FROM `priority` ORDER BY `value` DESC");
        $queryTodos->execute();

        $rows = [];
        while( $row = $queryTodos->fetch(PDO::FETCH_ASSOC) )
        {
            array_push($rows, $row);
        }
        return $rows;
    }

    public static function createUrlImage($url_image)
    {
        $con = self::db_connexion();
        $query = $con->prepare("INSERT INTO url_image (`id_url_image`, `url_image`) 
                                VALUES ( NULL, :url_image);");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $query->execute( array(
            ":url_image"=> $url_image
        ));

        return $con->lastInsertId();
    }

    public static function updateImageUrl($id_url_image, $url_image)
    {
        $con = self::db_connexion();
        $query = $con->prepare("UPDATE url_image
                                SET `url_image` = :url_image
                                WHERE `id_url_image` = :id_url_image;");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":id_url_image" => $id_url_image,
            ":url_image" => $url_image
        ));

        return $result;
    }

    public static function deleteImageUrl($id_url_image)
    {
        $con = self::db_connexion();
        $query = $con->prepare("DELETE FROM url_image 
                                        WHERE `id_url_image` = :id_url_image ;");
        $result = $query->execute(array(":id_url_image"=>$id_url_image));
        return $result;
    }
}
class DB_LABEL {
    private static function db_connexion()
    {
        return DbPDO::pdoConnexion();
    }

    public static function getAllLABEL(){
        $con = self::db_connexion();
        $request =
            "SELECT * 
                FROM `label` 
                ORDER BY `label`.`name_label` ASC;";
        $query = $con->prepare($request);
        $query->execute();

        $rows = [];
        while( $row = $query->fetch(PDO::FETCH_ASSOC) )
        {
            array_push($rows, $row);
        }
        return $rows;
    }

    public static function getLabelById($id_label)
    {
        $con = self::db_connexion();
        $query = $con->prepare("SELECT * 
                                            FROM `label`
                                            WHERE `label`.`id_label` = :id_label;");
        $query->execute(array( ":id_label" => $id_label ));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public static function createLABEL($name_label)
    {
        $con = self::db_connexion();
        $query = $con->prepare("INSERT INTO label (`id_label`, `name_label`) 
                                VALUES ( NULL, :name_label);");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":name_label" => $name_label
        ));

        return $result;
    }

    public static function updateLabel($id_label, $name_label) {
        $con = self::db_connexion();
        $query = $con->prepare("UPDATE label
                                SET `name_label` = :name_label 
                                WHERE `id_label` = :id_label;");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":id_label" => $id_label,
            ":name_label" => $name_label
        ));

        return $result;
    }

    public static function deleteLabel($id_label) {
        $con = self::db_connexion();
        $query = $con->prepare("DELETE FROM label 
                                        WHERE `id_label` = :id_label ;");
        $result = $query->execute(array(":id_label"=>$id_label));
        return $result;
    }

    public static function associateTodoLabel($id_todo, $id_label)
    {
        $con = self::db_connexion();
        $query = $con->prepare("INSERT INTO todo_label (`id_todo_label`, `id_todo`,`id_label`) 
                                VALUES ( NULL, :id_todo, :id_label);");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":id_todo" => $id_todo,
            ":id_label" => $id_label
        ));

        return $result;
    }

    public static function getTodoLabelByTodo($id_todo)
    {
        $con = self::db_connexion();
        $query = $con->prepare("SELECT * 
                                            FROM `todo_label`
                                            INNER JOIN `label`
                                            ON `label`.`id_label` = `todo_label`.`id_label`
                                            WHERE `todo_label`.`id_todo` = :id_todo;");
        $query->execute(array( ":id_todo" => $id_todo ));
        $rows = [];
        while( $row = $query->fetch(PDO::FETCH_ASSOC) )
        {
            array_push($rows, $row);
        }
        return $rows;
    }

    public static function deleteAllTodoLabelByTodo($id_todo)
    {
        $con = self::db_connexion();
        $query = $con->prepare("DELETE FROM `todo_label`
                                            WHERE `todo_label`.`id_todo` = :id_todo;");
        return $query->execute(array( ":id_todo" => $id_todo ));
    }

    public static function deleteAllTodoLabelByLabel($id_label) {
        $con = self::db_connexion();
        $query = $con->prepare("DELETE FROM `todo_label`
                                            WHERE `todo_label`.`id_label` = :id_label;");
        return $query->execute(array( ":id_label" => $id_label ));
    }
}

class DB_ITEM {
    private static function db_connexion()
    {
        return DbPDO::pdoConnexion();
    }

    public static function createItem($id_todo, $desc_item){
        $con = self::db_connexion();
        $query = $con->prepare("INSERT INTO item (`id_item`, `id_todo`, `desc_item`) 
                                VALUES ( NULL, :id_todo, :desc_item);");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":id_todo" => $id_todo,
            ":desc_item" => $desc_item
        ));

        return $result;
    }

    public static function getAllByTodo($id_todo)
    {
        $con = self::db_connexion();
        $query = $con->prepare("SELECT * 
                                            FROM `item`
                                            WHERE `item`.`id_todo` = :id_todo;");
        $query->execute(array( ":id_todo" => $id_todo ));
        $rows = [];
        while( $row = $query->fetch(PDO::FETCH_ASSOC) )
        {
            array_push($rows, $row);
        }
        return $rows;
    }

    public static function deleteItem($id_item){
        $con = self::db_connexion();
        $query = $con->prepare("DELETE FROM `item`
                                        WHERE `item`.`id_item` = :id_item;");
        return $query->execute(array( ":id_item" => $id_item ));
    }

    public static function updateItem($id_item, $desc_item) {
        $con = self::db_connexion();
        $query = $con->prepare("UPDATE item
                                SET `desc_item` = :desc_item 
                                WHERE `id_item` = :id_item;");

        if (!$query)
        {
            print_r($con->errorInfo());
        }

        $result = $query->execute( array(
            ":id_item" => $id_item,
            ":desc_item" => $desc_item
        ));

        return $result;
    }

    public static function deleteAllItemByTodo($id_todo){
        $con = self::db_connexion();
        $query = $con->prepare("DELETE FROM `item`
                                        WHERE `item`.`id_todo` = :id_todo;");
        return $query->execute(array( ":id_todo" => $id_todo ));
    }
}
