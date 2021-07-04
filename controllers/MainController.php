<?php

namespace SimplePHPFramework\controllers;

use SimplePHPFramework\kernel\View;

require __DIR__ . "/../vendor/autoload.php";


class MainController
{
    private View $viewEngine;
    public function __construct()
    {
        // Initialize the viewEngine
        $this->viewEngine = new View;
    }
    public function index()
    {
        $dataJson = '[{"id":1,"title":"Staff Accountant III","image":"http://dummyimage.com/157x100.png/dddddd/000000","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Laurice Forson","date":"6/10/2021"},
        {"id":2,"title":"Accountant IV","image":"http://dummyimage.com/230x100.png/dddddd/000000","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Pam Pugh","date":"10/10/2020"},
        {"id":3,"title":"Food Chemist","image":"http://dummyimage.com/132x100.png/cc0000/ffffff","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Amity Aingell","date":"9/3/2020"},
        {"id":4,"title":"Help Desk Operator","image":"http://dummyimage.com/130x100.png/dddddd/000000","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Celisse Cozby","date":"1/22/2021"},
        {"id":5,"title":"Speech Pathologist","image":"http://dummyimage.com/169x100.png/ff4444/ffffff","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Stephannie Pinner","date":"12/15/2020"},
        {"id":6,"title":"Accounting Assistant I","image":"http://dummyimage.com/221x100.png/5fa2dd/ffffff","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Marcos Sale","date":"1/30/2021"},
        {"id":7,"title":"VP Accounting","image":"http://dummyimage.com/221x100.png/dddddd/000000","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Roldan Baudin","date":"6/14/2021"},
        {"id":8,"title":"Human Resources Manager","image":"http://dummyimage.com/187x100.png/cc0000/ffffff","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Terry Lyste","date":"11/29/2020"},
        {"id":9,"title":"Geologist II","image":"http://dummyimage.com/192x100.png/ff4444/ffffff","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Codie Balharry","date":"10/17/2020"},
        {"id":10,"title":"VP Quality Control","image":"http://dummyimage.com/230x100.png/5fa2dd/ffffff","body":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.","author":"Shandy Crutchley","date":"5/2/2021"}]';
        $dataAssoc = json_decode($dataJson, true);
        // Render the template
        return $this->viewEngine->render("home/index.pug", [
            "title" => "PHP MVC Blog",
            "author" => "Mohammad Raufzahed",
            "authorDescription" => "A Profassional Web Developer",
            "articles" => $dataAssoc
        ]);
    }
}
