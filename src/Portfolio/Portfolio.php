<?php


class Portfolio
{
    public function getInfo($id)
    {
        $infoQuery = "SELECT * FROM info WHERE id_user LIKE '" . $id . "'";
        $db = $this->dataBase($infoQuery);
        if ($data = mysqli_fetch_array($db)) {
            $foto = $data['profile'];
            $titulo = $data['titulo'];
            $subtitulo = $data['subtitulo'];
        }

        $data = [
            'profile' => $foto,
            'titulo' => $titulo,
            'subtitulo' => $subtitulo
        ];
        return $data;
    }

    public function getProjects($id)
    {
        $infoQuery = "SELECT * FROM projects WHERE id_user = '" . $id . "'";
        $db = $this->dataBase($infoQuery);
        $projects = array();

        while ($data = mysqli_fetch_array($db)) {
            $project = array(
                'print' => $data['print'],
                'project_name' => $data['nome_projeto'],
                'url_project' => $data['url']
            );
            $projects[] = $project;
        }

        return $projects;
    }

    public function getSkills($id)
    {
        $infoQuery = "SELECT * FROM skills WHERE id_user LIKE '" . $id . "'";
        $db = $this->dataBase($infoQuery);

        $skills = array();
        while ($data = mysqli_fetch_array($db)) {
            $skill = array(
                'skill' => $data['logo'],
            );
            $skills[] = $skill;
        }
        return $skills;
    }
    public function getOthers($id)
    {
        $infoQuery = "SELECT * FROM others WHERE id_user LIKE '" . $id . "'";
        $db = $this->dataBase($infoQuery);
        $others = array();
        while ($data = mysqli_fetch_array($db)) {
            $other = array(
                'id' => $data['id'],
                'banner' => $data['banner'],
                'banner_url' => $data['url'],
            );
            $others[] = $other;
        }

        return $others;
    }

    public function getSocial($id)
    {
        $infoQuery = "SELECT * FROM social WHERE id_user LIKE '" . $id . "'";
        $db = $this->dataBase($infoQuery);
        if ($data = mysqli_fetch_array($db)) {
            $github = $data['github'];
            $linkedin = $data['linkedin'];
            // $email = $data['email'];
        }

        $data = [
            'github' => $github,
            // 'email' => $email,
            'linkedin' => $linkedin
        ];
        return $data;
    }


    public function setImage($file, $typePicture)
    {
        $hoje = date("d-m-y");
        $ext = explode(".", $file['name']); //[foto][ferias][jpg]
        $ext = array_reverse($ext); //[jpg][ferias][foto]
        $ext = $ext[0]; //jpg


        if ((!isset($file))) {
            $path = "Arquivo inexistente!";
        } else {
            if ($ext != "jpg" && $ext != "png" && $ext != "jpeg") {
                $path = "Arquivo de imagem inválido!";
                $file = null;
                return $path;
            } else {
                $folder = "pasta_de_" . $_SESSION['user'];
                $num = rand(0, 9);
                $directory = "../../images/users/" . $folder . "/" . $typePicture . "/";

                if (is_dir($directory)) {
                    removeAllFilesAndSubdirectories($directory);
                }
                mkdir($directory, 0777, true);
                move_uploaded_file($file['tmp_name'], $directory . $_SESSION['user'] . "[" . $num . "]" . $hoje . '.' . $ext);
                return $path = "images/users/" . $folder . "/" . $typePicture . "/" . $_SESSION['user'] . "[" . $num . "]" . $hoje . '.' . $ext;
            }
        }
    }

    public function setImages($files, $typePicture)
    {

        $hoje = date("d-m-y");
        $folder = "pasta_de_" . $_SESSION['user'];
        $directory = "../../images/users/" . $folder . "/" . $typePicture . "/";
        if (is_dir($directory)) {
            $this->removeAllFilesAndSubdirectories("../../images/users/" . $folder . "/" . $typePicture . "/");
        }
        mkdir($directory, 0777, true);
        $uploadedPaths = [];

        foreach ($files['tmp_name'] as $key => $tmpName) {
            $ext = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
            $num = rand(0, 9);
            $newFileName = $_SESSION['user'] . "[" . $num . "]" . $hoje . '.' . $ext;
            $destination = $directory . $newFileName;

            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedPaths[] = "images/users/" . $folder . "/" . $typePicture . "/" . $newFileName;
            } else {
                $uploadedPaths[] = "Erro no diretório " . $typePicture . "";
            }
        }

        return $uploadedPaths;
    }

    public function moreThanOne($userId)
    {
        $query = "SELECT * FROM info WHERE id_user LIKE '" . $userId . "'";
        $db = new Connection;

        $linhas = mysqli_num_rows($db->toDatabase($query));
        if ($linhas >= 1) {
            return true;
        }
        return false;
    }

    public function dataBase($query)
    {
        $db = new Connection;

        return $db->toDatabase($query);
    }

    //remove arquivos
    public function removeAllFilesAndSubdirectories($directory)
    {
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $path = $directory . '/' . $file;

                if (is_dir($path)) {
                    removeAllFilesAndSubdirectories($path); // Recursively remove subdirectories
                    rmdir($path);
                } else {
                    unlink($path); // Remove individual file
                }
            }
        }
    }
}
