<?php


class Post {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function generateId ($length = 10) {
        $id = "pst_";

        for ($i = 0; $i < $length; $i++) { 
            $id .= rand(0, 9);
        }

        return $id;
    }

    public function createPost(array $post)
    {
        extract($post);

        try {
            $id = $this->generateId();


            $query = "INSERT INTO `post_table`(`school_id`, `post_id`, `title`, `content`, `image`) VALUES (:school_id, :post_id, :title, :content, :image)";
            $result = $this->connection->prepare($query);
            $result->execute([
                'school_id' => $schoolId,
                'post_id' => $id,
                'title' => $title,
                'content' => $content,
                'image' => $image,
            ]);

            if($result){
                $data = $this->getPost($id);

                $response = [
                    'status' => 201,
                    'data' => $data['data'],
                    'error' => null
                ];

                return $response;
            }

        }

        catch (PDOException $e) {
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }

    }

    public function getAllPosts () 
    {
        try{
            $query = "SELECT * FROM `post_table`";
            $result = $this->connection->prepare($query);
            $result->execute();

            if($result->rowCount()){
                $response = [
                    'status' => 200,
                    'data' => $result->fetchAll(),
                    'error' => null
                ];

                return $response;
            }
        }

        catch (PDOException $e){
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }
        
    }

    public function getPost ($id) 
    {
        try{
            $query = "SELECT * FROM `post_table` WHERE `post_id` = ?";
            $result = $this->connection->prepare($query);
            $result->execute([$id]);

            if($result->rowCount()){
                $response = [
                    'status' => 200,
                    'data' => $result->fetch(),
                    'error' => null
                ];

                return $response;
            }
        }

        catch (PDOException $e){
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }
        
    }

    public function deletePost($id) 
    {
        $post = $this->getPost($id);

        if($post['status'] === 200){
            try {
                $query = "DELETE FROM `post_table` WHERE `post_id` = ?";
                $result = $this->connection->prepare($query);
                $result->execute([$id]);

                if($result){
                    $response = [
                        'status' => 200,
                        'data' => $post['data'],
                        'error' => null
                    ];

                    return $response;
                }
            } 
            catch (PDOException $e) {
                $response = [
                    'status' => 500,
                    'data' => null,
                    'error' => $e->getMessage(),
                ];

                return $response;
            }
            

        }
    }

    public function updatePost ($id, array $post)
    {
        extract($post);
        
        try {
            $query = "UPDATE `post_table` SET `image` = :image, `title` = :title, `content` = :content WHERE `post_id` = :post_id";
            $result = $this->connection->prepare($query);
            $result->execute([
                'image' => $image,
                'title' => $title,
                'content' => $content,
                'post_id' => $id,
            ]);

            if($result){
                $data = $this->getpost ($id);

                if($data['status'] == 200){
                    $response = [
                        'status' => 200,
                        'data' => $data['data'],
                        'error' => null
                    ];

                    return $response;
                }
            }

            
        } 
        
        catch (PDOException $e) {
            $response = [
                'status' => 500,
                'data' => null,
                'error' => $e->getMessage()
            ];

            return $response;
        }
        
    }
}