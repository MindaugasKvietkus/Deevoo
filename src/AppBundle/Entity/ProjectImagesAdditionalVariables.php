<?php
namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectImagesAdditionalVariables {

    public $project_name;

    public $images_name;

    public $images_path;

    public $images_id;

    public $delete;

    public $update;

    public $insert;

    /**
     * @var UploadedFile
     */

    public $add_file;
}


/**
 * Created by PhpStorm.
 * User: Mariukas
 * Date: 2016.11.10
 * Time: 13:23
 */