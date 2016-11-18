<?php
namespace AppBundle\Entity;

class ImagesVariables {
	
	public $id;
	
	public $project_id;
	
	public $images_name;
	
	public $images_path;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set projectId
     *
     * @param integer $projectId
     *
     * @return ImagesVariables
     */
    public function setProjectId($projectId)
    {
        $this->project_id = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Set imagesName
     *
     * @param string $imagesName
     *
     * @return ImagesVariables
     */
    public function setImagesName($imagesName)
    {
        $this->images_name = $imagesName;

        return $this;
    }

    /**
     * Get imagesName
     *
     * @return string
     */
    public function getImagesName()
    {
        return $this->images_name;
    }

    /**
     * Set imagesPath
     *
     * @param string $imagesPath
     *
     * @return ImagesVariables
     */
    public function setImagesPath($imagesPath)
    {
        $this->images_path = $imagesPath;

        return $this;
    }

    /**
     * Get imagesPath
     *
     * @return string
     */
    public function getImagesPath()
    {
        return $this->images_path;
    }
}
